<x-guest-layout>
    <div class="relative min-h-screen bg-zinc-950 text-zinc-100 overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(99,102,241,0.16),transparent_35%),radial-gradient(circle_at_80%_0%,rgba(34,197,94,0.12),transparent_30%),radial-gradient(circle_at_50%_80%,rgba(14,165,233,0.12),transparent_30%)]"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-zinc-900/40 via-zinc-950/70 to-zinc-950"></div>
            <div class="absolute -left-32 top-10 h-72 w-72 rounded-full bg-indigo-600/25 blur-3xl"></div>
            <div class="absolute right-0 bottom-0 h-60 w-60 rounded-full bg-emerald-500/20 blur-3xl"></div>
        </div>

        <header class="absolute top-6 left-6 right-6 flex items-center justify-between text-sm text-zinc-300">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 rounded-full bg-white/5 px-4 py-2 border border-white/10 backdrop-blur hover:border-indigo-400/60 transition">
                <img src="/images/brand/mark.png" class="h-6 w-6" alt="CrowdUp">
                <span class="font-semibold text-white">CrowdUp</span>
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ url('/') }}" class="hidden sm:inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-2 font-semibold text-white hover:border-indigo-400/60 transition">
                    Volver al inicio
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-full border border-indigo-400/40 bg-indigo-600/80 px-4 py-2 font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 transition">
                        Crear cuenta
                    </a>
                @endif
            </div>
        </header>

        <div class="mx-auto flex min-h-screen w-full max-w-6xl items-center px-4 sm:px-6 lg:px-8">
            <div class="grid w-full gap-10 md:grid-cols-[1.1fr,0.9fr] items-center">
                <div class="space-y-6">
                    <p class="inline-flex items-center gap-2 rounded-full bg-white/5 px-3 py-1 text-xs uppercase tracking-[0.3em] text-indigo-100 border border-white/10 backdrop-blur">CrowdUp Access</p>
                    <h1 class="text-3xl sm:text-4xl font-bold leading-tight text-white">Impulsa tus proyectos, inversionistas y proveedores desde un solo panel.</h1>
                    <p class="text-zinc-300 text-base">Gestiona avances, fondos y comunicación con una experiencia limpia y enfocada.</p>
                    <div class="grid grid-cols-3 gap-3 max-w-xl">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-xs text-zinc-400 uppercase tracking-[0.18em]">Proyectos</p>
                            <p class="text-2xl font-semibold text-white">+180</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-xs text-zinc-400 uppercase tracking-[0.18em]">Fondos</p>
                            <p class="text-2xl font-semibold text-white">$2.4M</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-xs text-zinc-400 uppercase tracking-[0.18em]">Proveedores</p>
                            <p class="text-2xl font-semibold text-white">92</p>
                        </div>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-indigo-900/60 via-zinc-900/80 to-zinc-950 p-4 sm:p-5 shadow-2xl shadow-indigo-900/30">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-2xl bg-indigo-600/30 border border-indigo-400/30 flex items-center justify-center text-indigo-100 font-semibold">UP</div>
                            <div>
                                <p class="text-sm text-zinc-300">Panel de inversionistas</p>
                                <p class="text-base font-semibold text-white">Transparencia en cada etapa</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative isolate overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur shadow-2xl shadow-indigo-900/30">
                    <div class="absolute inset-0 bg-gradient-to-b from-white/5 via-indigo-500/5 to-zinc-950/40"></div>
                    <div class="relative p-6 sm:p-8 space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-indigo-200">Bienvenido</p>
                                <h2 class="mt-1 text-2xl font-bold text-white">Inicia sesion</h2>
                                <p class="text-sm text-zinc-400">Entra a tu panel CrowdUp.</p>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-indigo-300 hover:text-white" href="{{ route('password.request') }}">Olvidaste tu contrasena?</a>
                            @endif
                        </div>

                        <x-auth-session-status class="mb-2" :status="session('status')" />
                        @if ($errors->any())
                            <div class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf
                            <div class="space-y-1">
                                <label class="text-sm text-zinc-300" for="email">Correo</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                       class="w-full rounded-xl border border-white/10 bg-zinc-900/70 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm text-zinc-300" for="password">Contrasena</label>
                                <input id="password" name="password" type="password" required autocomplete="current-password"
                                       class="w-full rounded-xl border border-white/10 bg-zinc-900/70 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                            </div>
                            <div class="flex items-center justify-between text-sm text-zinc-300">
                                <label class="inline-flex items-center gap-2">
                                    <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-white/20 bg-zinc-900 text-indigo-500 focus:ring-indigo-400">
                                    Recordarme
                                </label>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="font-semibold text-indigo-300 hover:text-white">Crear cuenta</a>
                                @endif
                            </div>
                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500">
                                Entrar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
