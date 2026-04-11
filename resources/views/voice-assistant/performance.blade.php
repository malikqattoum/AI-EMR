@extends('layouts.doctor')

@section('title', 'Ambient Listening Performance')

@push('styles')
<style>
.card { background: var(--card-bg) !important; border: 1px solid var(--card-border) !important; border-radius: 16px !important; }
.card-header { background: rgba(0,212,170,0.05) !important; border-bottom: 1px solid var(--card-border) !important; color: var(--offwhite) !important; }
.card-body { background: transparent !important; }
.form-control, .form-select { background: rgba(10,20,40,0.8) !important; border: 1px solid var(--card-border) !important; color: var(--offwhite) !important; border-radius: 10px !important; }
.form-control:focus { border-color: rgba(0,212,170,0.5) !important; box-shadow: 0 0 0 3px rgba(0,212,170,0.08) !important; }
.form-label { color: var(--offwhite) !important; }
.text-muted { color: var(--muted) !important; }
.bg-primary { background: rgba(0,212,170,0.15) !important; }
.bg-success { background: rgba(0,212,170,0.15) !important; }
.bg-warning { background: rgba(251,191,36,0.15) !important; }
.bg-info { background: rgba(59,130,246,0.15) !important; }
.bg-light { background: rgba(255,255,255,0.04) !important; }
.bg-white { background: var(--card-bg) !important; }
.bg-secondary { background: rgba(255,255,255,0.06) !important; }
.text-primary { color: var(--teal) !important; }
.text-success { color: var(--teal) !important; }
.text-dark { color: var(--offwhite) !important; }
.text-white { color: var(--offwhite) !important; }
.text-danger { color: #f87171 !important; }
.btn-primary { background: var(--teal) !important; border-color: var(--teal) !important; color: var(--navy) !important; font-weight: 600; }
.btn-success { background: rgba(0,212,170,0.15) !important; border-color: rgba(0,212,170,0.3) !important; color: var(--teal) !important; }
.btn-danger { background: rgba(248,113,113,0.15) !important; border-color: rgba(248,113,113,0.3) !important; color: #f87171 !important; }
.btn-warning { background: rgba(251,191,36,0.15) !important; border-color: rgba(251,191,36,0.3) !important; color: #fbbf24 !important; }
.btn-info { background: rgba(59,130,246,0.15) !important; border-color: rgba(59,130,246,0.3) !important; color: #60a5fa !important; }
.btn-secondary { background: rgba(255,255,255,0.06) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: var(--muted) !important; }
.btn-outline-primary { border-color: rgba(0,212,170,0.3) !important; color: var(--teal) !important; }
.btn-outline-secondary { border-color: rgba(255,255,255,0.15) !important; color: var(--muted) !important; }
.alert-success { background: rgba(0,212,170,0.08) !important; border: 1px solid rgba(0,212,170,0.2) !important; color: var(--teal) !important; }
.alert-danger { background: rgba(248,113,113,0.08) !important; border: 1px solid rgba(248,113,113,0.2) !important; color: #f87171 !important; }
.alert-warning { background: rgba(251,191,36,0.08) !important; border: 1px solid rgba(251,191,36,0.2) !important; color: #fbbf24 !important; }
.alert-info { background: rgba(59,130,246,0.08) !important; border: 1px solid rgba(59,130,246,0.2) !important; color: #60a5fa !important; }
.border { border-color: var(--card-border) !important; }
.border-success { border-color: rgba(0,212,170,0.2) !important; }
.border-warning { border-color: rgba(251,191,36,0.2) !important; }
.fw-bold, .fw-semibold { color: var(--offwhite) !important; }
.fw-normal { color: var(--muted) !important; }
.table { color: var(--offwhite) !important; }
.table-hover tbody tr:hover { background-color: rgba(0,212,170,0.05) !important; }
.table td { border-color: var(--card-border) !important; color: var(--offwhite) !important; }
.table th { border-color: var(--card-border) !important; color: var(--muted) !important; }
.pagination .page-link { background: rgba(10,20,40,0.8) !important; border-color: var(--card-border) !important; color: var(--offwhite) !important; }
.pagination .page-item.active .page-link { background: var(--teal) !important; border-color: var(--teal) !important; color: var(--navy) !important; }
.modal-content { background: var(--card-bg) !important; border: 1px solid var(--card-border) !important; }
.modal-header { background: rgba(0,212,170,0.05) !important; border-bottom: 1px solid var(--card-border) !important; color: var(--offwhite) !important; }
.modal-footer { background: rgba(0,212,170,0.03) !important; border-top: 1px solid var(--card-border) !important; }
.badge { color: var(--offwhite) !important; font-weight: 600; }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="container-fluid px-3 px-md-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-chart-line me-2"></i>
                        Ambient Listening Performance Analytics
                    </h4>
                    <div class="card-tools">
                        <select id="timeRange" class="form-select form-select-sm" style="width: auto;">
                            <option value="7" {{ request('days', 30) == 7 ? 'selected' : '' }}>Last 7 days</option>
                            <option value="30" {{ request('days', 30) == 30 ? 'selected' : '' }}>Last 30 days</option>
                            <option value="90" {{ request('days', 30) == 90 ? 'selected' : '' }}>Last 90 days</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Success Rates Overview -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Overall Success Rate</h5>
                                    <h2>{{ number_format($successRates['overall_success_rate'], 1) }}%</h2>
                                    <small>{{ $successRates['total_sessions'] }} sessions</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Live Transcription</h5>
                                    <h2>{{ number_format($successRates['live_transcription_success_rate'], 1) }}%</h2>
                                    <small>Real-time accuracy</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Server Processing</h5>
                                    <h2>{{ number_format($successRates['server_processing_success_rate'], 1) }}%</h2>
                                    <small>AI-enhanced accuracy</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Server Improvement</h5>
                                    <h2>{{ number_format($successRates['server_improvement_rate'], 1) }}%</h2>
                                    <small>Better than live</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Trends Chart -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Performance Trends</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="performanceTrendsChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Statistics -->
                    @if(!empty($errorStatistics))
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Error Analysis</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Error Type</th>
                                                    <th>Count</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($errorStatistics as $error)
                                                <tr>
                                                    <td>{{ ucfirst(str_replace('_', ' ', $error['error_type'])) }}</td>
                                                    <td>{{ $error['count'] }}</td>
                                                    <td>{{ number_format(($error['count'] / $successRates['total_sessions']) * 100, 1) }}%</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Recent Sessions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Recent Sessions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Processing Type</th>
                                                    <th>Success</th>
                                                    <th>Processing Time</th>
                                                    <th>Live Length</th>
                                                    <th>Server Length</th>
                                                    <th>Improvement</th>
                                                    <th>Device</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentSessions as $session)
                                                <tr>
                                                    <td>{{ $session->created_at->format('M j, Y H:i') }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $session->processing_type === 'hybrid' ? 'primary' : 'secondary' }}">
                                                            {{ ucfirst($session->processing_type) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $session->overall_success ? 'success' : 'danger' }}">
                                                            {{ $session->overall_success ? 'Success' : 'Failed' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $session->total_processing_time ? number_format($session->total_processing_time, 0) . 'ms' : 'N/A' }}</td>
                                                    <td>{{ $session->live_transcript_length ?? 'N/A' }}</td>
                                                    <td>{{ $session->server_transcript_length ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($session->server_better_than_live)
                                                            <span class="badge bg-success">Improved</span>
                                                        @else
                                                            <span class="badge bg-secondary">Same/Live</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ ucfirst($session->device_type ?? 'unknown') }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">No sessions recorded yet.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Performance Trends Chart
    const performanceTrendsData = @json($performanceTrends);
    const ctx = document.getElementById('performanceTrendsChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: performanceTrendsData.map(item => item.date),
            datasets: [{
                label: 'Success Rate (%)',
                data: performanceTrendsData.map(item => item.success_rate),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }, {
                label: 'Average Processing Time (ms)',
                data: performanceTrendsData.map(item => item.avg_processing_time),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                yAxisID: 'y1',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Success Rate (%)'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Processing Time (ms)'
                    }
                }
            }
        }
    });

    // Time range selector
    document.getElementById('timeRange').addEventListener('change', function() {
        const days = this.value;
        window.location.href = '{{ route("ai.ambient-listening.performance") }}?days=' + days;
    });
});
</script>
@endsection