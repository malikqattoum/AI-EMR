@extends('layouts.doctor')

@section('title', 'Book Appointment')

@push('styles')
<style>
/* ============================================
   APPOINTMENT BOOKING - CLEAN DARK DESIGN
   ============================================ */

body {
    background: #060d1f !important;
}

.appt-booking-page,
.doctor-page,
.doctor-container {
    background: #060d1f !important;
}

.appt-booking-page {
    padding: 2rem;
    max-width: 900px;
    margin: 0 auto;
    min-height: 100vh;
}

/* Header */
.appt-header {
    margin-bottom: 2rem;
}

.appt-header h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #e8ede7 !important;
    margin-bottom: 0.25rem;
}

.appt-header p {
    color: rgba(232, 237, 231, 0.55) !important;
    margin: 0;
    font-size: 0.95rem;
}

/* Doctor Card */
.appt-doctor-card {
    background: rgba(10, 22, 40, 0.9) !important;
    border: 1px solid rgba(0, 212, 170, 0.15) !important;
    border-radius: 14px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.appt-doctor-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(0, 212, 170, 0.15) !important;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(0, 212, 170, 0.3) !important;
    flex-shrink: 0;
}

.appt-doctor-avatar i {
    font-size: 1.5rem;
    color: #00d4aa !important;
}

.appt-doctor-info h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #e8ede7 !important;
    margin-bottom: 0.2rem;
}

.appt-doctor-info .specialty {
    color: #00d4aa !important;
    font-size: 0.85rem;
    margin-bottom: 0.15rem;
}

.appt-doctor-info .fee {
    color: rgba(232, 237, 231, 0.5) !important;
    font-size: 0.8rem;
}

.appt-doctor-info .fee span {
    color: #00d4aa !important;
    font-weight: 600;
}

/* Steps Container */
.appt-steps {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

/* Step Card */
.appt-step {
    background: rgba(10, 22, 40, 0.85) !important;
    border: 1px solid rgba(0, 212, 170, 0.12) !important;
    border-radius: 14px;
    overflow: hidden;
}

.appt-step-header {
    background: rgba(0, 212, 170, 0.08) !important;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.85rem;
    border-bottom: 1px solid rgba(0, 212, 170, 0.1) !important;
}

.appt-step-num {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #00d4aa !important;
    color: #060d1f !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.appt-step-title {
    font-size: 1rem;
    font-weight: 600;
    color: #e8ede7 !important;
    margin: 0;
}

.appt-step-body {
    padding: 1.25rem;
}

/* Patient Type Grid */
.patient-type-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.patient-type-btn {
    background: rgba(6, 13, 31, 0.6) !important;
    border: 2px solid rgba(0, 212, 170, 0.12) !important;
    border-radius: 12px;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 0.85rem;
    text-align: left;
}

.patient-type-btn:hover {
    border-color: rgba(0, 212, 170, 0.35) !important;
    background: rgba(0, 212, 170, 0.05) !important;
}

.patient-type-btn.selected {
    border-color: #00d4aa !important;
    background: rgba(0, 212, 170, 0.1) !important;
}

.patient-type-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.patient-type-icon.existing {
    background: rgba(59, 130, 246, 0.15);
}

.patient-type-icon.existing i {
    color: #60a5fa !important;
}

.patient-type-icon.new {
    background: rgba(0, 212, 170, 0.12);
}

.patient-type-icon.new i {
    color: #00d4aa !important;
}

.patient-type-text h4 {
    font-size: 0.95rem;
    font-weight: 600;
    color: #e8ede7 !important;
    margin-bottom: 0.15rem;
}

.patient-type-text p {
    font-size: 0.8rem;
    color: rgba(232, 237, 231, 0.5) !important;
    margin: 0;
}

/* Search Box */
.appt-search-wrap {
    position: relative;
    margin-top: 0.5rem;
}

.appt-search-input {
    width: 100%;
    padding: 0.85rem 1rem 0.85rem 2.75rem;
    background: rgba(6, 13, 31, 0.8) !important;
    border: 1px solid rgba(0, 212, 170, 0.12) !important;
    border-radius: 10px;
    color: #e8ede7 !important;
    font-size: 0.95rem;
}

.appt-search-input::placeholder {
    color: rgba(232, 237, 231, 0.35) !important;
}

.appt-search-input:focus {
    outline: none;
    border-color: rgba(0, 212, 170, 0.5) !important;
    box-shadow: 0 0 0 3px rgba(0, 212, 170, 0.08) !important;
}

.appt-search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(232, 237, 231, 0.4) !important;
}

/* Search Results */
.appt-search-results {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background: rgba(10, 22, 40, 0.98) !important;
    border: 1px solid rgba(0, 212, 170, 0.15) !important;
    border-radius: 10px;
    max-height: 180px;
    overflow-y: auto;
    z-index: 100;
    display: none;
}

.appt-search-results.show {
    display: block;
}

.appt-result-item {
    padding: 0.85rem 1rem;
    border-bottom: 1px solid rgba(0, 212, 170, 0.06) !important;
    cursor: pointer;
    transition: background 0.15s;
}

.appt-result-item:last-child {
    border-bottom: none !important;
}

.appt-result-item:hover {
    background: rgba(0, 212, 170, 0.08) !important;
}

.appt-result-item .name {
    font-weight: 500;
    color: #e8ede7 !important;
    font-size: 0.9rem;
}

.appt-result-item .email {
    font-size: 0.8rem;
    color: rgba(232, 237, 231, 0.5) !important;
}

/* Selected Patient */
.appt-selected-patient {
    background: rgba(0, 212, 170, 0.08) !important;
    border: 1px solid rgba(0, 212, 170, 0.2) !important;
    border-radius: 10px;
    padding: 0.9rem 1rem;
    margin-top: 0.75rem;
    display: none;
    align-items: center;
    gap: 0.75rem;
}

.appt-selected-patient.show {
    display: flex;
}

.appt-selected-patient i {
    color: #00d4aa !important;
    font-size: 1.25rem;
}

.appt-selected-patient .name {
    font-weight: 500;
    color: #e8ede7 !important;
    font-size: 0.9rem;
}

.appt-selected-patient .email {
    font-size: 0.8rem;
    color: rgba(232, 237, 231, 0.5) !important;
}

/* New Patient Form */
.appt-new-patient-form {
    display: none;
    margin-top: 1.25rem;
    padding-top: 1.25rem;
    border-top: 1px solid rgba(0, 212, 170, 0.08) !important;
}

.appt-new-patient-form.show {
    display: block;
}

.appt-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.85rem;
}

.appt-form-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 500;
    color: #e8ede7 !important;
    margin-bottom: 0.4rem;
}

.appt-form-group input,
.appt-form-group select {
    width: 100%;
    padding: 0.7rem 0.9rem;
    background: rgba(6, 13, 31, 0.8) !important;
    border: 1px solid rgba(0, 212, 170, 0.12) !important;
    border-radius: 8px;
    color: #e8ede7 !important;
    font-size: 0.9rem;
}

.appt-form-group input:focus,
.appt-form-group select:focus {
    outline: none;
    border-color: rgba(0, 212, 170, 0.5) !important;
    box-shadow: 0 0 0 3px rgba(0, 212, 170, 0.08) !important;
}

.appt-form-group select option {
    background: #060d1f;
    color: #e8ede7;
}

/* Date Grid */
.appt-date-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 0.75rem;
}

.appt-date-btn {
    background: rgba(6, 13, 31, 0.6) !important;
    border: 2px solid rgba(0, 212, 170, 0.1) !important;
    border-radius: 10px;
    padding: 0.85rem 0.5rem;
    cursor: pointer;
    transition: all 0.25s ease;
    text-align: center;
}

.appt-date-btn:hover {
    border-color: rgba(0, 212, 170, 0.35) !important;
}

.appt-date-btn.selected {
    border-color: #00d4aa !important;
    background: rgba(0, 212, 170, 0.1) !important;
}

.appt-date-btn .day-name {
    color: #00d4aa !important;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.appt-date-btn .day-num {
    color: #e8ede7 !important;
    font-size: 1.35rem;
    font-weight: 700;
    line-height: 1.3;
}

.appt-date-btn .month {
    color: rgba(232, 237, 231, 0.5) !important;
    font-size: 0.8rem;
}

.appt-date-btn .slots {
    margin-top: 0.4rem;
    font-size: 0.7rem;
    color: rgba(232, 237, 231, 0.4) !important;
}

.appt-date-btn .slots span {
    color: #00d4aa !important;
    font-weight: 600;
}

/* Time Slots */
.appt-time-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(95px, 1fr));
    gap: 0.5rem;
    margin-top: 1rem;
}

.appt-time-btn {
    background: rgba(6, 13, 31, 0.6) !important;
    border: 2px solid rgba(0, 212, 170, 0.1) !important;
    border-radius: 8px;
    padding: 0.7rem 0.5rem;
    color: #e8ede7 !important;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.25s ease;
    text-align: center;
}

.appt-time-btn:hover {
    border-color: rgba(0, 212, 170, 0.35) !important;
}

.appt-time-btn.selected {
    border-color: #00d4aa !important;
    background: rgba(0, 212, 170, 0.12) !important;
    color: #00d4aa !important;
    font-weight: 600;
}

/* Type Grid */
.appt-type-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.85rem;
}

.appt-type-btn {
    background: rgba(6, 13, 31, 0.6) !important;
    border: 2px solid rgba(0, 212, 170, 0.1) !important;
    border-radius: 12px;
    padding: 1.25rem 0.75rem;
    cursor: pointer;
    transition: all 0.25s ease;
    text-align: center;
}

.appt-type-btn:hover {
    border-color: rgba(0, 212, 170, 0.35) !important;
}

.appt-type-btn.selected {
    border-color: #00d4aa !important;
    background: rgba(0, 212, 170, 0.1) !important;
}

.appt-type-btn i {
    font-size: 1.75rem;
    margin-bottom: 0.6rem;
}

.appt-type-btn i.in-person { color: #00d4aa !important; }
.appt-type-btn i.video { color: #60a5fa !important; }
.appt-type-btn i.phone { color: #a78bfa !important; }

.appt-type-btn h4 {
    font-size: 0.9rem;
    font-weight: 600;
    color: #e8ede7 !important;
    margin-bottom: 0.2rem;
}

.appt-type-btn p {
    font-size: 0.75rem;
    color: rgba(232, 237, 231, 0.5) !important;
    margin: 0;
}

/* Reason */
.appt-reason {
    width: 100%;
    min-height: 110px;
    padding: 0.9rem 1rem;
    background: rgba(6, 13, 31, 0.8) !important;
    border: 1px solid rgba(0, 212, 170, 0.12) !important;
    border-radius: 10px;
    color: #e8ede7 !important;
    font-size: 0.95rem;
    resize: vertical;
    font-family: inherit;
}

.appt-reason:focus {
    outline: none;
    border-color: rgba(0, 212, 170, 0.5) !important;
    box-shadow: 0 0 0 3px rgba(0, 212, 170, 0.08) !important;
}

.appt-reason::placeholder {
    color: rgba(232, 237, 231, 0.35) !important;
}

/* Summary */
.appt-summary {
    background: rgba(10, 22, 40, 0.75) !important;
    border: 1px solid rgba(0, 212, 170, 0.1) !important;
    border-radius: 14px;
    margin-top: 1.5rem;
    overflow: hidden;
}

.appt-summary-header {
    background: rgba(0, 212, 170, 0.06) !important;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(0, 212, 170, 0.08) !important;
}

.appt-summary-header h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #e8ede7 !important;
    margin: 0;
}

.appt-summary-body {
    padding: 1.25rem;
}

.appt-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.6rem 0;
    border-bottom: 1px solid rgba(0, 212, 170, 0.06) !important;
}

.appt-summary-row:last-child {
    border-bottom: none !important;
}

.appt-summary-row .label {
    color: rgba(232, 237, 231, 0.5) !important;
    font-size: 0.85rem;
}

.appt-summary-row .value {
    color: #e8ede7 !important;
    font-weight: 500;
    font-size: 0.9rem;
}

.appt-summary-row.total {
    margin-top: 0.5rem;
    padding-top: 0.85rem;
    border-top: 2px solid rgba(0, 212, 170, 0.2) !important;
}

.appt-summary-row.total .label {
    color: #e8ede7 !important;
    font-weight: 600;
}

.appt-summary-row.total .value {
    color: #00d4aa !important;
    font-size: 1.15rem;
    font-weight: 700;
}

/* Submit */
.appt-submit-btn {
    width: 100%;
    padding: 1rem;
    margin-top: 1rem;
    background: linear-gradient(135deg, rgba(0, 212, 170, 0.15) 0%, rgba(0, 212, 170, 0.08) 100%);
    border: 2px solid #00d4aa !important;
    border-radius: 12px;
    color: #00d4aa !important;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
}

.appt-submit-btn:hover:not(:disabled) {
    background: #00d4aa !important;
    color: #060d1f !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 212, 170, 0.3);
}

.appt-submit-btn:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

/* No Slots */
.appt-no-slots {
    text-align: center;
    padding: 2rem;
    color: rgba(232, 237, 231, 0.5) !important;
}

.appt-no-slots i {
    font-size: 2.5rem;
    margin-bottom: 0.75rem;
    opacity: 0.4;
}

.appt-no-slots h4 {
    color: #e8ede7 !important;
    margin-bottom: 0.3rem;
}

.appt-no-slots p {
    font-size: 0.85rem;
    margin: 0;
}

/* Info note */
.appt-info-note {
    background: rgba(59, 130, 246, 0.08) !important;
    border: 1px solid rgba(59, 130, 246, 0.15) !important;
    border-radius: 8px;
    padding: 0.85rem 1rem;
    margin-top: 1rem;
    display: flex;
    align-items: flex-start;
    gap: 0.6rem;
}

.appt-info-note i {
    color: #60a5fa !important;
    margin-top: 0.1rem;
}

.appt-info-note p {
    font-size: 0.8rem;
    color: rgba(232, 237, 231, 0.6) !important;
    margin: 0;
}

/* Back link */
.appt-back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    color: #00d4aa !important;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
    text-decoration: none;
}

.appt-back-link:hover {
    color: #00eabb !important;
    text-decoration: none;
}

/* Responsive */
@media (max-width: 767px) {
    .appt-booking-page {
        padding: 1rem;
        min-height: auto;
    }

    .patient-type-row,
    .appt-form-grid,
    .appt-type-grid {
        grid-template-columns: 1fr;
    }

    .appt-date-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .appt-time-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
@endpush

@section('content')
<div class="doctor-page" style="background: #060d1f !important;">
<div class="doctor-container" style="background: #060d1f !important;">
<div class="appt-booking-page" style="background: #060d1f !important;">

    <!-- Back Link -->
    <a href="{{ route('doctor.appointments.index') }}" class="appt-back-link">
        <i class="fas fa-arrow-left"></i> Back to Appointments
    </a>

    <!-- Header -->
    <div class="appt-header">
        <h1>Book Appointment</h1>
        <p>Schedule a consultation with Dr. {{ $doctor->user->name }}</p>
    </div>

    <!-- Doctor Card -->
    <div class="appt-doctor-card">
        @if($doctor->profile_image)
            <img src="{{ asset('storage/' . $doctor->profile_image) }}" alt="{{ $doctor->user->name }}"
                 class="appt-doctor-avatar" style="object-fit: cover;">
        @else
            <div class="appt-doctor-avatar">
                <i class="fas fa-user-md"></i>
            </div>
        @endif
        <div class="appt-doctor-info">
            <h3>Dr. {{ $doctor->user->name }}</h3>
            <div class="specialty">{{ $doctor->specialty->name }}</div>
            <div class="fee">Consultation Fee: <span>${{ number_format($doctor->consultation_fee / 100, 2) }}</span></div>
        </div>
    </div>

    <form method="POST" action="{{ route('doctor.appointments.store') }}" id="appointmentForm">
        @csrf
        <input type="hidden" name="patient_type" id="patientType" value="existing">
        <input type="hidden" name="existing_patient_id" id="existingPatientId">
        <input type="hidden" name="appointment_type" id="selectedType" value="{{ $firstType ?? 'in_person' }}">
        <input type="hidden" name="appointment_date" id="selectedDateTime">

        <!-- Steps -->
        <div class="appt-steps">

            <!-- Step 1: Patient -->
            <div class="appt-step">
                <div class="appt-step-header">
                    <div class="appt-step-num">1</div>
                    <h3 class="appt-step-title">Select Patient</h3>
                </div>
                <div class="appt-step-body">
                    <div class="patient-type-row">
                        <div class="patient-type-btn selected" data-type="existing" id="existingPatientOption">
                            <div class="patient-type-icon existing">
                                <i class="fas fa-user-injured"></i>
                            </div>
                            <div class="patient-type-text">
                                <h4>Existing Patient</h4>
                                <p>Select from your records</p>
                            </div>
                        </div>
                        <div class="patient-type-btn" data-type="new" id="newPatientOption">
                            <div class="patient-type-icon new">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="patient-type-text">
                                <h4>New Patient</h4>
                                <p>Create new record</p>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Patient Search -->
                    <div id="existingPatientFields">
                        <div class="appt-search-wrap">
                            <i class="fas fa-search appt-search-icon"></i>
                            <input type="text" id="patientSearch" class="appt-search-input"
                                   placeholder="Search by name, email, or phone..." autocomplete="off">
                            <div class="appt-search-results" id="patientSearchResults"></div>
                        </div>
                        <div class="appt-selected-patient" id="selectedPatientCard">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <div class="name" id="selectedPatientName"></div>
                                <div class="email" id="selectedPatientEmail"></div>
                            </div>
                        </div>
                    </div>

                    <!-- New Patient Form -->
                    <div class="appt-new-patient-form" id="newPatientFields">
                        <div class="appt-form-grid">
                            <div class="appt-form-group">
                                <label for="patient_name">Full Name *</label>
                                <input type="text" name="patient_name" id="patient_name"
                                       placeholder="Patient's full name" value="{{ old('patient_name') }}">
                            </div>
                            <div class="appt-form-group">
                                <label for="patient_email">Email Address *</label>
                                <input type="email" name="patient_email" id="patient_email"
                                       placeholder="Email address" value="{{ old('patient_email') }}">
                            </div>
                            <div class="appt-form-group">
                                <label for="patient_phone">Phone Number *</label>
                                <input type="tel" name="patient_phone" id="patient_phone"
                                       placeholder="Phone number" value="{{ old('patient_phone') }}">
                            </div>
                            <div class="appt-form-group">
                                <label for="patient_date_of_birth">Date of Birth *</label>
                                <input type="date" name="patient_date_of_birth" id="patient_date_of_birth"
                                       max="{{ date('Y-m-d') }}" value="{{ old('patient_date_of_birth') }}">
                            </div>
                            <div class="appt-form-group">
                                <label for="patient_gender">Gender *</label>
                                <select name="patient_gender" id="patient_gender">
                                    <option value="">Select gender</option>
                                    <option value="male" {{ old('patient_gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('patient_gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('patient_gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="appt-info-note">
                            <i class="fas fa-info-circle"></i>
                            <p>A secure password will be auto-generated and sent to the patient via email.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Date & Time -->
            <div class="appt-step">
                <div class="appt-step-header">
                    <div class="appt-step-num">2</div>
                    <h3 class="appt-step-title">Select Date & Time</h3>
                </div>
                <div class="appt-step-body">
                    @forelse($availableSlots as $date => $slots)
                        <div class="appt-date-btn" data-date="{{ $date }}">
                            <div class="day-name">{{ \Carbon\Carbon::parse($date)->format('D') }}</div>
                            <div class="day-num">{{ \Carbon\Carbon::parse($date)->format('j') }}</div>
                            <div class="month">{{ \Carbon\Carbon::parse($date)->format('M Y') }}</div>
                            <div class="slots"><span>{{ $slots->count() }}</span> slots</div>
                        </div>
                    @empty
                        <div class="appt-no-slots">
                            <i class="fas fa-calendar-times"></i>
                            <h4>No Available Slots</h4>
                            <p>Please check your availability settings to add time slots.</p>
                        </div>
                    @endforelse

                    <div class="appt-time-grid" id="timeSlots" style="display: none;"></div>
                </div>
            </div>

            <!-- Step 3: Appointment Type -->
            <div class="appt-step">
                <div class="appt-step-header">
                    <div class="appt-step-num">3</div>
                    <h3 class="appt-step-title">Appointment Type</h3>
                </div>
                <div class="appt-step-body">
                    <div class="appt-type-grid">
                        @php
                            $appointmentTypes = [
                                'in_person' => ['icon' => 'fas fa-hospital', 'class' => 'in-person', 'title' => 'In-Person', 'desc' => 'Clinic visit'],
                                'video_call' => ['icon' => 'fas fa-video', 'class' => 'video', 'title' => 'Video Call', 'desc' => 'Online consultation'],
                                'phone_call' => ['icon' => 'fas fa-phone', 'class' => 'phone', 'title' => 'Phone Call', 'desc' => 'Voice consultation']
                            ];
                            $enabledTypes = $doctor->getEnabledAppointmentTypes();
                            $firstType = reset($enabledTypes);
                        @endphp

                        @foreach($enabledTypes as $type)
                            <div class="appt-type-btn {{ $type === $firstType ? 'selected' : '' }}" data-type="{{ $type }}">
                                <i class="{{ $appointmentTypes[$type]['icon'] }} {{ $appointmentTypes[$type]['class'] }}"></i>
                                <h4>{{ $appointmentTypes[$type]['title'] }}</h4>
                                <p>{{ $appointmentTypes[$type]['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Step 4: Reason -->
            <div class="appt-step">
                <div class="appt-step-header">
                    <div class="appt-step-num">4</div>
                    <h3 class="appt-step-title">Reason for Visit</h3>
                </div>
                <div class="appt-step-body">
                    <textarea name="reason" id="reason" class="appt-reason"
                              placeholder="Please describe the reason for the appointment...">{{ old('reason') }}</textarea>
                </div>
            </div>

        </div>

        <!-- Summary -->
        <div class="appt-summary">
            <div class="appt-summary-header">
                <h3><i class="fas fa-clipboard-list me-2"></i>Appointment Summary</h3>
            </div>
            <div class="appt-summary-body">
                <div class="appt-summary-row">
                    <span class="label">Patient</span>
                    <span class="value" id="summaryPatient">Not selected</span>
                </div>
                <div class="appt-summary-row">
                    <span class="label">Date</span>
                    <span class="value" id="summaryDate">Not selected</span>
                </div>
                <div class="appt-summary-row">
                    <span class="label">Time</span>
                    <span class="value" id="summaryTime">Not selected</span>
                </div>
                <div class="appt-summary-row">
                    <span class="label">Type</span>
                    <span class="value" id="summaryType">{{ $appointmentTypes[$firstType ?? 'in_person']['title'] ?? 'In-Person' }}</span>
                </div>
                <div class="appt-summary-row">
                    <span class="label">Duration</span>
                    <span class="value">{{ $doctor->appointment_duration }} min</span>
                </div>
                <div class="appt-summary-row total">
                    <span class="label">Total</span>
                    <span class="value">${{ number_format($doctor->consultation_fee / 100, 2) }}</span>
                </div>
            </div>
        </div>

        <button type="submit" class="appt-submit-btn" id="bookBtn" disabled>
            <i class="fas fa-calendar-plus"></i>
            Book Appointment
        </button>
    </form>

</div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const availableSlots = @json($availableSlots);

    // State
    let selectedPatient = null;
    let selectedDate = null;
    let selectedTime = null;
    let selectedType = '{{ $firstType ?? 'in_person' }}';

    // Elements
    const patientTypeInput = document.getElementById('patientType');
    const existingPatientFields = document.getElementById('existingPatientFields');
    const newPatientFields = document.getElementById('newPatientFields');
    const existingPatientOption = document.getElementById('existingPatientOption');
    const newPatientOption = document.getElementById('newPatientOption');
    const patientSearch = document.getElementById('patientSearch');
    const patientSearchResults = document.getElementById('patientSearchResults');
    const selectedPatientCard = document.getElementById('selectedPatientCard');
    const selectedPatientName = document.getElementById('selectedPatientName');
    const selectedPatientEmail = document.getElementById('selectedPatientEmail');
    const existingPatientId = document.getElementById('existingPatientId');
    const timeSlots = document.getElementById('timeSlots');
    const typeOptions = document.querySelectorAll('.appt-type-btn');
    const dateOptions = document.querySelectorAll('.appt-date-btn');
    const bookBtn = document.getElementById('bookBtn');

    // Summary elements
    const summaryPatient = document.getElementById('summaryPatient');
    const summaryDate = document.getElementById('summaryDate');
    const summaryTime = document.getElementById('summaryTime');
    const summaryType = document.getElementById('summaryType');

    // Patient Type Selection
    existingPatientOption.addEventListener('click', function() {
        patientTypeInput.value = 'existing';
        existingPatientOption.classList.add('selected');
        newPatientOption.classList.remove('selected');
        existingPatientFields.style.display = 'block';
        newPatientFields.classList.remove('show');
        updateBookButton();
    });

    newPatientOption.addEventListener('click', function() {
        patientTypeInput.value = 'new';
        newPatientOption.classList.add('selected');
        existingPatientOption.classList.remove('selected');
        existingPatientFields.style.display = 'none';
        newPatientFields.classList.add('show');
        selectedPatient = { name: 'New Patient', email: 'Will be created' };
        updateSummaryPatient();
        updateBookButton();
    });

    // Set default
    existingPatientOption.click();

    // Patient Search
    let searchTimeout;
    patientSearch.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 2) {
            patientSearchResults.classList.remove('show');
            return;
        }
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => searchPatients(query), 300);
    });

    function searchPatients(query) {
        fetch(`{{ route('doctor.patients.search') }}?query=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            credentials: 'same-origin'
        })
        .then(res => res.json())
        .then(data => displaySearchResults(data))
        .catch(() => displaySearchResults([]));
    }

    function displaySearchResults(patients) {
        patientSearchResults.innerHTML = '';
        if (patients.length === 0) {
            const div = document.createElement('div');
            div.className = 'appt-result-item';
            const nameSpan = document.createElement('span');
            nameSpan.className = 'name';
            nameSpan.textContent = 'No patients found';
            div.appendChild(nameSpan);
            patientSearchResults.appendChild(div);
        } else {
            patients.forEach(function(patient) {
                const div = document.createElement('div');
                div.className = 'appt-result-item';

                const nameSpan = document.createElement('span');
                nameSpan.className = 'name';
                nameSpan.textContent = patient.name;

                const emailSpan = document.createElement('span');
                emailSpan.className = 'email';
                emailSpan.textContent = patient.email;

                div.appendChild(nameSpan);
                div.appendChild(emailSpan);
                div.addEventListener('click', function() { selectPatient(patient); });
                patientSearchResults.appendChild(div);
            });
        }
        patientSearchResults.classList.add('show');
    }

    function selectPatient(patient) {
        selectedPatient = patient;
        patientSearch.value = patient.name;
        existingPatientId.value = patient.id;
        patientSearchResults.classList.remove('show');
        selectedPatientName.textContent = patient.name;
        selectedPatientEmail.textContent = patient.email;
        selectedPatientCard.classList.add('show');
        updateSummaryPatient();
        updateBookButton();
    }

    // Close search on outside click
    document.addEventListener('click', function(e) {
        if (!patientSearch.contains(e.target) && !patientSearchResults.contains(e.target)) {
            patientSearchResults.classList.remove('show');
        }
    });

    // Date Selection
    dateOptions.forEach(function(option) {
        option.addEventListener('click', function() {
            dateOptions.forEach(function(o) { o.classList.remove('selected'); });
            this.classList.add('selected');
            selectedDate = this.dataset.date;

            const dateObj = new Date(selectedDate);
            summaryDate.textContent = dateObj.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });

            showTimeSlots(selectedDate);
            selectedTime = null;
            summaryTime.textContent = 'Not selected';
            updateBookButton();
        });
    });

    function showTimeSlots(date) {
        const slots = availableSlots[date] || [];
        timeSlots.innerHTML = '';
        timeSlots.style.display = 'grid';

        if (slots.length === 0) {
            const p = document.createElement('p');
            p.className = 'col-12 text-center';
            p.style.cssText = 'color: rgba(232, 237, 231, 0.5); padding: 1rem;';
            p.textContent = 'No available slots';
            timeSlots.appendChild(p);
            return;
        }

        slots.forEach(function(slot) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'appt-time-btn';
            btn.textContent = formatTime(slot.start_time);
            btn.dataset.datetime = date + ' ' + slot.start_time;
            btn.dataset.time = slot.start_time;
            btn.addEventListener('click', function() {
                document.querySelectorAll('.appt-time-btn').forEach(function(b) { b.classList.remove('selected'); });
                this.classList.add('selected');
                selectedTime = this.dataset.time;
                document.getElementById('selectedDateTime').value = this.dataset.datetime;
                summaryTime.textContent = formatTime(selectedTime);
                updateBookButton();
            });
            timeSlots.appendChild(btn);
        });
    }

    function formatTime(time) {
        const parts = time.split(':');
        const hours = parseInt(parts[0], 10);
        const minutes = parseInt(parts[1], 10);
        const date = new Date();
        date.setHours(hours, minutes);
        return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
    }

    // Type Selection
    typeOptions.forEach(function(option) {
        option.addEventListener('click', function() {
            typeOptions.forEach(function(o) { o.classList.remove('selected'); });
            this.classList.add('selected');
            selectedType = this.dataset.type;
            document.getElementById('selectedType').value = selectedType;

            const typeNames = {
                'in_person': 'In-Person',
                'video_call': 'Video Call',
                'phone_call': 'Phone Call'
            };
            summaryType.textContent = typeNames[selectedType];
        });
    });

    // Set default type
    const defaultType = document.querySelector('.appt-type-btn.selected');
    if (defaultType) {
        selectedType = defaultType.dataset.type;
    }

    // Summary Updates
    function updateSummaryPatient() {
        if (selectedPatient) {
            summaryPatient.textContent = selectedPatient.name;
        } else {
            summaryPatient.textContent = 'Not selected';
        }
    }

    function updateBookButton() {
        const hasPatient = selectedPatient !== null;
        const hasDateTime = selectedDate && selectedTime;
        bookBtn.disabled = !(hasPatient && hasDateTime);
    }
});
</script>
@endsection
