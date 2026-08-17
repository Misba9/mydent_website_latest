<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Super Admin
        $admin = User::find(1);
        if ($admin) {
            $admin->update([
                'email' => 'admin@mydent.in',
                'password' => Hash::make('123456'),
                'status' => User::ACTIVE,
                'type' => User::ADMIN,
                'email_verified_at' => $admin->email_verified_at ?? now(),
            ]);
            $adminRole = Role::firstOrCreate(['name' => 'clinic_admin', 'guard_name' => 'web'], ['display_name' => 'Clinic Admin']);
            if (!$admin->hasRole('clinic_admin')) {
                $admin->assignRole($adminRole);
            }
        }

        // 2. Doctor
        $doctor = User::find(2);
        if ($doctor) {
            $doctor->update([
                'email' => 'doctor@mydent.in',
                'password' => Hash::make('123456'),
                'status' => User::ACTIVE,
                'type' => User::DOCTOR,
                'email_verified_at' => $doctor->email_verified_at ?? now(),
            ]);
            $doctorRole = Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web'], ['display_name' => 'Doctor']);
            if (!$doctor->hasRole('doctor')) {
                $doctor->assignRole($doctorRole);
            }
        }

        // 3. Patient
        $patient = User::find(3);
        if ($patient) {
            $patient->update([
                'email' => 'patient@mydent.in',
                'password' => Hash::make('123456'),
                'status' => User::ACTIVE,
                'type' => User::PATIENT,
                'email_verified_at' => $patient->email_verified_at ?? now(),
            ]);
            $patientRole = Role::firstOrCreate(['name' => 'patient', 'guard_name' => 'web'], ['display_name' => 'Patient']);
            if (!$patient->hasRole('patient')) {
                $patient->assignRole($patientRole);
            }
        } else {
            $newPatient = User::create([
                'first_name' => 'Patient',
                'last_name' => 'User',
                'email' => 'patient@mydent.in',
                'password' => Hash::make('123456'),
                'status' => User::ACTIVE,
                'type' => User::PATIENT,
                'gender' => User::MALE,
                'language' => 'en',
                'email_verified_at' => now(),
            ]);
            $patientRole = Role::firstOrCreate(['name' => 'patient', 'guard_name' => 'web'], ['display_name' => 'Patient']);
            $newPatient->assignRole($patientRole);
        }

        // Clean double backslashes in model_has_roles
        if (\Schema::hasTable('model_has_roles')) {
            \DB::table('model_has_roles')->where('model_type', '!=', 'App\Models\User')->update(['model_type' => 'App\Models\User']);
        }
        if (\Schema::hasTable('model_has_permissions')) {
            \DB::table('model_has_permissions')->where('model_type', '!=', 'App\Models\User')->update(['model_type' => 'App\Models\User']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
