<?php

namespace App\Services;

use App\Models\Appointment;
use App\Events\AppointmentStatusUpdated;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Service for handling appointment status transitions.
 * 
 * Validates and manages appointment status changes with
 * proper transition rules and broadcasting.
 */
class AppointmentStatusService extends BaseService
{
    /**
     * Valid status transitions map.
     *
     * @var array
     */
    protected array $validTransitions = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['check_in', 'cancelled', 'no_show'],
        'check_in' => ['in_progress', 'no_show'],
        'in_progress' => ['completed', 'no_show'],
    ];

    /**
     * Update appointment status with validation.
     *
     * @param Appointment $appointment
     * @param string $newStatus
     * @return array
     */
    public function updateStatus(Appointment $appointment, string $newStatus): array
    {
        try {
            // Validate the transition
            if (!$this->isValidTransition($appointment->status, $newStatus)) {
                return $this->errorResult('Invalid status transition', [
                    'from' => $appointment->status,
                    'to' => $newStatus,
                ]);
            }

            // Perform the status update
            $result = $this->performTransition($appointment, $newStatus);

            if (!$result['success']) {
                return $result;
            }

            // Broadcast the status change
            broadcast(new AppointmentStatusUpdated($appointment))->toOthers();

            $this->logInfo('Appointment status updated', [
                'appointment_id' => $appointment->id,
                'old_status' => $appointment->getOriginal('status'),
                'new_status' => $appointment->status,
            ]);

            return $this->successResult('Appointment status updated successfully', [
                'id' => $appointment->id,
                'status' => $appointment->status,
                'updated_at' => $appointment->updated_at->toISOString(),
            ]);
        } catch (Exception $e) {
            $this->logError('Failed to update appointment status', [
                'appointment_id' => $appointment->id,
                'new_status' => $newStatus,
                'error' => $e->getMessage(),
            ], $e);

            return $this->errorResult('Failed to update appointment status');
        }
    }

    /**
     * Check if a status transition is valid.
     *
     * @param string $fromStatus
     * @param string $toStatus
     * @return bool
     */
    public function isValidTransition(string $fromStatus, string $toStatus): bool
    {
        return isset($this->validTransitions[$fromStatus])
            && in_array($toStatus, $this->validTransitions[$fromStatus]);
    }

    /**
     * Perform the actual status transition.
     *
     * @param Appointment $appointment
     * @param string $newStatus
     * @return array
     */
    protected function performTransition(Appointment $appointment, string $newStatus): array
    {
        try {
            return $this->transaction(function () use ($appointment, $newStatus) {
                switch ($newStatus) {
                    case 'in_progress':
                        if ($appointment->status === 'check_in') {
                            $appointment->update(['status' => 'in_progress']);
                        }
                        break;

                    case 'completed':
                        if ($appointment->status === 'in_progress') {
                            $appointment->complete();
                        }
                        break;

                    case 'no_show':
                        if (in_array($appointment->status, ['check_in', 'in_progress', 'confirmed'])) {
                            $appointment->markAsNoShow();
                        }
                        break;

                    default:
                        return $this->errorResult("Unknown status: {$newStatus}");
                }

                return $this->successResult('Transition completed');
            });
        } catch (Exception $e) {
            $this->logError('Failed to perform status transition', [
                'appointment_id' => $appointment->id,
                'new_status' => $newStatus,
                'error' => $e->getMessage(),
            ], $e);

            return $this->errorResult('Failed to perform status transition');
        }
    }

    /**
     * Reorder appointments (for drag and drop).
     *
     * @param int $doctorId
     * @param array $order Array of appointment IDs in new order
     * @return array
     */
    public function reorderAppointments(int $doctorId, array $order): array
    {
        try {
            $this->transaction(function () use ($doctorId, $order) {
                foreach ($order as $index => $appointmentId) {
                    Appointment::where('id', $appointmentId)
                        ->where('doctor_id', $doctorId)
                        ->update(['sort_order' => $index + 1]);
                }
            });

            $this->logInfo('Appointments reordered', [
                'doctor_id' => $doctorId,
                'appointment_count' => count($order),
            ]);

            return $this->successResult('Appointments reordered successfully');
        } catch (Exception $e) {
            $this->logError('Failed to reorder appointments', [
                'doctor_id' => $doctorId,
                'error' => $e->getMessage(),
            ], $e);

            return $this->errorResult('Failed to update appointment order');
        }
    }
}
