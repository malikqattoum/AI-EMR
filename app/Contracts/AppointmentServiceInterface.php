<?php

namespace App\Contracts;

/**
 * Interface for appointment-related services.
 * 
 * Provides a standardized contract for appointment operations
 * to ensure consistency and enable service swapping.
 */
interface AppointmentServiceInterface
{
    /**
     * Create a new appointment.
     *
     * @param array $data Appointment data
     * @return array
     */
    public function createAppointment(array $data): array;

    /**
     * Update an existing appointment.
     *
     * @param int $appointmentId The appointment ID
     * @param array $data Updated appointment data
     * @return array
     */
    public function updateAppointment(int $appointmentId, array $data): array;

    /**
     * Cancel an appointment with reason.
     *
     * @param int $appointmentId The appointment ID
     * @param string $reason Cancellation reason
     * @param int $cancelledBy User ID who cancelled
     * @return array
     */
    public function cancelAppointment(int $appointmentId, string $reason, int $cancelledBy): array;

    /**
     * Confirm an appointment.
     *
     * @param int $appointmentId The appointment ID
     * @return array
     */
    public function confirmAppointment(int $appointmentId): array;

    /**
     * Complete an appointment.
     *
     * @param int $appointmentId The appointment ID
     * @param array $data Completion data
     * @return array
     */
    public function completeAppointment(int $appointmentId, array $data): array;

    /**
     * Mark appointment as no-show.
     *
     * @param int $appointmentId The appointment ID
     * @return array
     */
    public function markNoShow(int $appointmentId): array;

    /**
     * Send confirmation email for appointment.
     *
     * @param int $appointmentId The appointment ID
     * @return bool
     */
    public function sendConfirmationEmail(int $appointmentId): bool;

    /**
     * Send cancellation email for appointment.
     *
     * @param int $appointmentId The appointment ID
     * @return bool
     */
    public function sendCancellationEmail(int $appointmentId): bool;

    /**
     * Send completion email for appointment.
     *
     * @param int $appointmentId The appointment ID
     * @return bool
     */
    public function sendCompletionEmail(int $appointmentId): bool;

    /**
     * Check for scheduling conflicts.
     *
     * @param int $doctorId The doctor ID
     * @param string $appointmentDate The appointment date/time
     * @param int $duration Duration in minutes
     * @param int|null $excludeAppointmentId Appointment ID to exclude (for updates)
     * @return bool
     */
    public function hasConflict(
        int $doctorId,
        string $appointmentDate,
        int $duration,
        ?int $excludeAppointmentId = null
    ): bool;

    /**
     * Get available time slots for a doctor.
     *
     * @param int $doctorId The doctor ID
     * @param string $date The date to check
     * @param int $duration Slot duration in minutes
     * @return array
     */
    public function getAvailableSlots(int $doctorId, string $date, int $duration = 30): array;
}
