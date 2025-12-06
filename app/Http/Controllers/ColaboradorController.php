<?php

namespace App\Http\Controllers;

use App\Models\Aportacion;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ColaboradorController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        // Obtener IDs de proyectos que el colaborador ha apoyado
        $proyectosAportadosIds = Aportacion::where('colaborador_id', $userId)
            ->pluck('proyecto_id')
            ->unique();

        // Obtener información de esos proyectos
        $proyectosAportados = Proyecto::whereIn('id', $proyectosAportadosIds)->get();

        // Métricas básicas
        $metrics = [
            'totalAportado'   => Aportacion::where('colaborador_id', $userId)->sum('monto'),
            'numProyectos'    => $proyectosAportadosIds->count(),
            'numAportaciones' => Aportacion::where('colaborador_id', $userId)->count(),
        ];

        return view('colaborador.dashboard', compact('metrics', 'proyectosAportados'));
    }
}


