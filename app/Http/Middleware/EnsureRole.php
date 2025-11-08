<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
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
            abort(403); // prohibido
        }

        return $next($request);
    }
}
