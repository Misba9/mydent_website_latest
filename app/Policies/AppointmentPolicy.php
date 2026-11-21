<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function view(User $user, Appointment $appointment): bool
    {
        return $this->ownsOrAdministers($user, $appointment);
    }

    public function update(User $user, Appointment $appointment): bool
    {
        if ($user->hasRole('clinic_admin')) {
            return true;
        }

        if ($user->hasRole('doctor') && $user->doctor) {
            return (int) $appointment->doctor_id === (int) $user->doctor->id;
        }

        if ($user->hasRole('patient') && $user->patient) {
            return (int) $appointment->patient_id === (int) $user->patient->id;
        }

        return false;
    }

    public function delete(User $user, Appointment $appointment): bool
    {
        return $this->update($user, $appointment);
    }

    private function ownsOrAdministers(User $user, Appointment $appointment): bool
    {
        if ($user->hasRole('clinic_admin')) {
            return true;
        }

        if ($user->hasRole('doctor') && $user->doctor) {
            return (int) $appointment->doctor_id === (int) $user->doctor->id;
        }

        if ($user->hasRole('patient') && $user->patient) {
            return (int) $appointment->patient_id === (int) $user->patient->id;
        }

        return false;
    }
}
