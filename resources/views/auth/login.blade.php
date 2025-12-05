<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="text-sm text-indigo-200 font-semibold">Bienvenido de nuevo</p>
            <h2 class="text-3xl font-extrabold mt-1">Inicia sesión en CrowdUp</h2>
            <p class="mt-2 text-sm text-zinc-300">Accede a tu panel para crear o apoyar campañas. Tu sesión es segura y cifrada.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="text-sm font-semibold text-zinc-100">Correo electrónico</label>
                <input id="email" class="block w-full rounded-xl border border-white/10 bg-white/5 text-white placeholder:text-zinc-400 focus:border-indigo-400 focus:ring-indigo-400" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="correo@ejemplo.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label for="password" class="text-sm font-semibold text-zinc-100">Contraseña</label>
                    @if (Route::has('password.request'))
                        <a class="text-xs text-indigo-200 hover:text-white" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    @endif
                </div>
                <input id="password" class="block w-full rounded-xl border border-white/10 bg-white/5 text-white placeholder:text-zinc-400 focus:border-indigo-400 focus:ring-indigo-400" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-sm">
                <label for="remember_me" class="inline-flex items-center gap-2 text-zinc-300">
                    <input id="remember_me" type="checkbox" class="rounded border-white/20 bg-white/5 text-indigo-500 focus:ring-indigo-400" name="remember">
                    <span>Mantener sesión iniciada</span>
                </label>
                <a href="{{ route('register') }}" class="text-indigo-200 hover:text-white font-semibold">Crear cuenta</a>
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white font-semibold shadow-lg shadow-indigo-500/25 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/></svg>
                Entrar
            </button>
        </form>
    </div>
</x-guest-layout>
