<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Review;
use App\Models\DoctorNote;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Traits\HandlesEffectiveDoctor;
use App\Services\AppointmentEmailService;
use App\Services\AppointmentBookingService;
use App\Services\AppointmentStatusService;
use App\Services\DashboardStatsService;
use App\Services\RiskPredictionService;
use App\Services\PredictiveAnalyticsService;

class DashboardController extends Controller
{
    use HandlesEffectiveDoctor;

    public function __construct(
        protected AppointmentEmailService $emailService,
        protected AppointmentBookingService $bookingService,
        protected AppointmentStatusService $statusService,
        protected DashboardStatsService $statsService,
        protected RiskPredictionService $riskService
    ) {
        // Middleware is handled at route level
    }

    /**
     * Display the doctor dashboard
     */
    public function index()
    {
        $doctor = $this->getEffectiveDoctor();

        // Get today's appointments
        $todayAppointments = $doctor->appointments()
            ->with(['patient.patientRiskScores'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_date')
            ->get();

        // Get upcoming appointments (next 7 days)
        $upcomingAppointments = $doctor->appointments()
            ->with(['patient.patientRiskScores'])
            ->whereBetween('appointment_date', [now(), now()->addDays(7)])
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->limit(5)
            ->get();

        // Get pending appointments
        $pendingAppointments = $doctor->appointments()
            ->with(['patient.patientRiskScores'])
            ->where('status', 'pending')
            ->orderBy('appointment_date')
            ->limit(5)
            ->get();

        // Get recent reviews
        $recentReviews = $doctor->reviews()
            ->with(['patient'])
            ->latest()
            ->limit(5)
            ->get();

        // Get recent notes
        $recentNotes = $this->getEffectiveDoctorUser()->doctorNotes()
            ->with(['patient'])
            ->latest()
            ->limit(5)
            ->get();

        // Calculate statistics using service
        $stats = $this->statsService->getDoctorDashboardStats($doctor);

        // Calculate additional stats for "Needs Attention" section
        $completedToday = $doctor->appointments()
            ->where('status', 'completed')
            ->whereDate('completed_at', today())
            ->count();

        $highRiskPatients = $doctor->appointments()
            ->whereDate('appointment_date', today())
            ->whereHas('patient.patientRiskScores', function ($q) {
                $q->whereRaw('GREATEST(no_show_risk, hospitalization_risk) >= 0.7');
            })
            ->distinct()
            ->count('patient_id');

        // Add to stats
        $stats['completed_today'] = $completedToday;
        $stats['high_risk_patients'] = $highRiskPatients;

        // Get counts for "Needs Attention" section
        $unreadMessages = 0; // TODO: Implement when messages system is ready
        $pendingFollowUps = $doctor->appointments()
            ->where('appointment_type', 'follow-up')
            ->where('status', 'scheduled')
            ->where('appointment_date', '>', now())
            ->count();
        $unreviewedLabs = 0; // TODO: Implement when lab system is ready

        return view('doctor.dashboard-improved', compact(
            'doctor',
            'todayAppointments',
            'upcomingAppointments',
            'pendingAppointments',
            'recentReviews',
            'recentNotes',
            'stats',
            'unreadMessages',
            'pendingFollowUps',
            'unreviewedLabs'
        ));
    }

    /**
     * Display appointments calendar
     */
    public function appointments(Request $request)
    {
        $doctor = $this->getEffectiveDoctor();

        $query = $doctor->appointments()->with(['patient.patientRiskScores']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        // Filter by risk category
        if ($request->filled('risk_category')) {
            $query->whereHas('patient.patientRiskScores', function ($q) use ($request) {
                $q->whereColumn('appointment_id', 'appointments.id');

                match ($request->risk_category) {
                    'low' => $q->whereRaw('GREATEST(no_show_risk, hospitalization_risk) < 0.3'),
                    'medium' => $q->whereRaw('GREATEST(no_show_risk, hospitalization_risk) >= 0.3')
                        ->whereRaw('GREATEST(no_show_risk, hospitalization_risk) < 0.7'),
                    'high' => $q->whereRaw('GREATEST(no_show_risk, hospitalization_risk) >= 0.7'),
                    default => null,
                };
            });
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(15);

        return view('doctor.appointments.index', compact('appointments'));
    }

    /**
     * Show appointment details
     */
    public function showAppointment(Appointment $appointment)
    {
        $doctor = $this->getEffectiveDoctor();

        // Check if this appointment belongs to the doctor
        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        // Log doctor access to patient appointment
        if ($appointment->patient_id) {
            \App\Services\AuditLoggingService::logDoctorAccessPatient(
                $this->getEffectiveDoctorUser()->id,
                $appointment->patient_id,
                ['appointment_id' => $appointment->id]
            );
        }

        $appointment->load(['patient', 'review']);

        // Generate risk predictions if they don't exist for this appointment
        $this->riskService->ensurePredictionsExist($appointment);

        // Reload appointment with risk scores
        $appointment->load(['patient.patientRiskScores' => function($query) use ($appointment) {
            $query->where('appointment_id', $appointment->id);
        }]);

        return view('doctor.appointments.show', compact('appointment'));
    }

    /**
     * Confirm an appointment
     */
    public function confirmAppointment(Appointment $appointment)
    {
        $doctor = $this->getEffectiveDoctor();

        if (!$this->validateDoctorExists($doctor)) {
            return redirect()->route('dashboard')
                ->with('error', 'No doctor profile found. Please contact support if you believe this is an error.');
        }

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        if ($appointment->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending appointments can be confirmed.']);
        }

        $appointment->confirm();
        $appointment->load('patient');

        // Send confirmation email using service
        $this->emailService->sendConfirmation($appointment);

        return back()->with('success', 'Appointment confirmed successfully.');
    }

    /**
     * Cancel an appointment
     */
    public function cancelAppointment(Request $request, Appointment $appointment)
    {
        $doctor = $this->getEffectiveDoctor();

        if (!$this->validateDoctorExists($doctor)) {
            return redirect()->route('dashboard')
                ->with('error', 'No doctor profile found. Please contact support if you believe this is an error.');
        }

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return back()->withErrors(['error' => 'This appointment cannot be cancelled.']);
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $appointment->cancel('doctor', $request->cancellation_reason);
        $appointment->load('patient');

        // Send cancellation email using service
        $this->emailService->sendCancellation($appointment, $request->cancellation_reason);

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Complete an appointment
     */
    public function completeAppointment(Request $request, Appointment $appointment)
    {
        $doctor = $this->getEffectiveDoctor();

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        if ($appointment->status !== 'confirmed') {
            return back()->withErrors(['error' => 'Only confirmed appointments can be completed.']);
        }

        $request->validate([
            'doctor_notes' => 'nullable|string|max:2000',
            'follow_up_required' => 'nullable'
        ]);

        $appointment->update([
            'doctor_notes' => $request->doctor_notes,
            'follow_up_required' => $request->boolean('follow_up_required'),
        ]);

        $appointment->complete();
        $appointment->load('patient');

        // Send completion email using service
        $diagnosis = $appointment->diagnosis_id ? \App\Models\Diagnosis::find($appointment->diagnosis_id) : null;
        $this->emailService->sendCompletion($appointment, $diagnosis);

        return back()->with('success', 'Appointment completed successfully.');
    }

    /**
     * Mark appointment as no show
     */
    public function markNoShow(Appointment $appointment)
    {
        $doctor = $this->getEffectiveDoctor();

        // Check if this appointment belongs to the doctor
        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        if ($appointment->status !== 'confirmed') {
            return back()->withErrors(['error' => 'Only confirmed appointments can be marked as no show.']);
        }

        $appointment->markAsNoShow();

        return back()->with('success', 'Appointment marked as no show.');
    }

    /**
     * Toggle auto-approve appointments setting
     */
    public function toggleAutoApprove(Request $request)
    {
        $doctor = $this->getEffectiveDoctor();

        $request->validate([
            'auto_approve' => 'required|boolean'
        ]);

        $doctor->update([
            'auto_approve_appointments' => $request->auto_approve
        ]);

        $status = $request->auto_approve ? 'enabled' : 'disabled';

        return response()->json([
            'success' => true,
            'message' => "Auto-approve appointments {$status} successfully!",
            'auto_approve' => $request->auto_approve
        ]);
    }

    /**
     * Show form to create a new appointment
     */
    public function createAppointment()
    {
        $doctor = $this->getEffectiveDoctor();

        // Check if effective doctor exists
        if (!$doctor) {
            Log::error('No effective doctor found for user during appointment creation', [
                'user_id' => Auth::id(),
                'user_role' => Auth::user()->role,
                'is_sub_user' => Auth::user()->isSubUser(),
                'parent_user_id' => Auth::user()->parent_user_id,
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'No doctor profile found. Please contact support if you believe this is an error.');
        }

        // Log doctor info for debugging
        Log::info('Create appointment - doctor info', [
            'current_user_id' => Auth::id(),
            'current_user_role' => Auth::user()->role,
            'is_sub_user' => Auth::user()->isSubUser(),
            'parent_user_id' => Auth::user()->parent_user_id,
            'effective_doctor_id' => $doctor ? $doctor->id : null,
            'user_doctor_id' => Auth::user()->doctor ? Auth::user()->doctor->id : null,
        ]);

        // Get available slots for next 30 days
        $availableSlots = [];
        for ($i = 0; $i < 30; $i++) {
            $date = now()->addDays($i)->format('Y-m-d');
            $slots = $doctor->getAvailableSlots($date);
            if ($slots->isNotEmpty()) {
                $availableSlots[$date] = $slots;
            }
        }

        // Get doctor's existing patients for selection (unified method - primary_doctor_id OR appointments)
        $patients = $this->getEffectiveDoctorUser()->getDoctorPatients();

        return view('doctor.appointments.create', compact('doctor', 'availableSlots', 'patients'));
    }

    /**
     * Store a newly created appointment
     */
    public function storeAppointment(StoreAppointmentRequest $request)
    {
        $doctor = $this->getEffectiveDoctor();

        if (!$this->validateDoctorExists($doctor)) {
            return redirect()->route('dashboard')
                ->with('error', 'No doctor profile found. Please contact support.');
        }

        // Validate slot availability
        $slotValidation = $this->bookingService->validateSlot($doctor, $request->appointment_date);
        
        if (!$slotValidation['valid']) {
            return back()->withErrors(['appointment_date' => $slotValidation['error']]);
        }

        // Create appointment based on patient type
        if ($request->patient_type === 'existing') {
            $result = $this->bookingService->bookForExistingPatient($doctor, [
                'patient_id' => $request->existing_patient_id,
                'appointment_date' => $request->appointment_date,
                'appointment_type' => $request->appointment_type,
                'reason' => $request->reason,
            ]);
        } else {
            $result = $this->bookingService->bookForNewPatient($doctor, [
                'patient_name' => $request->patient_name,
                'patient_email' => $request->patient_email,
                'patient_phone' => $request->patient_phone,
                'patient_date_of_birth' => $request->patient_date_of_birth,
                'patient_gender' => $request->patient_gender,
                'appointment_date' => $request->appointment_date,
                'appointment_type' => $request->appointment_type,
                'reason' => $request->reason,
            ]);
        }

        if (!$result['success']) {
            return back()->withErrors(['error' => $result['message']])->withInput();
        }

        $appointment = $result['data']['appointment'] ?? $result['data'];

        // Send notifications
        $this->sendAppointmentNotifications($appointment);

        return redirect()->route('doctor.appointments.show', $appointment)
            ->with('success', 'Appointment booked successfully!');
    }

    /**
     * Send appointment notifications (same as patient booking flow)
     */
    private function sendAppointmentNotifications(Appointment $appointment)
    {
        try {
            // Send notification to doctor about new appointment
            if ($appointment->doctor && $appointment->doctor->user) {
                $doctor = $appointment->doctor->user;

                // Check if doctor wants appointment notifications
                if ($doctor->wantsNotification('appointment_booked')) {
                    // Send notification directly, not using queue
                    $notification = new \App\Notifications\AppointmentBookedNotification($appointment);
                    $doctor->notify($notification);

                    // Broadcast event immediately, not using queue
                    event(new \App\Events\AppointmentBookedEvent($appointment));
                }
            }

            // Send notification to patient about appointment confirmation
            if ($appointment->patient && $appointment->status === 'confirmed') {
                $patient = $appointment->patient;

                // Check if patient wants appointment notifications
                if ($patient->wantsNotification('appointment_booked')) {
                    $patient->notifyIfWants(new \App\Notifications\AppointmentBookedNotification($appointment), 'appointment_booked');
                }
            }

            // Send notification to guest about appointment confirmation
            if ($appointment->isGuestAppointment() && $appointment->status === 'confirmed') {
                // For guest appointments, we'll handle notifications differently
                // This could be handled through email notifications
            }

        } catch (\Exception $e) {
            // Log notification errors but don't break the appointment process
            Log::error('Failed to send appointment notifications: ' . $e->getMessage());
        }
    }

    /**
     * Search patients for appointment booking
     */
    public function searchPatients(Request $request)
    {
        try {
            $request->validate([
                'query' => 'required|string|min:2|max:100'
            ]);

            $query = $request->input('query');

            // Get only patients related to the current doctor
            $doctorUser = $this->getEffectiveDoctorUser();
            $patients = $doctorUser->getDoctorPatients()
                ->filter(function($patient) use ($query) {
                    return stripos($patient->name, $query) !== false ||
                           stripos($patient->email, $query) !== false ||
                           ($patient->phone && stripos($patient->phone, $query) !== false);
                })
                ->take(10)
                ->map(function($patient) {
                    return [
                        'id' => $patient->id,
                        'name' => $patient->name,
                        'email' => $patient->email,
                        'phone' => $patient->phone,
                        'text' => "{$patient->name} ({$patient->email})"
                    ];
                })
                ->values()
                ->toArray();

            return response()->json($patients);
        } catch (\Exception $e) {
            Log::error('Patient search error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'query' => $request->all()
            ]);
            return response()->json([
                'error' => 'Search failed',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Display reviews
     */
    public function reviews(Request $request)
    {
        $doctor = $this->getEffectiveDoctor();

        $query = $doctor->reviews()->with(['patient', 'appointment']);

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filter by approval status
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        $reviews = $query->latest()->paginate(15);

        // Calculate positive reviews (ratings 4-5)
        $positiveReviews = $doctor->reviews()->whereIn('rating', [4, 5])->count();

        // Calculate recent reviews (this month)
        $recentReviews = $doctor->reviews()->whereMonth('created_at', now()->month)->count();

        return view('doctor.reviews.index', compact('doctor', 'reviews', 'positiveReviews', 'recentReviews'));
    }

    /**
     * Show doctor profile edit form
     */
    public function profile()
    {
        $doctor = $this->getEffectiveDoctor();
        $doctor->load(['user', 'specialty', 'googleAccount']);

        // Get available specialties for the dropdown
        $specialties = \App\Models\Specialty::orderBy('name')->get();

        return view('doctor.profile.edit', compact('doctor', 'specialties'));
    }

    /**
     * Update doctor profile
     */
    public function updateProfile(Request $request)
    {
        $doctor = $this->getEffectiveDoctor();

        $request->validate([
            'bio' => 'nullable|string|max:2000',
            'phone' => 'nullable|string|max:20',
            'specialty_id' => 'required|exists:specialties,id',
            'consultation_fee' => 'required|numeric|min:0|max:999999',
            'appointment_duration' => 'required|integer|min:15|max:240',
            'languages' => 'nullable|array',
            'languages.*' => 'string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'auto_approve_appointments' => 'boolean',
            'allow_cancellation' => 'boolean',
            'allow_rescheduling' => 'boolean',
            'cancellation_hours' => 'required|integer|min:1|max:168',
            // WhatsApp notification preferences
            'whatsapp_enabled' => 'boolean',
            'whatsapp_appointment_reminders' => 'boolean',
            'whatsapp_urgent_alerts' => 'boolean',
            'whatsapp_diagnosis_updates' => 'boolean',
            'whatsapp_review_requests' => 'boolean',
        ]);

        $data = $request->except(['profile_image']);

        // Convert consultation fee to cents
        $data['consultation_fee'] = $request->consultation_fee * 100;

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($doctor->profile_image) {
                \Storage::disk('public')->delete($doctor->profile_image);
            }

            $data['profile_image'] = $request->file('profile_image')->store('doctor-profiles', 'public');
        }

        $doctor->update($data);

        // Update notification preferences if provided
        if ($request->hasAny([
            'whatsapp_enabled',
            'whatsapp_appointment_reminders',
            'whatsapp_urgent_alerts',
            'whatsapp_diagnosis_updates',
            'whatsapp_review_requests'
        ])) {
            $notificationPreferences = $doctor->user->getOrCreateNotificationPreferences();
            $notificationPreferences->update([
                'whatsapp_enabled' => $request->whatsapp_enabled ?? false,
                'whatsapp_appointment_reminders' => $request->whatsapp_appointment_reminders ?? false,
                'whatsapp_urgent_alerts' => $request->whatsapp_urgent_alerts ?? false,
                'whatsapp_diagnosis_updates' => $request->whatsapp_diagnosis_updates ?? false,
                'whatsapp_review_requests' => $request->whatsapp_review_requests ?? false,
            ]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Get calendar events for appointments (AJAX)
     */
    public function getCalendarEvents(Request $request): JsonResponse
    {
        $doctor = $this->getEffectiveDoctor();
        
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);
        
        $start = $request->start;
        $end = $request->end;

        $appointments = $doctor->appointments()
            ->with(['patient.patientRiskScores'])
            ->whereBetween('appointment_date', [$start, $end])
            ->get();

        $events = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'title' => $appointment->patient_name,
                'start' => $appointment->appointment_date->toISOString(),
                'end' => $appointment->appointment_end->toISOString(),
                'color' => $this->getEventColor($appointment->status),
                'url' => route('doctor.appointments.show', $appointment),
                'extendedProps' => [
                    'status' => $appointment->status,
                    'patient' => $appointment->patient_name,
                    'reason' => $appointment->reason,
                    'type' => $appointment->appointment_type,
                    'phone' => $appointment->patient_phone,
                ]
            ];
        });

        return response()->json($events);
    }


    /**
     * Display the on-deck dashboard for real-time appointment tracking
     */
    public function onDeck(Request $request)
    {
        $doctor = $this->getEffectiveDoctor();

        // Get appointments for on-deck display (today and upcoming)
        $query = $doctor->appointments()
            ->with(['patient.patientRiskScores'])
            ->whereIn('status', ['check_in', 'in_progress', 'confirmed'])
            ->whereDate('appointment_date', '>=', today())
            ->whereDate('appointment_date', '<=', today()->addDays(1)); // Today and tomorrow

        // Filter by status if specified
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Order by appointment time and priority
        $appointments = $query->orderBy('appointment_date')
            ->orderByRaw("CASE
                WHEN status = 'in_progress' THEN 1
                WHEN status = 'check_in' THEN 2
                WHEN status = 'confirmed' THEN 3
                ELSE 4
            END")
            ->get();

        // Add priority based on risk scores and appointment time
        $appointments->transform(function ($appointment) {
            $riskScore = $appointment->patient->patientRiskScores
                ->where('appointment_id', $appointment->id)
                ->first();

            $priority = 'low';
            if ($riskScore) {
                $maxRisk = max($riskScore->no_show_risk, $riskScore->hospitalization_risk);
                if ($maxRisk >= 0.7) {
                    $priority = 'high';
                } elseif ($maxRisk >= 0.3) {
                    $priority = 'medium';
                }
            }

            $appointment->priority = $priority;
            return $appointment;
        });

        // Sort by priority and time
        $appointments = $appointments->sort(function ($a, $b) {
            $priorityOrder = ['high' => 3, 'medium' => 2, 'low' => 1];
            $priorityDiff = $priorityOrder[$b->priority] - $priorityOrder[$a->priority];

            if ($priorityDiff !== 0) {
                return $priorityDiff;
            }

            return $a->appointment_date <=> $b->appointment_date;
        })->values();

        return view('doctor.on-deck', compact('appointments'));
    }

    /**
     * Update appointment status via AJAX
     */
    public function updateAppointmentStatus(Request $request, Appointment $appointment): JsonResponse
    {
        $doctor = $this->getEffectiveDoctor();

        if ($appointment->doctor_id !== $doctor->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:check_in,in_progress,completed,no_show'
        ]);

        $result = $this->statusService->updateStatus($appointment, $request->status);

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        return response()->json($result);
    }

    /**
     * Update appointment order (drag and drop)
     */
    public function reorderAppointments(Request $request): JsonResponse
    {
        $doctor = $this->getEffectiveDoctor();

        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:appointments,id'
        ]);

        $result = $this->statusService->reorderAppointments($doctor->id, $request->order);

        if (!$result['success']) {
            return response()->json($result, 500);
        }

        return response()->json($result);
    }


    /**
     * Show form to create a follow-up appointment
     */
    public function createFollowUp(Appointment $appointment)
    {
        $doctor = $this->getEffectiveDoctor();

        // Check if this appointment belongs to the doctor
        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        // Only allow follow-up creation for completed appointments
        if ($appointment->status !== 'completed') {
            return back()->withErrors(['error' => 'Follow-up appointments can only be created for completed appointments.']);
        }

        return view('doctor.appointments.create-follow-up', compact('appointment'));
    }

    /**
     * Store a new follow-up appointment
     */
    public function storeFollowUp(Request $request, Appointment $appointment)
    {
        $doctor = $this->getEffectiveDoctor();

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        $request->validate([
            'appointment_date' => 'required|date|after:now',
            'appointment_type' => 'required|in:video_call,phone_call,in_person,follow_up',
            'consultation_fee' => 'required|numeric|min:0',
            'reason' => 'required|string|max:1000',
            'duration' => 'nullable|integer|min:15|max:240',
        ]);

        $followUpAppointment = new Appointment();
        $followUpAppointment->doctor_id = $doctor->id;
        $followUpAppointment->patient_id = $appointment->patient_id;
        $followUpAppointment->patient_name = $appointment->patient_name;
        $followUpAppointment->patient_email = $appointment->patient_email;
        $followUpAppointment->patient_phone = $appointment->patient_phone;
        $followUpAppointment->appointment_date = $request->appointment_date;
        $followUpAppointment->appointment_type = $request->appointment_type;
        $followUpAppointment->consultation_fee = $request->consultation_fee * 100;
        $followUpAppointment->appointment_duration = $request->duration ?? 30;
        $followUpAppointment->reason = $request->reason;
        $followUpAppointment->status = 'pending';
        $followUpAppointment->is_follow_up = true;
        $followUpAppointment->original_appointment_id = $appointment->id;
        $followUpAppointment->save();

        $followUpAppointment->load('patient');

        // Send follow-up email using service
        $this->emailService->sendFollowUp($followUpAppointment, $appointment);

        return redirect()->route('doctor.appointments.show', $appointment)
            ->with('success', 'Follow-up appointment created successfully!');
    }

    /**
     * Display SMS provider settings page for doctors
     */
    public function smsSettings()
    {
        $doctor = $this->getEffectiveDoctor();

        // Get current doctor provider setting
        $doctorProvider = $doctor->sms_provider;

        // Get system provider
        $systemProvider = app(\App\Services\SmsService::class)->getSystemProviderPublic();

        // Get hospital information if doctor belongs to a hospital
        $user = $this->getEffectiveDoctorUser();
        $hospital = $user->hospital;

        $hospitalProvider = $hospital ? $hospital->sms_provider : null;
        $hospitalName = $hospital ? $hospital->name : null;

        // Determine effective provider using the same logic as the API
        if ($doctorProvider) {
            $effectiveProvider = [
                'provider' => $doctorProvider,
                'source' => 'doctor',
                'inherited_from' => null
            ];
        } elseif ($hospitalProvider) {
            $effectiveProvider = [
                'provider' => $hospitalProvider,
                'source' => 'hospital',
                'inherited_from' => $hospitalName
            ];
        } else {
            $effectiveProvider = [
                'provider' => $systemProvider,
                'source' => 'system',
                'inherited_from' => null
            ];
        }

        return view('doctor.sms-settings', compact(
            'doctorProvider',
            'systemProvider',
            'hospitalProvider',
            'hospitalName',
            'effectiveProvider'
        ));
    }

    /**
     * Validate that doctor exists and return boolean.
     *
     * @param mixed $doctor
     * @return bool
     */
    protected function validateDoctorExists($doctor): bool
    {
        if (!$doctor) {
            Log::error('No effective doctor found for user', [
                'user_id' => Auth::id(),
                'user_role' => Auth::user()->role ?? 'unknown',
                'is_sub_user' => Auth::user()?->isSubUser() ?? false,
                'parent_user_id' => Auth::user()?->parent_user_id,
            ]);
            
            return false;
        }
        
        return true;
    }

    /**
     * Get event color based on appointment status
     */
    protected function getEventColor(string $status): string
    {
        return match($status) {
            'pending' => '#f59e0b',
            'confirmed' => '#10b981',
            'cancelled' => '#ef4444',
            'completed' => '#3b82f6',
            'no_show' => '#6b7280',
            default => '#6b7280'
        };
    }
}
