<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditorFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_auditor_filtra_desembolsos_por_estado(): void
    {
        $auditor = $this->createUserWithRole('AUDITOR');
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator);
        $this->createSolicitud($proyecto, ['estado' => 'pendiente']);
        $this->createSolicitud($proyecto, ['estado' => 'aprobado']);

        $this->actingAs($auditor)
            ->get(route('auditor.desembolsos', ['estado' => 'aprobado']))
            ->assertOk()
            ->assertSee('aprobado');
    }

    public function test_auditor_filtra_comprobantes_por_proyecto(): void
    {
        $auditor = $this->createUserWithRole('AUDITOR');
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto1 = $this->createProyecto($creator, ['titulo' => 'Proyecto A']);
        $proyecto2 = $this->createProyecto($creator, ['titulo' => 'Proyecto B']);

        $sol1 = $this->createSolicitud($proyecto1, ['estado' => 'aprobado']);
        $sol2 = $this->createSolicitud($proyecto2, ['estado' => 'aprobado']);
        $prov1 = $this->createProveedor($creator, $proyecto1);
        $prov2 = $this->createProveedor($creator, $proyecto2);
        $this->createPago($sol1, $prov1, ['concepto' => 'Pago A']);
        $this->createPago($sol2, $prov2, ['concepto' => 'Pago B']);

        $this->actingAs($auditor)
            ->get(route('auditor.comprobantes', ['q' => 'Pago A']))
            ->assertOk()
            ->assertSee('Pago A')
            ->assertDontSee('Pago B');
    }

    public function test_auditor_puede_pausar_publicacion_proyecto(): void
    {
        $auditor = $this->createUserWithRole('AUDITOR');
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator, ['estado' => 'publicado']);

        $this->actingAs($auditor)
            ->patch(route('auditor.proyectos.publicacion', $proyecto), ['accion' => 'pausar'])
            ->assertRedirect(route('auditor.proyectos.show', $proyecto));

        $this->assertEquals('pausado', $proyecto->fresh()->estado);
    }
}
