<?php

namespace Tests\Feature;

use App\Models\ReporteSospechoso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditorModulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_auditor_can_aprobar_desembolso(): void
    {
        $auditor = $this->createUserWithRole('AUDITOR');
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator);
        $solicitud = $this->createSolicitud($proyecto, ['estado' => 'pendiente']);

        $this->actingAs($auditor)
            ->patch(route('auditor.desembolsos.estado', $solicitud), [
                'accion' => 'aprobar',
            ])
            ->assertRedirect(route('auditor.desembolsos.show', $solicitud));

        $this->assertEquals('aprobado', $solicitud->fresh()->estado);
    }

    public function test_auditor_can_aprobar_comprobante(): void
    {
        $auditor = $this->createUserWithRole('AUDITOR');
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator);
        $solicitud = $this->createSolicitud($proyecto, ['estado' => 'aprobado']);
        $proveedor = $this->createProveedor($creator, $proyecto);
        $pago = $this->createPago($solicitud, $proveedor);

        $this->actingAs($auditor)
            ->patch(route('auditor.comprobantes.estado', $pago), [
                'accion' => 'aprobar',
            ])
            ->assertRedirect(route('auditor.comprobantes.show', $pago));

        $this->assertEquals('aprobado', $pago->fresh()->estado_auditoria);
    }

    public function test_auditor_can_responder_reporte_sospechoso(): void
    {
        $auditor = $this->createUserWithRole('AUDITOR');
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator);
        $colaborador = $this->createUserWithRole('COLABORADOR');

        $reporte = ReporteSospechoso::create([
            'colaborador_id' => $colaborador->id,
            'proyecto_id' => $proyecto->id,
            'motivo' => 'Motivo',
            'estado' => 'pendiente',
        ]);

        $this->actingAs($auditor)
            ->patch(route('auditor.reportes.estado', $reporte), [
                'accion' => 'aprobar',
                'respuesta' => 'Se reviso y se aprueba.',
            ])
            ->assertRedirect();

        $this->assertEquals('aprobado', $reporte->fresh()->estado);
    }
}
