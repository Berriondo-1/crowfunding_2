<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ColaboradorFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_colaborador_filtra_aportaciones_por_fecha(): void
    {
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator);
        $colab = $this->createUserWithRole('COLABORADOR');
        $this->createAportacion($colab, $proyecto, [
            'monto' => 20,
            'fecha_aportacion' => now()->subDays(5),
            'id_transaccion_pago' => uniqid('tx-', true),
        ]);
        $this->createAportacion($colab, $proyecto, [
            'monto' => 30,
            'fecha_aportacion' => now(),
            'id_transaccion_pago' => uniqid('tx-', true),
        ]);

        $this->actingAs($colab)
            ->get(route('colaborador.aportaciones', ['desde' => now()->subDay()->toDateString()]))
            ->assertOk()
            ->assertSee('30.00')
            ->assertDontSee('20.00');
    }

    public function test_colaborador_filtra_proveedores_por_calificacion(): void
    {
        $creator = $this->createUserWithRole('CREADOR');
        $proyecto = $this->createProyecto($creator);
        $colab = $this->createUserWithRole('COLABORADOR');

        $prov1 = $this->createProveedor($creator, $proyecto, ['nombre_proveedor' => 'Bueno']);
        $prov2 = $this->createProveedor($creator, $proyecto, ['nombre_proveedor' => 'Malo']);

        \App\Models\ProveedorHistorial::create([
            'proveedor_id' => $prov1->id,
            'concepto' => 'Servicio',
            'monto' => 10,
            'fecha_entrega' => now(),
            'calificacion' => 5,
        ]);

        \App\Models\ProveedorHistorial::create([
            'proveedor_id' => $prov2->id,
            'concepto' => 'Servicio',
            'monto' => 10,
            'fecha_entrega' => now(),
            'calificacion' => 2,
        ]);

        $this->actingAs($colab)
            ->get(route('colaborador.proyectos.proveedores', [$proyecto, 'promedio' => '4']))
            ->assertOk()
            ->assertSee('Bueno')
            ->assertDontSee('Malo');
    }
}
