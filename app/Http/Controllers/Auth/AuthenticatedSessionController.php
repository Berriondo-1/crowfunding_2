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

        // Redirección basada en rol con prioridad
        if ($user->hasRole('ADMIN')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('AUDITOR')) {
            return redirect()->route('auditor.dashboard');
        } elseif ($user->hasRole('CREADOR')) {
            return redirect()->route('creator.dashboard');
        } elseif ($user->hasRole('COLABORADOR')) {
            return redirect()->route('colaborador.dashboard');
        }

        // Si no tiene rol definido, va al dashboard genérico
        return redirect()->route('dashboard');
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
