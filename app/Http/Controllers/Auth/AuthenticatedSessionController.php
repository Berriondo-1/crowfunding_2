<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $previous = url()->previous();
        $current = url()->current();

        if (! $this->isAuthFlowUrl($previous, $current) && ! session()->has('url.intended')) {
            session(['url.intended' => $previous]);
        }

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

    private function isAuthFlowUrl(string $previous, string $current): bool
    {
        $authUrls = collect([
            Route::has('login') ? route('login', absolute: false) : null,
            Route::has('register') ? route('register', absolute: false) : null,
            Route::has('password.request') ? route('password.request', absolute: false) : null,
        ])->filter();

        // Ignore if the previous URL is the current request or an auth-related page
        return $previous === $current || collect($authUrls)->contains(fn ($path) => Str::contains($previous, $path));
    }
}
