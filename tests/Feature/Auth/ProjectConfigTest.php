<?php

use App\Models\ProyectoCategoria;
use App\Models\ProyectoModeloFinanciamiento;
use App\Models\User;

function actingAsAdmin()
{
    $user = User::factory()->create();
    // evitamos middleware de rol/auth para probar solo el controlador
    test()->withoutMiddleware();

    return $user;
}

test('admin dashboard loads with basic stats', function () {
    $admin = actingAsAdmin();

    $response = $this->actingAs($admin)
        ->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewIs('admin.dashboard')
        ->assertViewHas('totalUsers')
        ->assertViewHas('projects')
        ->assertViewHas('finanzas');
});

test('admin can create new project category', function () {
    $admin = actingAsAdmin();

    $response = $this->actingAs($admin)
        ->post(route('admin.proyectos.categorias.store'), [
            'nombre' => 'Salud',
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('status', 'Categoria creada.');

    $this->assertDatabaseHas('proyecto_categorias', [
        'nombre' => 'Salud',
    ]);
});

test('project category name must be unique', function () {
    ProyectoCategoria::create(['nombre' => 'Salud']);

    $admin = actingAsAdmin();

    $response = $this->from(route('admin.proyectos.config'))
        ->actingAs($admin)
        ->post(route('admin.proyectos.categorias.store'), [
            'nombre' => 'Salud',
        ]);

    $response->assertRedirect(route('admin.proyectos.config'));
    $response->assertSessionHasErrors('nombre');
});

test('admin can create new financing model', function () {
    $admin = actingAsAdmin();

    $response = $this->actingAs($admin)
        ->post(route('admin.proyectos.modelos.store'), [
            'nombre' => 'Todo o nada',
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('status', 'Modelo de financiamiento creado.');

    $this->assertDatabaseHas('proyecto_modelos_financiamiento', [
        'nombre' => 'Todo o nada',
    ]);
});
