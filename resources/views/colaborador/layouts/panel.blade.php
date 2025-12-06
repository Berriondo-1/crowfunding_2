{{-- resources/views/colaborador/layouts/panel.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>@yield('title', 'Panel Colaborador') | CrowdUp</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden bg-zinc-950">
        {{-- Fondo decorativo, igual que en el panel de creador --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(37,99,235,0.25),transparent_60%)]"></div>

        @php
            $backUrl   = trim($__env->yieldContent('back_url', route('colaborador.dashboard')));
            $backLabel = trim($__env->yieldContent('back_label', 'Volver al panel'));
        @endphp

        {{-- Header --}}
        <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <img src="/images/brand/mark.png" alt="CrowdUp" class="h-8 w-8">
                        <span class="text-xl font-extrabold tracking-tight">
                            Crowd<span class="text-indigo-400">Up</span> Colaborador
                        </span>
                    </a>

                    @if ($backUrl)
                        <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                            <span aria-hidden="true">&larr;</span> {{ $backLabel }}
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <span class="hidden text-sm text-zinc-400 sm:inline">
                        {{ auth()->user()->name ?? 'Invitado' }} · COLABORADOR
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="rounded-full bg-zinc-800 px-4 py-2 text-sm font-medium text-zinc-100 hover:bg-zinc-700">
                            Salir
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="mx-auto flex max-w-7xl gap-8 px-4 py-8 sm:px-6 lg:px-8">
            {{-- Sidebar --}}
            <aside class="w-64 shrink-0">
                <nav class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500">
                            Navegación
                        </p>
                        <ul class="mt-3 space-y-1 text-sm">
                            <li>
                                <a href="{{ route('colaborador.dashboard') }}"
                                   class="flex items-center justify-between rounded-lg px-3 py-2
                                          @if($__env->yieldContent('active') === 'dashboard')
                                              bg-zinc-800 text-white
                                          @else
                                              text-zinc-300 hover:bg-zinc-800/60 hover:text-white
                                          @endif">
                                    <span>Inicio</span>
                                    @if($__env->yieldContent('active') === 'dashboard')
                                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                    @endif
                                </a>
                            </li>
                            {{-- aquí luego podemos añadir enlaces a proyectos/aportaciones/reportes --}}
                        </ul>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500">
                            Cuenta
                        </p>
                        <ul class="mt-3 space-y-1 text-sm">
                            <li>
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center justify-between rounded-lg px-3 py-2 text-zinc-300 hover:bg-zinc-800/60 hover:text-white">
                                    <span>Mi perfil</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </aside>

            {{-- CONTENIDO ESPECÍFICO DE CADA VISTA --}}
            <section class="flex-1 space-y-8">
                @yield('content')
            </section>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
