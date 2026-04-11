<?php

namespace App\Http\Requests;

use App\Models\Doctor;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for storing a new appointment.
 * 
 * Handles validation logic for appointment creation,
 * removing validation code from the controller.
 */
class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $doctor = $this->getEffectiveDoctor();
        $enabledTypes = $doctor ? $doctor->getEnabledAppointmentTypes() : ['in_person'];

        $rules = [
            'appointment_date' => 'required|date|after:now',
            'appointment_type' => 'required|in:' . implode(',', $enabledTypes),
            'reason' => 'required|string|max:500',
            'patient_type' => 'required|in:existing,new',
        ];

        if ($this->input('patient_type') === 'existing') {
            $rules['existing_patient_id'] = 'required|exists:users,id';
        } else {
            $rules['patient_name'] = 'required|string|max:255';
            $rules['patient_email'] = 'required|email|unique:users,email';
            $rules['patient_phone'] = 'required|string|max:20';
            $rules['patient_date_of_birth'] = 'required|date|before:today';
            $rules['patient_gender'] = 'required|in:male,female,other';
            $rules['patient_terms'] = 'required|accepted';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'appointment_date.required' => 'Appointment date is required.',
            'appointment_date.after' => 'Appointment date must be in the future.',
            'appointment_type.required' => 'Appointment type is required.',
            'appointment_type.in' => 'Invalid appointment type selected.',
            'reason.required' => 'Reason for appointment is required.',
            'reason.max' => 'Reason cannot exceed 500 characters.',
            'existing_patient_id.exists' => 'Selected patient does not exist.',
            'patient_email.unique' => 'A patient with this email already exists.',
            'patient_terms.accepted' => 'Patient must accept terms and conditions.',
        ];
    }

    /**
     * Get the effective doctor for the current user.
     *
     * @return Doctor|null
     */
    protected function getEffectiveDoctor(): ?Doctor
    {
        $user = auth()->user();
        
        if (!$user) {
            return null;
        }

        // If user is a sub-user, get the parent's doctor profile
        if ($user->isSubUser()) {
            return $user->parentUser?->doctor;
        }

        return $user->doctor;
    }
}
