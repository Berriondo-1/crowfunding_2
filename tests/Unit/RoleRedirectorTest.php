<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use App\Support\RoleRedirector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleRedirectorTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirects_to_first_supported_dashboard(): void
    {
        $adminRole = Role::firstOrCreate(['nombre_rol' => 'ADMIN']);
        $user = User::factory()->create();
        $user->roles()->sync([$adminRole->id]);

        $redirect = RoleRedirector::redirect($user);

        $this->assertNotNull($redirect);
        $this->assertSame(route('admin.dashboard'), $redirect->headers->get('Location'));
    }

    public function test_returns_null_when_user_has_no_supported_role(): void
    {
        $otherRole = Role::firstOrCreate(['nombre_rol' => 'OTRO']);
        $user = User::factory()->create();
        $user->roles()->sync([$otherRole->id]);

        $redirect = RoleRedirector::redirect($user);

        $this->assertNull($redirect);
    }
}
