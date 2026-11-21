<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class SecurityCriticalFixesTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_guest_cannot_access_manual_payment_route(): void
    {
        $this->get(route('manually-payment', ['appointmentId' => 1]))
            ->assertRedirect(route('login'));
    }

    public function test_guest_cannot_change_transaction_status(): void
    {
        $this->put(route('transaction.status'), [
            'id' => 1,
            'acceptPaymentUserId' => 1,
        ])->assertRedirect(route('login'));
    }

    public function test_patient_cannot_change_transaction_status(): void
    {
        $patient = $this->createUserWithRole('patient', User::PATIENT);

        $this->actingAs($patient)
            ->put(route('transaction.status'), [
                'id' => 1,
                'acceptPaymentUserId' => $patient->id,
            ])
            ->assertForbidden();
    }

    public function test_guest_cannot_mark_notification_read(): void
    {
        $this->post('/notification/1/read')->assertRedirect(route('login'));
    }

    public function test_user_cannot_mark_another_users_notification_read(): void
    {
        $owner = $this->createUserWithRole('patient', User::PATIENT);
        $intruder = $this->createUserWithRole('patient', User::PATIENT);

        $notification = Notification::create([
            'title' => 'Test notification',
            'type' => Notification::BOOKED,
            'user_id' => $owner->id,
        ]);

        $this->actingAs($intruder)
            ->post(route('notifications.read', $notification))
            ->assertStatus(403);
    }

    public function test_password_change_does_not_mass_assign_type_or_status(): void
    {
        $user = $this->createUserWithRole('patient', User::PATIENT);
        $originalType = $user->type;
        $originalStatus = $user->status;

        $this->actingAs($user)
            ->put(route('user.changePassword'), [
                'current_password' => 'password',
                'new_password' => 'new-password',
                'confirm_password' => 'new-password',
                'type' => User::ADMIN,
                'status' => 0,
                'email_verified_at' => null,
            ])
            ->assertOk();

        $user->refresh();

        $this->assertSame($originalType, $user->type);
        $this->assertSame($originalStatus, $user->status);
        $this->assertTrue(Hash::check('new-password', $user->password));
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_can_access_record_denies_cross_patient_prescription(): void
    {
        $patientAUser = $this->createUserWithRole('patient', User::PATIENT);
        $patientBUser = $this->createUserWithRole('patient', User::PATIENT);

        $patientA = Patient::create([
            'patient_unique_id' => 'PAT'.strtoupper(uniqid()),
            'user_id' => $patientAUser->id,
        ]);
        $patientB = Patient::create([
            'patient_unique_id' => 'PAT'.strtoupper(uniqid()),
            'user_id' => $patientBUser->id,
        ]);

        $prescription = Prescription::create([
            'patient_id' => $patientA->id,
            'doctor_id' => null,
            'appointment_id' => 1,
            'status' => 1,
        ]);

        $this->actingAs($patientBUser);
        $this->assertFalse(canAccessRecord(Prescription::class, $prescription->id));

        $this->actingAs($patientAUser);
        $this->assertTrue(canAccessRecord(Prescription::class, $prescription->id));
    }

    public function test_ecom_page_does_not_require_sql_import_and_renders(): void
    {
        $response = $this->get(route('ecom'));
        $response->assertOk();
    }

    public function test_get_logged_in_doctor_uses_lowercase_role(): void
    {
        $doctor = $this->createUserWithRole('doctor', User::DOCTOR);
        $this->actingAs($doctor);
        $this->assertTrue(getLoggedinDoctor());
    }

    private function createUserWithRole(string $roleName, int $type): User
    {
        $user = User::create([
            'first_name' => 'Sec',
            'last_name' => ucfirst($roleName),
            'email' => 'sec-'.$roleName.'-'.uniqid('', true).'@example.com',
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
