<?php

namespace App\Http\Controllers;

use App\Models\Aportacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ColaboradorController extends Controller
{
    /**
     * Dashboard principal del colaborador
     */
    public function index(): View
    {
        $colaboradorId = Auth::id();

        // Cargamos las aportaciones del colaborador con sus proyectos
        $aportaciones = Aportacion::with('proyecto')
            ->where('colaborador_id', $colaboradorId)
            ->get();

        // Métricas
        $metrics = [
            'totalAportado'      => $aportaciones->sum('monto'),
            'numProyectos'       => $aportaciones->groupBy('proyecto_id')->count(),
            'numAportaciones'    => $aportaciones->count(),
        ];

        // Proyectos únicos que ha apoyado
        $proyectosAportados = $aportaciones
            ->pluck('proyecto')
            ->filter()           // por si alguno viene null
            ->unique('id')
            ->values();

        return view('colaborador.dashboard', compact('metrics', 'proyectosAportados'));
    }

    /**
     * Lista de proyectos apoyados
     */
    public function proyectos(): View
    {
        $colaboradorId = Auth::id();

        $aportaciones = Aportacion::with('proyecto')
            ->where('colaborador_id', $colaboradorId)
            ->get();

        $proyectosAportados = $aportaciones
            ->pluck('proyecto')
            ->filter()
            ->unique('id')
            ->values();

        return view('colaborador.proyectos', compact('proyectosAportados'));
    }

    /**
     * Historial de aportaciones
     */
    public function aportaciones(): View
    {
        $colaboradorId = Auth::id();

        $aportaciones = Aportacion::with('proyecto')
            ->where('colaborador_id', $colaboradorId)
            ->orderByDesc('fecha_aportacion')
            ->get();

        return view('colaborador.aportaciones', compact('aportaciones'));
    }

    /**
     * Reportes / resumen
     */
    public function reportes(): View
    {
        $colaboradorId = Auth::id();

        $aportaciones = Aportacion::with('proyecto')
            ->where('colaborador_id', $colaboradorId)
            ->get();

        $totalAportado  = $aportaciones->sum('monto');
        $numProyectos   = $aportaciones->groupBy('proyecto_id')->count();
        $numAportaciones = $aportaciones->count();

        return view('colaborador.reportes', compact(
            'aportaciones',
            'totalAportado',
            'numProyectos',
            'numAportaciones'
        ));
    }
}
