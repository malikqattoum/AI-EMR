<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * Trait AuthorizesDoctorResources
 * 
 * Provides shared authorization logic for doctor-owned resources.
 * Ensures that only authenticated, active doctors can access their own resources.
 */
trait AuthorizesDoctorResources
{
    /**
     * Authorize that the current user is a doctor and owns the resource.
     * 
     * @param int $resourceDoctorId The doctor_id field on the resource
     * @param string $resourceType Optional resource type for error messages (e.g., 'audio file', 'note')
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function authorizeDoctorOwnership(int $resourceDoctorId, string $resourceType = 'resource'): void
    {
        $user = Auth::user();
        $effectiveDoctorId = null;

        if ($user->parent_user_id) {
            // Sub-user: check parent is an active doctor
            $parentUser = $user->parentUser;
            if (!$parentUser || $parentUser->role !== 'doctor' || !$parentUser->doctor || !$parentUser->doctor->is_active) {
                abort(403, 'Access denied.');
            }
            $effectiveDoctorId = $parentUser->doctor->id;
        } else {
            // Main user: must be an active doctor
            if ($user->role !== 'doctor' || !$user->doctor || !$user->doctor->is_active) {
                abort(403, 'Access denied.');
            }
            $effectiveDoctorId = $user->doctor->id;
        }

        // Verify the resource belongs to this doctor
        if ($resourceDoctorId !== $effectiveDoctorId) {
            abort(403, "Access denied. You do not have permission to access this {$resourceType}.");
        }
    }

    /**
     * Get the effective doctor ID for the current user.
     * 
     * @return int|null
     */
    protected function getEffectiveDoctorId(): ?int
    {
        $user = Auth::user();
        
        if ($user->parent_user_id) {
            $parentUser = $user->parentUser;
            return $parentUser?->doctor?->id;
        }

        return $user->doctor?->id;
    }
}
