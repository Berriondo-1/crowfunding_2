<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CrowdUp') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .glass-card {backdrop-filter: blur(14px); background: rgba(255, 255, 255, 0.05);}
    </style>
</head>
<body class="font-sans antialiased bg-zinc-950 text-zinc-100 min-h-screen">
    <div class="absolute inset-0 -z-10">
        <img src="/images/hero/texture.jpg" class="w-full h-full object-cover opacity-60" alt="">
        <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-zinc-950 to-zinc-900"></div>
        <div class="absolute -top-40 -left-32 w-[28rem] h-[28rem] rounded-full blur-3xl opacity-30" style="background: radial-gradient(closest-side, #6366f1, transparent 70%);"></div>
        <div class="absolute -bottom-52 -right-40 w-[30rem] h-[30rem] rounded-full blur-3xl opacity-25" style="background: radial-gradient(closest-side, #22d3ee, transparent 70%);"></div>
    </div>

    <header class="sticky top-0 z-20 bg-zinc-950/70 border-b border-white/5 backdrop-blur">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="/images/brand/mark.png" alt="CrowdUp" class="h-8 w-8" />
                <span class="font-extrabold tracking-tight text-xl">Crowd<span class="text-indigo-400">Up</span></span>
            </a>

            <div class="flex items-center gap-3 text-sm">
                <a href="{{ url('/') }}#como-funciona" class="hidden md:inline-flex px-3 py-2 rounded-lg hover:bg-white/5">Cómo funciona</a>
                <a href="{{ url('/') }}#campanas" class="hidden md:inline-flex px-3 py-2 rounded-lg hover:bg-white/5">Campañas</a>
                <a href="{{ route('login') }}" class="inline-flex px-4 py-2 rounded-lg border border-white/10 hover:bg-white/5">Entrar</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex px-4 py-2 rounded-lg bg-indigo-500 text-white hover:bg-indigo-600 shadow-lg shadow-indigo-500/20">Crear cuenta</a>
                @endif
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-12 lg:py-20 grid lg:grid-cols-2 gap-10 items-center">
        <section class="space-y-6">
            <p class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-indigo-300/80 bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-400/10">Accede en segundos</p>
            <div class="space-y-4">
                <h1 class="text-4xl font-extrabold leading-tight">Impulsa tu próxima campaña con una experiencia impecable</h1>
                <p class="text-lg text-zinc-300">Inicia sesión o crea tu cuenta para gestionar proyectos, seguir el avance de tus donaciones y colaborar con la comunidad.</p>
            </div>
            <dl class="grid grid-cols-2 gap-6 text-sm text-zinc-300">
                <div class="p-4 rounded-xl border border-white/5 bg-white/5">
                    <dt class="text-xs uppercase tracking-wide text-zinc-400">Seguridad</dt>
                    <dd class="mt-1 font-semibold">Verificación y 2FA opcional</dd>
                </div>
                <div class="p-4 rounded-xl border border-white/5 bg-white/5">
                    <dt class="text-xs uppercase tracking-wide text-zinc-400">Soporte</dt>
                    <dd class="mt-1 font-semibold">Equipo dedicado 24/7</dd>
                </div>
                <div class="p-4 rounded-xl border border-white/5 bg-white/5">
                    <dt class="text-xs uppercase tracking-wide text-zinc-400">Transparencia</dt>
                    <dd class="mt-1 font-semibold">Reportes claros de cada aporte</dd>
                </div>
                <div class="p-4 rounded-xl border border-white/5 bg-white/5">
                    <dt class="text-xs uppercase tracking-wide text-zinc-400">Velocidad</dt>
                    <dd class="mt-1 font-semibold">Configura tu perfil en minutos</dd>
                </div>
            </dl>
        </section>

        <section class="w-full">
            <div class="glass-card border border-white/10 rounded-2xl shadow-2xl shadow-indigo-500/10 overflow-hidden">
                <div class="px-6 sm:px-8 py-8 sm:py-10 bg-black/20">
                    {{ $slot }}
                </div>
                <div class="px-6 sm:px-8 py-4 border-t border-white/5 bg-black/30 text-sm text-zinc-300 flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 text-indigo-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Acceso seguro y cifrado
                    </span>
                    <span class="inline-flex items-center gap-2 text-zinc-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2"/></svg>
                        Diseñado para creadores y donantes
                    </span>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
