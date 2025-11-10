<?php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Ejecuta la semilla de roles base del sistema.
     */
    public function run(): void
    {
        $roles = [
            'ADMIN',
            'AUDITOR',
            'CREADOR',
            'COLABORADOR',
        ];

        foreach ($roles as $nombre) {
            Role::firstOrCreate(['nombre_rol' => $nombre]);
        }
    }
}
