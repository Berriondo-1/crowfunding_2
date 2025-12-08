<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Proyecto;
use App\Models\SolicitudDesembolso;
use App\Models\VerificacionSolicitud;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminModulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_user_role(): void
    {
        $admin = $this->createUserWithRole('ADMIN');
        $user = $this->createUserWithRole(null);
        $newRole = Role::firstOrCreate(['nombre_rol' => 'CREADOR']);

        $this->actingAs($admin)
            ->patch(route('admin.users.roles', $user), ['role_id' => $newRole->id])
            ->assertRedirect(route('admin.roles'));

        $this->assertTrue($user->fresh()->roles->contains('id', $newRole->id));
    }

    public function test_admin_can_update_solicitud_estado(): void
    {
        $admin = $this->createUserWithRole('ADMIN');
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator, ['monto_recaudado' => 500]);
        $solicitud = $this->createSolicitud($proyecto, ['estado' => 'pendiente']);

        $this->actingAs($admin)
            ->patch(route('admin.finanzas.solicitudes.update', $solicitud), [
                'accion' => 'liberar',
                'justificacion_admin' => 'Ok',
            ])
            ->assertRedirect();

        $this->assertEquals('liberado', $solicitud->fresh()->estado);
    }

    public function test_admin_can_approve_verificacion_and_verify_user(): void
    {
        $admin = $this->createUserWithRole('ADMIN');
        $user = $this->createUserWithRole(null);
        $verificacion = VerificacionSolicitud::create([
            'user_id' => $user->id,
            'estado' => 'pendiente',
            'adjuntos' => [],
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.verificaciones.update', $verificacion), [
                'accion' => 'aprobar',
                'nota' => 'ok',
            ])
            ->assertRedirect(route('admin.verificaciones'));

        $this->assertTrue($user->fresh()->estado_verificacion);
        $this->assertEquals('aprobada', $verificacion->fresh()->estado);
    }

    public function test_admin_can_view_proyectos_list(): void
    {
        $admin = $this->createUserWithRole('ADMIN');
        $creator = $this->createUserWithRole('CREADOR');
        $this->createProyecto($creator);

        $this->actingAs($admin)
            ->get(route('admin.proyectos'))
            ->assertOk();
    }

    public function test_admin_can_filter_roles_list(): void
    {
        $admin = $this->createUserWithRole('ADMIN');
        $creator = $this->createUserWithRole('CREADOR');

        $this->actingAs($admin)
            ->get(route('admin.roles', ['role' => $creator->roles()->first()->id]))
            ->assertOk()
            ->assertSee($creator->name);
    }

    public function test_admin_can_export_fondos_retenidos(): void
    {
        Storage::fake('public');
        $admin = $this->createUserWithRole('ADMIN');
        $creator = $this->createUserWithRole('CREADOR');
        $this->createProyecto($creator, ['monto_recaudado' => 100]);

        $this->actingAs($admin)
            ->get(route('admin.finanzas.export.retenidos'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/vnd.ms-excel');
    }

    public function test_admin_can_view_verificacion_adjunto(): void
    {
        Storage::fake('public');
        $admin = $this->createUserWithRole('ADMIN');
        $user = $this->createUserWithRole(null);
        $path = 'kyc/front.jpg';
        Storage::disk('public')->put($path, 'demo');
        $verificacion = VerificacionSolicitud::create([
            'user_id' => $user->id,
            'estado' => 'pendiente',
            'adjuntos' => ['documento_frontal' => $path],
        ]);

        $this->actingAs($admin)
            ->get(route('admin.verificaciones.adjunto', [$verificacion, 'documento_frontal']))
            ->assertOk();
    }
}
