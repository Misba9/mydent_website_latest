<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class UpgradeRouteAuthorizationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return array<string, array{0: string}>
     */
    public static function upgradeUrls(): array
    {
        return [
            'upgrade database' => ['/upgrade/database'],
            'upgrade to v3' => ['/upgrade-to-v3-0-0'],
            'lang js' => ['/lang-js'],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::shouldReceive('call')->zeroOrMoreTimes()->andReturn(0);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @dataProvider upgradeUrls
     */
    public function test_guest_cannot_access_upgrade_route(string $url): void
    {
        $this->get($url)->assertRedirect(route('login'));
    }

    /**
     * @dataProvider upgradeUrls
     */
    public function test_patient_cannot_access_upgrade_route(string $url): void
    {
        $patient = $this->createUserWithRole('patient', User::PATIENT);

        $this->actingAs($patient)->get($url)->assertForbidden();
    }

    /**
     * @dataProvider upgradeUrls
     */
    public function test_doctor_cannot_access_upgrade_route(string $url): void
    {
        $doctor = $this->createUserWithRole('doctor', User::DOCTOR);

        $this->actingAs($doctor)->get($url)->assertForbidden();
    }

    /**
     * @dataProvider upgradeUrls
     */
    public function test_authorized_admin_can_access_upgrade_route(string $url): void
    {
        $admin = $this->createUserWithRole('clinic_admin', User::ADMIN, true);

        $this->actingAs($admin)->get($url)->assertOk();
    }

    private function createUserWithRole(string $roleName, int $type, bool $withAdminPermission = false): User
    {
        $user = User::create([
            'first_name' => 'Upgrade',
            'last_name' => ucfirst($roleName),
            'email' => 'upgrade-'.$roleName.'-'.uniqid('', true).'@example.com',
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

        if ($withAdminPermission) {
            $permission = Permission::firstOrCreate(
                ['name' => 'manage_admin_dashboard', 'guard_name' => 'web'],
                ['display_name' => 'Manage Admin Dashboard']
            );

            $user->givePermissionTo($permission);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $user->fresh();
    }
}
