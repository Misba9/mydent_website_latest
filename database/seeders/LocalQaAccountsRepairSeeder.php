<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Idempotent local-only repair for QA demo accounts.
 * Does not wipe data; does not create duplicate roles/permissions/users.
 */
class LocalQaAccountsRepairSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            $this->command?->warn('LocalQaAccountsRepairSeeder refused: not local/testing.');

            return;
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->ensureRoles();
        $this->ensureCorePermissionsAssignedToAdmin();
        $this->ensureUser(
            email: 'admin@mydent.in',
            firstName: 'Super',
            lastName: 'Admin',
            type: User::ADMIN,
            role: 'clinic_admin',
            createDoctor: false,
            createPatient: false,
        );
        $this->ensureUser(
            email: 'doctor@mydent.in',
            firstName: 'Adam',
            lastName: 'Diaz',
            type: User::DOCTOR,
            role: 'doctor',
            createDoctor: true,
            createPatient: false,
        );
        $this->ensureUser(
            email: 'patient@mydent.in',
            firstName: 'Aiko',
            lastName: 'Walsh',
            type: User::PATIENT,
            role: 'patient',
            createDoctor: false,
            createPatient: true,
        );

        // Doctor/patient portal menus expect these permissions.
        $portalPerms = Permission::whereIn('name', [
            'manage_appointments',
            'manage_patient_visits',
            'manage_transactions',
        ])->get();
        foreach (['doctor', 'patient'] as $roleName) {
            $role = Role::whereName($roleName)->first();
            if ($role) {
                $role->givePermissionTo($portalPerms);
            }
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    private function ensureRoles(): void
    {
        foreach ([
            ['name' => 'clinic_admin', 'display_name' => 'Clinic Admin', 'is_default' => true],
            ['name' => 'doctor', 'display_name' => 'Doctor', 'is_default' => true],
            ['name' => 'patient', 'display_name' => 'Patient', 'is_default' => true],
        ] as $role) {
            Role::firstOrCreate(['name' => $role['name'], 'guard_name' => 'web'], $role);
        }
    }

    private function ensureCorePermissionsAssignedToAdmin(): void
    {
        $adminRole = Role::whereName('clinic_admin')->first();
        if (! $adminRole) {
            return;
        }

        $all = Permission::pluck('name');
        if ($all->isNotEmpty()) {
            $adminRole->givePermissionTo($all);
        }
    }

    private function ensureUser(
        string $email,
        string $firstName,
        string $lastName,
        int $type,
        string $role,
        bool $createDoctor,
        bool $createPatient,
    ): void {
        $user = User::whereEmail($email)->first();

        if (! $user) {
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'contact' => '1234567890',
                'gender' => User::MALE,
                'type' => $type,
                'email' => $email,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('123456'),
                'status' => 1,
                'region_code' => '91',
            ]);
            $this->command?->info("Created user {$email}");
        } else {
            // Restore known local demo password only when verification fails.
            if (! Hash::check('123456', $user->password)) {
                $user->password = Hash::make('123456');
            }
            $user->status = 1;
            $user->type = $type;
            if (! $user->email_verified_at) {
                $user->email_verified_at = Carbon::now();
            }
            $user->save();
            $this->command?->info("Updated existing user {$email}");
        }

        if (! $user->hasRole($role)) {
            $user->syncRoles([$role]);
        }

        if ($createDoctor && ! $user->doctor) {
            Doctor::create(['user_id' => $user->id]);
        }

        if ($createPatient && ! $user->patient) {
            Patient::create([
                'user_id' => $user->id,
                'patient_unique_id' => 'UNIQUE'.strtoupper(substr(md5($email), 0, 6)),
            ]);
        }
    }
}
