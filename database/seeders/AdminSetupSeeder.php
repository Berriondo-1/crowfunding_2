<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AdminSetupSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Crear rol ADMIN si no existe
        $adminRole = Role::firstOrCreate(['nombre_rol' => 'ADMIN']);

        // 2) Crear (o actualizar) usuario admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@app.test'],
            [
                'name' => 'Administrador',
                'nombre_completo' => 'Administrador',
                'password' => Hash::make('secret'), // cámbialo en prod
                'estado_verificacion' => true,
                'indice_confianza' => 100,
            ]
        );

        // 3) Vincular rol ADMIN en pivot `usuario_rol` (user_id, rol_id)
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
