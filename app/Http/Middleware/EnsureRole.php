<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class EnsureRole
{
    /**
     * Mapa de roles a sus dashboards principales.
     */
    private array $roleRedirects = [
        'ADMIN'       => 'admin.dashboard',
        'AUDITOR'     => 'auditor.dashboard',
        'CREADOR'     => 'creador.dashboard',
        'COLABORADOR' => 'colaborador.dashboard',
    ];

    /**
     * Uso en rutas: ->middleware('role:ADMIN') o ->middleware('role:ADMIN,AUDITOR')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401); // no autenticado
        }

        // Asegúrate de tener $user->roles() definido y el helper hasRole
        $user->loadMissing('roles');

        $tieneRol = collect($roles)->some(function ($rol) use ($user) {
            return $user->roles->contains(fn($r) => strcasecmp($r->nombre_rol, $rol) === 0);
        });

        if (!$tieneRol) {
            // Si no tiene permiso aquí pero sí otro rol, lo redirigimos a su panel correspondiente
            if ($redirect = $this->redirectToRoleHome($user)) {
                return $redirect;
            }

            abort(403); // prohibido y sin panel asociado
        }

        return $next($request);
    }

    /**
     * Redirige al primer panel que coincida con alguno de los roles del usuario.
     */
    private function redirectToRoleHome(User $user): ?Response
    {
        $userRoles = $user->roles->pluck('nombre_rol')->map(fn ($rol) => strtoupper($rol));

        foreach ($userRoles as $rol) {
            if (isset($this->roleRedirects[$rol])) {
                return redirect()->route($this->roleRedirects[$rol]);
            }
        }

        return null;
    }
}
