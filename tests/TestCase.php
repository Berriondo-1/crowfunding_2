<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Proyecto;
use App\Models\SolicitudDesembolso;
use App\Models\Proveedor;
use App\Models\Pago;
use App\Models\Aportacion;

abstract class TestCase extends BaseTestCase
{
    protected function createUserWithRole(?string $roleName): User
    {
        $user = User::factory()->create();

        if ($roleName) {
            $role = Role::firstOrCreate(['nombre_rol' => $roleName]);
            $user->roles()->sync([$role->id]);
        }

        return $user;
    }

    protected function createProyecto(User $creador, array $overrides = []): Proyecto
    {
        return Proyecto::create(array_merge([
            'creador_id' => $creador->id,
            'titulo' => 'Proyecto Demo',
            'descripcion_proyecto' => 'Descripcion',
            'meta_financiacion' => 1000,
            'monto_recaudado' => 0,
            'estado' => 'borrador',
            'modelo_financiamiento' => 'donaciones',
            'categoria' => 'Tecnologia',
            'ubicacion_geografica' => 'Ciudad',
        ], $overrides));
    }

    protected function createSolicitud(Proyecto $proyecto, array $overrides = []): SolicitudDesembolso
    {
        return SolicitudDesembolso::create(array_merge([
            'proyecto_id' => $proyecto->id,
            'monto_solicitado' => 100,
            'hito' => 'Hito 1',
            'descripcion' => 'Descripcion',
            'proveedores' => [],
            'estado' => 'pendiente',
        ], $overrides));
    }

    protected function createProveedor(User $creador, Proyecto $proyecto = null, array $overrides = []): Proveedor
    {
        return Proveedor::create(array_merge([
            'creador_id' => $creador->id,
            'proyecto_id' => $proyecto?->id,
            'nombre_proveedor' => 'Proveedor Demo',
            'info_contacto' => 'demo@test.com',
            'especialidad' => 'Servicios',
        ], $overrides));
    }

    protected function createPago(SolicitudDesembolso $solicitud, Proveedor $proveedor, array $overrides = []): Pago
    {
        return Pago::create(array_merge([
            'solicitud_id' => $solicitud->id,
            'proveedor_id' => $proveedor->id,
            'monto' => 50,
            'fecha_pago' => now(),
            'concepto' => 'Pago demo',
            'estado_auditoria' => 'pendiente',
            'adjuntos' => [],
        ], $overrides));
    }

    protected function createAportacion(User $colaborador, Proyecto $proyecto, array $overrides = []): Aportacion
    {
        return Aportacion::create(array_merge([
            'colaborador_id' => $colaborador->id,
            'proyecto_id' => $proyecto->id,
            'monto' => 25,
            'fecha_aportacion' => now(),
            'estado_pago' => 'pagado',
            'id_transaccion_pago' => $overrides['id_transaccion_pago'] ?? uniqid('tx-', true),
        ], $overrides));
    }
}
