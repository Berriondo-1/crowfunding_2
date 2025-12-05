<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();
        $user->loadMissing('roles');

        // Redirección basada en rol con prioridad como valor por defecto
        $defaultRedirect = url('/');

        if ($user->hasRole('ADMIN')) {
            $defaultRedirect = route('admin.dashboard');
        } elseif ($user->hasRole('AUDITOR')) {
            $defaultRedirect = route('auditor.dashboard');
        } elseif ($user->hasRole('CREADOR')) {
            $defaultRedirect = route('creador.dashboard');
        } elseif ($user->hasRole('COLABORADOR')) {
            $defaultRedirect = route('colaborador.dashboard');
        }

        // Si no hay ruta protegida previa, vuelve a la página principal
        return redirect()->intended($defaultRedirect);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
