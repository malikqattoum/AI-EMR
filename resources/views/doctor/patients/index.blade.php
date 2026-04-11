@extends('layouts.doctor')

@section('title', 'My Patients')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom-openai.css') }}">
<link rel="stylesheet" href="{{ asset('css/doctor-dashboard.css') }}">

<style>
/* Patient avatar */
.patient-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
}

.patient-avatar-male {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.7) 0%, rgba(37, 99, 235, 0.7) 100%);
    color: white;
}

.patient-avatar-female {
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.7) 0%, rgba(190, 24, 93, 0.7) 100%);
    color: white;
}

.patient-avatar-default {
    background: linear-gradient(135deg, rgba(107, 114, 128, 0.7) 0%, rgba(75, 85, 99, 0.7) 100%);
    color: white;
}

/* Status badges */
.status-active { background: linear-gradient(135deg, rgba(0, 212, 170, 0.7) 0%, rgba(16, 185, 129, 0.7) 100%); }
.status-inactive { background: linear-gradient(135deg, rgba(107, 114, 128, 0.7) 0%, rgba(75, 85, 99, 0.7) 100%); }
.status-new { background: linear-gradient(135deg, rgba(59, 130, 246, 0.7) 0%, rgba(37, 99, 235, 0.7) 100%); }

/* Action buttons */
.action-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Table enhancements */
.custom-table tbody tr {
    transition: all 0.2s ease;
}

.custom-table tbody tr:hover {
    background-color: rgba(59, 146, 246, 0.05);
}

/* Search box enhancement */
.search-box {
    border-radius: 10px;
    border: 1px solid rgba(232, 237, 245, 0.2);
    transition: all 0.3s ease;
    background: rgba(15, 28, 58, 0.8);
    color: #e8edf5;
}

.search-box::placeholder {
    color: rgba(232, 237, 245, 0.4);
}

.search-box:focus {
    border-color: #00d4aa;
    box-shadow: 0 0 0 3px rgba(0, 212, 170, 0.15);
    outline: none;
}
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Patients</li>
            </ol>
        </nav>

        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2>My Patients</h2>
                    <p>Your assigned patient profiles and records</p>
                </div>
                <a href="{{ route('doctor.appointments.create') }}" class="btn btn-secondary btn-lg mt-3 mt-md-0">
                    <i class="fas fa-user-plus me-2"></i>New Appointment
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon" style="background: linear-gradient(135deg, rgba(0,212,170,0.3) 0%, rgba(0,212,170,0.15) 100%);">
                        <i class="fas fa-users"></i>
                    </div>
                    <p class="stats-number">{{ $patients->total() }}</p>
                    <p class="stats-label">Total Patients</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon" style="background: linear-gradient(135deg, rgba(0,212,170,0.25) 0%, rgba(0,212,170,0.1) 100%);">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <p class="stats-number">{{ collect($patients->items())->filter(fn($p) => ($p->is_active ?? true))->count() }}</p>
                    <p class="stats-label">Active Patients</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon" style="background: linear-gradient(135deg, rgba(59,130,246,0.25) 0%, rgba(59,130,246,0.1) 100%);">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <p class="stats-number">{{ collect($patients->items())->filter(fn($p) => $p->appointments->isNotEmpty())->count() }}</p>
                    <p class="stats-label">With Appointments</p>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="table-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0"><i class="fas fa-search me-2"></i>Search & Filters</h6>
            </div>
            <form method="GET" action="{{ route('doctor.patients.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control search-box"
                           placeholder="Search by name, email, or phone..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="gender" class="form-select search-box">
                        <option value="">All Genders</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select search-box">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select search-box">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary-custom flex-grow-1">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('doctor.patients.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Patients List -->
        @if($patients->count() > 0)
            <div class="table-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>Patients ({{ $patients->total() }})</h6>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table mb-0">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Age / Gender</th>
                                <th>Contact</th>
                                <th>Last Visit</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patients as $patient)
                                <tr>
                                    <!-- Patient Info -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $avatarClass = 'patient-avatar-default';
                                                $initials = '??';
                                                if ($patient->gender == 'male') {
                                                    $avatarClass = 'patient-avatar-male';
                                                } elseif ($patient->gender == 'female') {
                                                    $avatarClass = 'patient-avatar-female';
                                                }
                                                $initials = collect(explode(' ', $patient->name))->map(function($word) {
                                                    return substr($word, 0, 1);
                                                })->take(2)->join('');
                                                if (strlen($initials) < 2) {
                                                    $initials = substr($patient->name, 0, 2);
                                                }
                                                $initials = strtoupper($initials);
                                            @endphp
                                            <div class="patient-avatar {{ $avatarClass }} me-3">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $patient->name }}</div>
                                                <small class="text-muted">ID: {{ $patient->id }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Age / Gender -->
                                    <td>
                                        @if($patient->age)
                                            <span class="fw-medium">{{ $patient->age }} years</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                        <br>
                                        <small class="text-muted">{{ ucfirst($patient->gender ?? 'Not specified') }}</small>
                                    </td>

                                    <!-- Contact -->
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $patient->email }}</span>
                                            @if($patient->phone)
                                                <small class="text-muted">{{ $patient->phone }}</small>
                                            @else
                                                <small class="text-muted">No phone</small>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Last Visit -->
                                    <td>
                                        @if($patient->appointments->first())
                                            <div class="fw-medium">
                                                {{ $patient->appointments->first()->appointment_date->format('M j, Y') }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $patient->appointments->first()->appointment_date->diffForHumans() }}
                                            </small>
                                        @else
                                            <span class="badge bg-secondary">No visits</span>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        @if($patient->is_active ?? true)
                                            <span class="badge status-active">Active</span>
                                        @else
                                            <span class="badge status-inactive">Inactive</span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('doctor.patients.show', $patient->id) }}"
                                               class="btn btn-sm btn-outline-primary action-btn" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('doctor.patients.edit', $patient->id) }}"
                                               class="btn btn-sm btn-outline-warning action-btn" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('ai.ambient-listening.index', ['patient' => $patient->id]) }}"
                                               class="btn btn-sm btn-outline-success action-btn" title="Start Consultation">
                                                <i class="fas fa-microphone"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger action-btn"
                                                    title="Delete"
                                                    onclick="deletePatient({{ $patient->id }}, '{{ addslashes($patient->name) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($patients->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $patients->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="table-card text-center py-5">
                <div class="empty-icon mb-3">
                    <i class="fas fa-users"></i>
                </div>
                <h5>No patients found</h5>
                <p class="text-muted">
                    @if(request('search') || request('gender') || request('status'))
                        No patients match your search criteria.
                    @else
                        You haven't added any patients yet. Create appointments to add patients.
                    @endif
                </p>
                <a href="{{ route('doctor.appointments.create') }}" class="btn btn-primary-custom">
                    <i class="fas fa-calendar-plus me-2"></i>Create Appointment
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deletePatientName"></strong>?</p>
                <p class="text-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i>This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Patient
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deletePatient(id, name) {
    document.getElementById('deletePatientName').textContent = name;
    document.getElementById('deleteForm').action = '/doctor/patients/' + id;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection
