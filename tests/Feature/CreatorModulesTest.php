<?php

namespace Tests\Feature;

use App\Models\ActualizacionProyecto;
use App\Models\ProveedorHistorial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatorModulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_creator_can_create_recompensa(): void
    {
        $creator = $this->createUserWithRole('CREADOR');
        $creator->update(['estado_verificacion' => true]);
        $proyecto = $this->createProyecto($creator);

        $this->actingAs($creator)
            ->post(route('creador.recompensas.store'), [
                'proyecto_id' => $proyecto->id,
                'titulo' => 'Nivel 1',
                'descripcion' => 'Desc',
                'monto_minimo_aportacion' => 10,
                'disponibilidad' => 5,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('recompensas', [
            'proyecto_id' => $proyecto->id,
            'titulo' => 'Nivel 1',
        ]);
    }

    public function test_creator_can_add_avance(): void
    {
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator);

        $this->actingAs($creator)
            ->post(route('creador.proyectos.avances', $proyecto), [
                'titulo' => 'Avance 1',
                'contenido' => 'Contenido',
                'es_hito' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('actualizaciones_proyecto', [
            'proyecto_id' => $proyecto->id,
            'titulo' => 'Avance 1',
            'es_hito' => 1,
        ]);
    }

    public function test_creator_can_request_desembolso_when_funds_available(): void
    {
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator, ['monto_recaudado' => 500]);

        $this->actingAs($creator)
            ->post(route('creador.fondos.solicitudes.store', $proyecto), [
                'monto_solicitado' => 100,
                'hito' => 'Hito prueba',
                'descripcion' => 'Desc',
            ])
            ->assertRedirect(route('creador.fondos', ['proyecto' => $proyecto->id]));

        $this->assertDatabaseHas('solicitudes_desembolso', [
            'proyecto_id' => $proyecto->id,
            'hito' => 'Hito prueba',
            'estado' => 'pendiente',
        ]);
    }

    public function test_creator_cannot_request_desembolso_if_insufficient_funds(): void
    {
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator, ['monto_recaudado' => 50]);

        $this->actingAs($creator)
            ->post(route('creador.fondos.solicitudes.store', $proyecto), [
                'monto_solicitado' => 100,
                'hito' => 'Hito insuficiente',
                'descripcion' => 'Desc',
            ])
            ->assertSessionHasErrors('monto_solicitado');
    }

    public function test_creator_can_toggle_recompensa_estado(): void
    {
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator);
        $recompensa = \App\Models\Recompensa::create([
            'proyecto_id' => $proyecto->id,
            'titulo' => 'R1',
            'monto_minimo_aportacion' => 10,
            'descripcion' => 'Desc',
        ]);

        $this->actingAs($creator)
            ->patch(route('creador.recompensas.estado', $recompensa))
            ->assertRedirect();

        $this->assertStringStartsWith('[PAUSADO]', $recompensa->fresh()->descripcion);
    }

    public function test_creator_can_edit_and_delete_avance(): void
    {
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator);
        $avance = ActualizacionProyecto::create([
            'proyecto_id' => $proyecto->id,
            'titulo' => 'Inicial',
            'fecha_publicacion' => now(),
        ]);

        $this->actingAs($creator)
            ->patch(route('creador.proyectos.avances.update', [$proyecto, $avance]), [
                'titulo' => 'Actualizado',
                'contenido' => 'Nuevo',
            ])
            ->assertRedirect();

        $this->assertEquals('Actualizado', $avance->fresh()->titulo);

        $this->actingAs($creator)
            ->delete(route('creador.proyectos.avances.delete', [$proyecto, $avance]))
            ->assertRedirect();

        $this->assertDatabaseMissing('actualizaciones_proyecto', ['id' => $avance->id]);
    }

    public function test_creator_can_update_and_delete_proveedor(): void
    {
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator);
        $proveedor = $this->createProveedor($creator, $proyecto, ['nombre_proveedor' => 'Prov1']);

        $this->actingAs($creator)
            ->patch(route('creador.proveedores.update', $proveedor), [
                'nombre_proveedor' => 'Prov2',
                'proyecto_id' => $proyecto->id,
            ])
            ->assertRedirect(route('creador.proveedores'));

        $this->assertDatabaseHas('proveedores', ['id' => $proveedor->id, 'nombre_proveedor' => 'Prov2']);

        $this->actingAs($creator)
            ->delete(route('creador.proveedores.destroy', $proveedor))
            ->assertRedirect(route('creador.proveedores'));

        $this->assertDatabaseMissing('proveedores', ['id' => $proveedor->id]);
    }

    public function test_creator_can_register_pago_with_comprobante(): void
    {
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator);
        $solicitud = $this->createSolicitud($proyecto, ['estado' => 'aprobado']);
        $proveedor = $this->createProveedor($creator, $proyecto);

        $this->actingAs($creator)
            ->post(route('creador.reportes.pagos.store', $proyecto), [
                'solicitud_id' => $solicitud->id,
                'proveedor_id' => $proveedor->id,
                'monto' => 75,
                'concepto' => 'Pago demo',
                'calificacion' => 4,
            ])
            ->assertRedirect(route('creador.reportes', ['proyecto' => $proyecto->id]));

        $this->assertDatabaseHas('pagos', [
            'solicitud_id' => $solicitud->id,
            'proveedor_id' => $proveedor->id,
            'monto' => 75,
        ]);

        $this->assertDatabaseHas('proveedor_historiales', [
            'proveedor_id' => $proveedor->id,
            'concepto' => 'Pago demo',
        ]);
    }
}
