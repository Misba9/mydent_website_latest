<?php

namespace App\Policies;

use App\Models\Prescription;
use App\Models\User;

class PrescriptionPolicy
{
    public function view(User $user, Prescription $prescription): bool
    {
        return $this->ownsOrAdministers($user, $prescription);
    }

    public function update(User $user, Prescription $prescription): bool
    {
        if ($user->hasRole('clinic_admin')) {
            return true;
        }

        if ($user->hasRole('doctor') && $user->doctor) {
            return (int) $prescription->doctor_id === (int) $user->doctor->id;
        }

        // Patients may view but not update prescriptions.
        return false;
    }

    public function delete(User $user, Prescription $prescription): bool
    {
        return $this->update($user, $prescription);
    }

    private function ownsOrAdministers(User $user, Prescription $prescription): bool
    {
        if ($user->hasRole('clinic_admin')) {
            return true;
        }

        if ($user->hasRole('doctor') && $user->doctor) {
            return (int) $prescription->doctor_id === (int) $user->doctor->id;
        }

        if ($user->hasRole('patient') && $user->patient) {
            return (int) $prescription->patient_id === (int) $user->patient->id;
        }

        return false;
    }
}
