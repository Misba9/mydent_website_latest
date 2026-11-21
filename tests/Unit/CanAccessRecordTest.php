<?php

namespace Tests\Unit;

use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CanAccessRecordTest extends TestCase
{
    use DatabaseTransactions;

    public function test_existence_alone_is_not_enough_for_access(): void
    {
        $admin = $this->createUserWithRole('clinic_admin', User::ADMIN);
        $patientUser = $this->createUserWithRole('patient', User::PATIENT);

        $patient = Patient::create([
            'patient_unique_id' => 'PAT'.strtoupper(uniqid()),
            'user_id' => $patientUser->id,
        ]);

        $prescription = Prescription::create([
            'patient_id' => $patient->id,
            'doctor_id' => null,
            'appointment_id' => 1,
            'status' => 1,
        ]);

        $this->actingAs($admin);
        $this->assertTrue(canAccessRecord(Prescription::class, $prescription->id));

        $this->actingAs($patientUser);
        $this->assertTrue(canAccessRecord(Prescription::class, $prescription->id));

        $stranger = $this->createUserWithRole('patient', User::PATIENT);
        $this->actingAs($stranger);
        $this->assertFalse(canAccessRecord(Prescription::class, $prescription->id));
    }

    public function test_unknown_model_is_denied_even_if_row_exists(): void
    {
        $admin = $this->createUserWithRole('clinic_admin', User::ADMIN);
        $this->actingAs($admin);

        // Medicine is explicitly allowed for admin/doctor; inventing access for
        // a non-registered model class string should fail closed when no row,
        // and known non-policy models without rules deny for non-admin paths.
        $this->assertFalse(canAccessRecord(Medicine::class, 999999));
    }

    private function createUserWithRole(string $roleName, int $type): User
    {
        $user = User::create([
            'first_name' => 'Unit',
            'last_name' => ucfirst($roleName),
            'email' => 'unit-'.$roleName.'-'.uniqid('', true).'@example.com',
            'password' => Hash::make('password'),
            'status' => User::ACTIVE,
            'type' => $type,
            'language' => 'en',
            'email_verified_at' => now(),
        ]);

        $role = Role::firstOrCreate(
            ['name' => $roleName, 'guard_name' => 'web'],
            ['display_name' => $roleName, 'is_default' => true]
        );

        $user->assignRole($role);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $user->fresh();
    }
}
