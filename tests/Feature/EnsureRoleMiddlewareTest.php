<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class EnsureRoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->group(function () {
            Route::get('/middleware-only-admin', fn () => 'ok')->middleware('role:ADMIN');
            Route::get('/middleware-only-creator', fn () => 'ok')->middleware('role:CREADOR');
        });
    }

    public function test_guest_receives_401_on_role_middleware(): void
    {
        $this->get('/middleware-only-admin')->assertStatus(401);
    }

    public function test_user_with_other_role_is_redirected_to_own_dashboard(): void
    {
        $user = $this->createUserWithRole('CREADOR');

        $this->actingAs($user)
            ->get('/middleware-only-admin')
            ->assertRedirect(route('creador.dashboard'));
    }

    public function test_user_without_matching_roles_gets_403(): void
    {
        $user = $this->createUserWithRole(null);

        $this->actingAs($user)
            ->get('/middleware-only-admin')
            ->assertStatus(403);
    }
}
