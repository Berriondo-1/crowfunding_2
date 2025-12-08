<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_is_accessible_for_admin_role(): void
    {
        $user = $this->createUserWithRole('ADMIN');

        $this->actingAs($user)->get('/admin')->assertOk();
    }

    public function test_auditor_dashboard_is_accessible_for_auditor_role(): void
    {
        $user = $this->createUserWithRole('AUDITOR');

        $this->actingAs($user)->get('/auditor')->assertOk();
    }

    public function test_creator_dashboard_is_accessible_for_creator_role(): void
    {
        $user = $this->createUserWithRole('CREADOR');

        $this->actingAs($user)->get('/creator')->assertOk();
    }

    public function test_colaborator_dashboard_is_accessible_for_colaborator_role(): void
    {
        $user = $this->createUserWithRole('COLABORADOR');

        $this->actingAs($user)->get('/colaborador')->assertOk();
    }
}
