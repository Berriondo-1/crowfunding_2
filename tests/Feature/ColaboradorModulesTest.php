<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ColaboradorModulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_colaborador_can_aportar_a_proyecto(): void
    {
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator, ['monto_recaudado' => 0]);
        $colaborador = $this->createUserWithRole('COLABORADOR');

        $this->actingAs($colaborador)
            ->post(route('colaborador.proyectos.aportar.store', $proyecto), [
                'monto' => 50,
            ])
            ->assertRedirect(route('colaborador.proyectos.show', $proyecto));

        $this->assertDatabaseHas('aportaciones', [
            'proyecto_id' => $proyecto->id,
            'colaborador_id' => $colaborador->id,
            'monto' => 50,
        ]);

        $this->assertEquals(50.0, $proyecto->fresh()->monto_recaudado);
    }

    public function test_colaborador_can_reportar_proyecto_sospechoso(): void
    {
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator);
        $colaborador = $this->createUserWithRole('COLABORADOR');

        $this->actingAs($colaborador)
            ->post(route('colaborador.reportes.store'), [
                'proyecto_id' => $proyecto->id,
                'motivo' => 'Motivo de prueba',
            ])
            ->assertRedirect(route('colaborador.reportes'));

        $this->assertDatabaseHas('reportes_sospechosos', [
            'proyecto_id' => $proyecto->id,
            'colaborador_id' => $colaborador->id,
            'estado' => 'pendiente',
        ]);
    }

    public function test_colaborador_puede_descargar_recibo_pdf(): void
    {
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator);
        $colaborador = $this->createUserWithRole('COLABORADOR');
        $aporte = $this->createAportacion($colaborador, $proyecto, ['monto' => 30]);

        $this->actingAs($colaborador)
            ->get(route('colaborador.aportaciones.recibo', $aporte))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }
}
