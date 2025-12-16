<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Support\RoleRedirector;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password; // <-- IMPORTANTE
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required',
                'confirmed',
                tap(
                    Password::min(8)
                        ->mixedCase()
                        ->letters()
                        ->numbers()
                        ->symbols(),
                    function ($rule) {
                        if (!app()->environment('testing')) {
                            $rule->uncompromised();
                        }
                    }
                ),
            ],
        ], [
            'password.required' => 'Debes ingresar una contrasena.',
            'password.confirmed' => 'Las contrasenas no coinciden.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
            'password.mixed_case' => 'La contrasena debe incluir mayusculas y minusculas.',
            'password.letters' => 'La contrasena debe incluir letras.',
            'password.numbers' => 'La contrasena debe incluir al menos un numero.',
            'password.symbols' => 'La contrasena debe incluir al menos un simbolo.',
            'password.uncompromised' => 'Esta contrasena es insegura, usa una diferente.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Rol por defecto: colaborador
        $colabRole = Role::firstOrCreate(['nombre_rol' => 'COLABORADOR']);
        $user->roles()->syncWithoutDetaching([$colabRole->id]);

        event(new Registered($user));

        Auth::login($user);

        // Forzamos a olvidar la URL previa protegida para enviar siempre al panel
        $request->session()->forget('url.intended');

        if ($redirect = RoleRedirector::redirect($user)) {
            return $redirect;
        }

        // Fallback por si no se encuentra un panel segun rol
        return redirect()->route('colaborador.dashboard');
    }
}
