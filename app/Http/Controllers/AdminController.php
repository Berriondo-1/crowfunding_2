<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Proyecto;
use App\Models\Aportacion;
use App\Models\SolicitudDesembolso;
use App\Models\Pago;
use App\Models\User;
use App\Models\VerificacionSolicitud;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $verifiedUsers = User::where('estado_verificacion', true)->count();
        $roleStats = Role::withCount('users')->orderBy('nombre_rol')->get();

        return view('admin.dashboard', [
            'totalUsers'    => $totalUsers,
            'verifiedUsers' => $verifiedUsers,
            'roleStats'     => $roleStats,
        ]);
    }

    public function roles(Request $request): View
    {
        $search = $request->query('q');
        $roleFilter = $request->query('role');

        $usersQuery = User::with('roles')->orderBy('name');

        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nombre_completo', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleFilter) {
            $usersQuery->whereHas('roles', function ($q) use ($roleFilter) {
                $q->where('id', $roleFilter);
            });
        }

        $users = $usersQuery->paginate(12)->withQueryString();
        $roles = Role::orderBy('nombre_rol')->get();

        return view('admin.modules.roles', [
            'users' => $users,
            'roles' => $roles,
            'search' => $search,
            'roleFilter' => $roleFilter,
        ]);
    }

    public function showUser(User $user): View
    {
        $user->load(['roles', 'proyectosCreados' => function ($q) {
            $q->orderByDesc('created_at');
        }]);

        $aportaciones = $user->aportaciones()->with('proyecto')->orderByDesc('fecha_aportacion')->get();

        $stats = [
            'total_aportado' => $aportaciones->sum('monto'),
            'aportaciones' => $aportaciones->count(),
            'proyectos_apoyados' => $aportaciones->pluck('proyecto_id')->unique()->count(),
        ];

        $topProyectos = $aportaciones
            ->groupBy('proyecto_id')
            ->map(function ($group) {
                return [
                    'proyecto' => $group->first()->proyecto,
                    'total' => $group->sum('monto'),
                    'aportes' => $group->count(),
                ];
            })
            ->sortByDesc('total')
            ->take(5);

        $calificacion = DB::table('calificaciones')
            ->where('colaborador_id', $user->id)
            ->avg('puntaje');

        return view('admin.modules.usuarios-show', [
            'user' => $user,
            'aportaciones' => $aportaciones,
            'stats' => $stats,
            'topProyectos' => $topProyectos,
            'calificacion' => $calificacion,
        ]);
    }

    public function updateUserRoles(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role_id' => ['nullable', 'exists:roles,id'],
        ]);

        $roleId = $validated['role_id'] ?? null;
        $user->roles()->sync($roleId ? [$roleId] : []);

        return redirect()
            ->route('admin.roles')
            ->with('status', "Rol del usuario {$user->name} actualizado.");
    }

    public function proyectos(Request $request): View
    {
        $search = $request->query('q');
        $proyectosQuery = Proyecto::with('creador')->orderByDesc('created_at');

        if ($search) {
            $proyectosQuery->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('categoria', 'like', "%{$search}%")
                  ->orWhere('ubicacion_geografica', 'like', "%{$search}%");
            });
        }

        $proyectos = $proyectosQuery->paginate(10)->withQueryString();

        return view('admin.modules.proyectos', compact('proyectos', 'search'));
    }

    public function showProyecto(Proyecto $proyecto): View
    {
        $proyecto->load('creador');

        $aporteQuery = Aportacion::where('proyecto_id', $proyecto->id);

        $topInversionistas = (clone $aporteQuery)
            ->select('colaborador_id', DB::raw('SUM(monto) as total'), DB::raw('COUNT(*) as aportes'))
            ->whereNotNull('colaborador_id')
            ->groupBy('colaborador_id')
            ->orderByDesc('total')
            ->with('colaborador')
            ->take(5)
            ->get();

        $aportacionesRecientes = (clone $aporteQuery)
            ->with('colaborador')
            ->orderByDesc('fecha_aportacion')
            ->take(8)
            ->get();

        $stats = [
            'total_recaudado' => (clone $aporteQuery)->sum('monto'),
            'aportaciones' => (clone $aporteQuery)->count(),
            'colaboradores' => (clone $aporteQuery)->distinct('colaborador_id')->count('colaborador_id'),
        ];

        return view('admin.modules.proyectos-show', [
            'proyecto' => $proyecto,
            'topInversionistas' => $topInversionistas,
            'aportacionesRecientes' => $aportacionesRecientes,
            'stats' => $stats,
        ]);
    }

    public function auditorias(): View
    {
        return view('admin.modules.auditorias');
    }

    public function finanzas(): View
    {
        $totRecaudado = Aportacion::sum('monto');
        $solicitudes = SolicitudDesembolso::all();
        $liberado = $solicitudes->whereIn('estado', ['liberado', 'aprobado', 'pagado', 'gastado'])->sum('monto_solicitado');
        $pendiente = $solicitudes->where('estado', 'pendiente')->sum('monto_solicitado');
        $retenido = max($totRecaudado - $liberado, 0);
        $gastado = Pago::sum('monto');
        $disponible = max($totRecaudado - $liberado - $pendiente, 0);

        $stats = [
            'recaudado' => $totRecaudado,
            'retenido' => $retenido,
            'liberado' => $liberado,
            'gastado' => $gastado,
            'pendiente' => $pendiente,
            'disponible' => $disponible,
        ];

        return view('admin.modules.finanzas', compact('stats'));
    }

    public function proveedores(): View
    {
        return view('admin.modules.proveedores');
    }

    public function reportes(): View
    {
        return view('admin.modules.reportes');
    }

    public function finanzasProyectos(): View
    {
        $proyectos = Proyecto::with('creador')->get();

        $recaudado = Aportacion::selectRaw('proyecto_id, SUM(monto) as total')->groupBy('proyecto_id')->pluck('total', 'proyecto_id');
        $solicitudes = SolicitudDesembolso::selectRaw("
            proyecto_id,
            SUM(monto_solicitado) as total,
            SUM(CASE WHEN estado IN ('liberado','aprobado','pagado','gastado') THEN monto_solicitado ELSE 0 END) as liberado,
            SUM(CASE WHEN estado = 'pendiente' THEN monto_solicitado ELSE 0 END) as pendiente
        ")->groupBy('proyecto_id')->get()->keyBy('proyecto_id');

        $filas = $proyectos->map(function ($p) use ($recaudado, $solicitudes) {
            $r = $recaudado[$p->id] ?? 0;
            $s = $solicitudes[$p->id] ?? null;
            $lib = $s->liberado ?? 0;
            $pen = $s->pendiente ?? 0;
            $retenido = max($r - $lib, 0);
            return [
                'proyecto' => $p,
                'recaudado' => $r,
                'retenido' => $retenido,
                'liberado' => $lib,
                'pendiente' => $pen,
            ];
        });

        return view('admin.modules.finanzas-proyectos', compact('filas'));
    }

    public function finanzasSolicitudes(Request $request): View
    {
        $estado = $request->query('estado');
        $q = $request->query('q');

        $query = SolicitudDesembolso::with(['proyecto.creador'])
            ->orderByDesc('created_at');

        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($q) {
            $query->whereHas('proyecto', function ($sub) use ($q) {
                $sub->where('titulo', 'like', "%{$q}%");
            });
        }

        $solicitudes = $query->paginate(12)->withQueryString();
        $totales = [
            'solicitado' => $query->clone()->sum('monto_solicitado'),
            'aprobado' => SolicitudDesembolso::whereIn('estado', ['aprobado','liberado','pagado','gastado'])->sum('monto_solicitado'),
        ];

        return view('admin.modules.finanzas-solicitudes', compact('solicitudes', 'estado', 'q', 'totales'));
    }

    public function updateSolicitudFondos(Request $request, SolicitudDesembolso $solicitud): RedirectResponse
    {
        $validated = $request->validate([
            'accion' => ['required', 'in:liberar,pausar,reintentar'],
            'justificacion_admin' => ['nullable', 'string'],
        ]);

        $estado = match ($validated['accion']) {
            'liberar' => 'liberado',
            'pausar' => 'pausado',
            'reintentar' => 'pendiente',
        };

        $solicitud->estado = $estado;
        $solicitud->estado_admin = $validated['accion'];
        $solicitud->justificacion_admin = $validated['justificacion_admin'] ?? null;
        $solicitud->save();

        return redirect()->back()->with('status', 'Solicitud actualizada manualmente.');
    }

    public function verificaciones(Request $request): View
    {
        $estado = $request->query('estado');
        $query = VerificacionSolicitud::with('user')->latest();
        if ($estado) {
            $query->where('estado', $estado);
        }
        $solicitudes = $query->paginate(12)->withQueryString();

        return view('admin.modules.verificaciones', compact('solicitudes', 'estado'));
    }

    public function updateVerificacion(Request $request, VerificacionSolicitud $solicitud): RedirectResponse
    {
        $validated = $request->validate([
            'accion' => ['required', 'in:aprobar,rechazar'],
            'nota' => ['nullable', 'string'],
        ]);

        $solicitud->estado = $validated['accion'] === 'aprobar' ? 'aprobada' : 'rechazada';
        $solicitud->nota = $validated['nota'] ?? null;
        $solicitud->save();

        if ($validated['accion'] === 'aprobar') {
            $solicitud->user->estado_verificacion = true;
            $solicitud->user->save();
        }

        return redirect()->route('admin.verificaciones')->with('status', 'Solicitud actualizada.');
    }

    public function verificacionAdjunto(VerificacionSolicitud $solicitud, string $tipo)
    {
        $allowed = ['documento_frontal', 'documento_reverso', 'selfie'];
        abort_unless(in_array($tipo, $allowed, true), 404);

        $path = $solicitud->adjuntos[$tipo] ?? null;
        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->response($path);
    }
}
