<?php

namespace App\Services;

use App\Models\Doctor;

/**
 * Service for calculating dashboard statistics.
 * 
 * Extracts statistics logic from controllers into a dedicated service
 * for better maintainability and testability.
 */
class DashboardStatsService extends BaseService
{
    /**
     * Get doctor dashboard statistics.
     *
     * @param Doctor $doctor
     * @return array
     */
    public function getDoctorDashboardStats(Doctor $doctor): array
    {
        $today = today();
        $thisMonth = now()->startOfMonth();

        return [
            'total_appointments' => $doctor->appointments()->count(),
            'today_appointments' => $doctor->appointments()->whereDate('appointment_date', $today)->count(),
            'pending_appointments' => $doctor->appointments()->where('status', 'pending')->count(),
            'this_month_appointments' => $doctor->appointments()->whereDate('appointment_date', '>=', $thisMonth)->count(),
            'completed_appointments' => $doctor->appointments()->where('status', 'completed')->count(),
            'cancelled_appointments' => $doctor->appointments()->where('status', 'cancelled')->count(),
            'average_rating' => $doctor->average_rating,
            'total_reviews' => $doctor->total_reviews,
            'this_month_reviews' => $doctor->reviews()->whereDate('created_at', '>=', $thisMonth)->count(),
            'revenue_this_month' => $this->calculateMonthlyRevenue($doctor),
            'total_notes' => $doctor->user->doctorNotes()->count(),
            'voice_notes' => $doctor->user->doctorNotes()->where('note_type', 'voice')->count(),
            'this_month_notes' => $doctor->user->doctorNotes()->whereDate('created_at', '>=', $thisMonth)->count(),
        ];
    }

    /**
     * Calculate monthly revenue from completed appointments.
     *
     * @param Doctor $doctor
     * @return float
     */
    protected function calculateMonthlyRevenue(Doctor $doctor): float
    {
        $thisMonth = now()->startOfMonth();

        $revenueCents = $doctor->appointments()
            ->where('status', 'completed')
            ->whereDate('appointment_date', '>=', $thisMonth)
            ->sum('consultation_fee');

        // Convert from cents to dollars
        return $revenueCents / 100;
    }
}
