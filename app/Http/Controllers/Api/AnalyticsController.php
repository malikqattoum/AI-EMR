<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsPermissions;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    protected AnalyticsPermissions $analyticsPermissions;

    public function __construct(AnalyticsPermissions $analyticsPermissions)
    {
        $this->analyticsPermissions = $analyticsPermissions;
    }

    /**
     * Get executive dashboard data
     */
    public function getExecutiveDashboard(Request $request): JsonResponse
    {
        $user = $request->user();

        // Check dashboard access
        if (!$this->analyticsPermissions->canAccessDashboard($user, 'executive')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied to executive dashboard'
            ], 403);
        }

        // Get data scope for filtering
        $dataScope = $this->analyticsPermissions->getDataScope($user, 'executive');

        // Calculate real metrics from database
        $dateRange = $request->input('date_range', '30_days');
        $startDate = match ($dateRange) {
            '7_days' => now()->subDays(7),
            '30_days' => now()->subDays(30),
            '90_days' => now()->subDays(90),
            '1_year' => now()->subYear(),
            default => now()->subDays(30),
        };

        // Calculate the period length in days for like-for-like comparison
        $periodDays = $startDate->diffInDays(now());

        // Revenue metrics
        $currentRevenue = DB::table('stripe_invoices')
            ->where('created_at', '>=', $startDate)
            ->where('status', 'paid')
            ->sum('amount');

        // Previous period: same-length window immediately before current window
        $previousPeriodStart = now()->copy()->subDays($periodDays * 2);
        $previousPeriodEnd = $startDate->copy();

        $previousRevenue = DB::table('stripe_invoices')
            ->where('created_at', '>=', $previousPeriodStart)
            ->where('created_at', '<', $previousPeriodEnd)
            ->where('status', 'paid')
            ->sum('amount');

        $revenueChange = $previousRevenue > 0 ? round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 2) : 0;

        // Patient satisfaction metrics
        $avgSatisfaction = DB::table('reviews')
            ->where('created_at', '>=', $startDate)
            ->avg('rating') ?? 0;

        // Operational efficiency (appointment completion rate)
        $totalAppointments = DB::table('appointments')
            ->where('appointment_date', '>=', $startDate)
            ->count();

        $completedAppointments = DB::table('appointments')
            ->where('appointment_date', '>=', $startDate)
            ->where('status', 'completed')
            ->count();

        $completionRate = $totalAppointments > 0 ? round(($completedAppointments / $totalAppointments) * 100, 2) : 0;

        // Clinical outcomes (diagnosis follow-up success rate)
        $totalDiagnoses = DB::table('diagnoses')
            ->where('created_at', '>=', $startDate)
            ->count();

        $followUpDiagnoses = DB::table('diagnoses')
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('follow_up_date')
            ->count();

        $clinicalOutcomeRate = $totalDiagnoses > 0 ? round(($followUpDiagnoses / $totalDiagnoses) * 100, 2) : 0;

        // Revenue trend data (last 5 months)
        $revenueTrend = [];
        for ($i = 4; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthRevenue = DB::table('stripe_invoices')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', 'paid')
                ->sum('amount');

            $revenueTrend['labels'][] = $month->format('M');
            $revenueTrend['data'][] = $monthRevenue;
        }

        // Patient satisfaction distribution
        $satisfactionDistribution = DB::table('reviews')
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $distributionData = [];
        for ($i = 1; $i <= 5; $i++) {
            $distributionData[] = $satisfactionDistribution[$i] ?? 0;
        }

        $data = [
            'summary' => [
                'revenue' => [
                    'value' => $currentRevenue,
                    'change' => $revenueChange,
                    'trend' => $revenueChange >= 0 ? 'up' : 'down',
                    'target' => config('analytics.targets.revenue', 130000)
                ],
                'patient_satisfaction' => [
                    'value' => round($avgSatisfaction, 2),
                    'change' => 0, // Would need historical comparison
                    'trend' => 'stable',
                    'target' => config('analytics.targets.patient_satisfaction', 4.9)
                ],
                'operational_efficiency' => [
                    'value' => $completionRate,
                    'change' => 0,
                    'trend' => $completionRate >= 95 ? 'up' : 'down',
                    'target' => config('analytics.targets.operational_efficiency', 95.0)
                ],
                'clinical_outcomes' => [
                    'value' => $clinicalOutcomeRate,
                    'change' => 0,
                    'trend' => $clinicalOutcomeRate >= 80 ? 'up' : 'down',
                    'target' => config('analytics.targets.clinical_outcomes', 90.0)
                ]
            ],
            'charts' => [
                'revenue_trend' => $revenueTrend,
                'patient_satisfaction_distribution' => [
                    'labels' => ['1★', '2★', '3★', '4★', '5★'],
                    'data' => $distributionData
                ]
            ],
            'alerts' => $this->generateDashboardAlerts([
                'revenue' => $currentRevenue,
                'patient_satisfaction' => $avgSatisfaction,
                'operational_efficiency' => $completionRate,
                'clinical_outcomes' => $clinicalOutcomeRate,
            ])
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'meta' => [
                'last_updated' => now()->toISOString(),
                'data_freshness' => 'realtime',
                'permissions' => [
                    'read' => true,
                    'export' => $this->analyticsPermissions->canAccessFeature($user, 'export_data'),
                    'customize' => $this->analyticsPermissions->canAccessFeature($user, 'dashboard_customization')
                ]
            ]
        ]);
    }

    /**
     * Get revenue analytics data
     */
    public function getRevenueAnalytics(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$this->analyticsPermissions->canAccessDashboard($user, 'revenue')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied to revenue dashboard'
            ], 403);
        }

        if (!$this->analyticsPermissions->canViewKpi($user, 'revenue_metrics')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied to revenue metrics'
            ], 403);
        }

        // Calculate real revenue metrics
        $currentMonth = now();
        $lastMonth = now()->subMonth();

        $currentMRR = DB::table('stripe_invoices')
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->where('status', 'paid')
            ->sum('amount');

        $lastMRR = DB::table('stripe_invoices')
            ->whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->where('status', 'paid')
            ->sum('amount');

        $mrrChange = $lastMRR > 0 ? round((($currentMRR - $lastMRR) / $lastMRR) * 100, 2) : 0;

        // ARPU (Average Revenue Per User)
        $activeUsers = DB::table('users')
            ->where('is_active', true)
            ->count();

        $arpu = $activeUsers > 0 ? round($currentMRR / $activeUsers, 2) : 0;

        // Churn rate (cancelled subscriptions / total subscriptions)
        $totalSubscriptions = DB::table('subscriptions')->count();
        $cancelledSubscriptions = DB::table('subscriptions')
            ->where('status', 'cancelled')
            ->count();

        $churnRate = $totalSubscriptions > 0 ? round(($cancelledSubscriptions / $totalSubscriptions) * 100, 2) : 0;

        // CLV (Customer Lifetime Value)
        $avgLifespan = $churnRate > 0 ? round(100 / $churnRate, 2) : 12; // months
        $clv = round($arpu * $avgLifespan, 2);

        // Revenue breakdown by subscription plan
        $planBreakdown = DB::table('subscriptions')
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->select(
                'subscription_plans.name as plan',
                DB::raw('COUNT(subscriptions.id) as subscriber_count'),
                DB::raw('SUM(subscriptions.amount) as total_revenue')
            )
            ->where('subscriptions.status', 'active')
            ->groupBy('subscription_plans.name')
            ->get();

        $totalPlanRevenue = $planBreakdown->sum('total_revenue');
        $byPlan = $planBreakdown->map(function ($plan) use ($totalPlanRevenue) {
            return [
                'plan' => $plan->plan,
                'revenue' => $plan->total_revenue,
                'percentage' => $totalPlanRevenue > 0 ? round(($plan->total_revenue / $totalPlanRevenue) * 100, 2) : 0,
                'subscriber_count' => $plan->subscriber_count,
            ];
        })->sortByDesc('revenue')->values();

        // Simple forecast based on 3-month trend
        $threeMonthsAgo = now()->subMonths(3);
        $threeMonthRevenue = DB::table('stripe_invoices')
            ->where('created_at', '>=', $threeMonthsAgo)
            ->where('status', 'paid')
            ->sum('amount');

        $avgMonthlyGrowth = $threeMonthsAgo > 0 ? round($threeMonthRevenue / 3, 2) : 0;
        $nextMonthForecast = $currentMRR + $avgMonthlyGrowth;

        $data = [
            'kpis' => [
                'mrr' => ['value' => $currentMRR, 'change' => $mrrChange],
                'arpu' => ['value' => $arpu, 'change' => 0],
                'churn_rate' => ['value' => $churnRate, 'change' => 0],
                'clv' => ['value' => $clv, 'change' => 0]
            ],
            'breakdown' => [
                'by_plan' => $byPlan
            ],
            'forecast' => [
                'next_month' => $nextMonthForecast,
                'confidence' => 75, // Base confidence
                'trend' => $mrrChange >= 0 ? 'up' : 'down'
            ]
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Get patient satisfaction metrics
     */
    public function getPatientSatisfaction(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$this->analyticsPermissions->canViewKpi($user, 'patient_satisfaction')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied to patient satisfaction metrics'
            ], 403);
        }

        // Mock patient satisfaction data
        $data = [
            'overall' => [
                'nps' => 72,
                'satisfaction_score' => 4.8,
                'response_rate' => 85.5
            ],
            'by_department' => [
                [
                    'department' => 'Cardiology',
                    'satisfaction' => 4.9,
                    'response_count' => 245
                ],
                [
                    'department' => 'Orthopedics',
                    'satisfaction' => 4.7,
                    'response_count' => 189
                ]
            ],
            'trends' => [
                'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                'data' => [4.6, 4.7, 4.8, 4.8]
            ]
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Export dashboard data
     */
    public function exportDashboard(Request $request): JsonResponse
    {
        $user = $request->user();
        $dashboard = $request->input('dashboard', 'executive');

        // Check export permission
        if (!$this->analyticsPermissions->canAccessFeature($user, 'export_data')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Export permission denied'
            ], 403);
        }

        // Check dashboard access
        if (!$this->analyticsPermissions->canAccessDashboard($user, $dashboard)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dashboard access denied'
            ], 403);
        }

        // In real implementation, this would queue an export job
        return response()->json([
            'status' => 'success',
            'data' => [
                'export_id' => 'export_' . uniqid(),
                'status' => 'processing',
                'estimated_completion' => now()->addMinutes(5)->toISOString(),
                'download_url' => '/api/analytics/export/download/export_' . uniqid()
            ]
        ]);
    }

    /**
     * Get user's available dashboards and permissions
     */
    public function getUserPermissions(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'data' => [
                'has_analytics_access' => $this->analyticsPermissions->hasAnalyticsAccess($user),
                'role_name' => $this->analyticsPermissions->getUserRoleName($user),
                'hierarchy_level' => $this->analyticsPermissions->getUserHierarchyLevel($user),
                'available_dashboards' => $this->analyticsPermissions->getAvailableDashboards($user),
                'available_features' => $this->analyticsPermissions->getAvailableFeatures($user),
                'available_kpi_categories' => $this->analyticsPermissions->getAvailableKpiCategories($user),
            ]
        ]);
    }

    /**
     * Apply data filtering to a query (helper method for other controllers)
     */
    protected function applyDataScopeFilter(Builder $query, string $dashboardName = null): Builder
    {
        $user = request()->user();
        return $this->analyticsPermissions->applyDataFilter($user, $query, $dashboardName);
    }

    /**
     * Generate alerts based on current metric values
     */
    protected function generateDashboardAlerts(array $metrics): array
    {
        $alerts = [];

        $targets = [
            'revenue' => config('analytics.targets.revenue', 130000),
            'patient_satisfaction' => config('analytics.targets.patient_satisfaction', 4.9),
            'operational_efficiency' => config('analytics.targets.operational_efficiency', 95.0),
            'clinical_outcomes' => config('analytics.targets.clinical_outcomes', 90.0),
        ];

        if ($metrics['patient_satisfaction'] > 0 && $metrics['patient_satisfaction'] < $targets['patient_satisfaction']) {
            $alerts[] = [
                'id' => 'alert_patient_satisfaction_' . now()->timestamp,
                'type' => 'warning',
                'message' => 'Patient satisfaction is below target (' . round($metrics['patient_satisfaction'], 2) . ' vs ' . $targets['patient_satisfaction'] . ' target)',
                'metric' => 'patient_satisfaction',
                'threshold' => $targets['patient_satisfaction'],
                'current_value' => round($metrics['patient_satisfaction'], 2)
            ];
        }

        if ($metrics['operational_efficiency'] < $targets['operational_efficiency']) {
            $alerts[] = [
                'id' => 'alert_operational_efficiency_' . now()->timestamp,
                'type' => 'warning',
                'message' => 'Appointment completion rate is below target (' . $metrics['operational_efficiency'] . '% vs ' . $targets['operational_efficiency'] . '% target)',
                'metric' => 'operational_efficiency',
                'threshold' => $targets['operational_efficiency'],
                'current_value' => $metrics['operational_efficiency']
            ];
        }

        if ($metrics['revenue'] > 0 && $metrics['revenue'] < $targets['revenue'] * 0.8) {
            $alerts[] = [
                'id' => 'alert_revenue_' . now()->timestamp,
                'type' => 'critical',
                'message' => 'Revenue is significantly below target',
                'metric' => 'revenue',
                'threshold' => $targets['revenue'],
                'current_value' => $metrics['revenue']
            ];
        }

        return $alerts;
    }
}
