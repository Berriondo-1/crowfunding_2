<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_redirects_to_admin_when_user_has_admin_role(): void
    {
        $user = $this->createUserWithRole('ADMIN');

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_dashboard_redirects_to_creator_when_user_has_creator_role(): void
    {
        $user = $this->createUserWithRole('CREADOR');

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect(route('creador.dashboard'));
    }
}
