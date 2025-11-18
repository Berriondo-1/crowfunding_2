<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Panel de Administración | CrowdUp</title>
    <meta name="description" content="Administra roles, módulos clave y monitorea la operación de CrowdUp.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-32 top-0 h-80 w-80 rounded-full bg-indigo-600/30 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-72 w-72 rounded-full bg-fuchsia-500/20 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="/images/brand/mark.png" alt="CrowdUp" class="h-8 w-8" />
                <span class="text-xl font-extrabold tracking-tight">Crowd<span class="text-indigo-400">Up</span> Admin</span>
            </a>
            <nav class="hidden gap-8 text-sm text-zinc-300 md:flex">
                <a href="#overview" class="hover:text-white">Overview</a>
                <a href="#modules" class="hover:text-white">Módulos</a>
                <a href="#roadmap" class="hover:text-white">Roadmap</a>
            </nav>
            <div class="flex items-center gap-3">
                <div class="text-right text-xs leading-tight">
                    <p class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</p>
                    <p class="text-zinc-400 uppercase tracking-wide">ADMIN</p>
                </div>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="inline-flex items-center rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
                    Salir
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <section id="overview" class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-10 shadow-2xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.25),_transparent_45%)]"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/70">Panel estratégico</p>
                    <h1 class="mt-3 text-4xl font-black text-white">Administración centralizada</h1>
                    <p class="mt-4 max-w-2xl text-lg text-white/80">
                        Accede a módulos especializados: roles, proyectos, auditorías, finanzas, proveedores y reportes. Diseñado para control granular y escalabilidad.
                    </p>
                </div>
                <div class="grid gap-4 rounded-2xl bg-white/10 p-6 text-white backdrop-blur">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-white/70">Usuarios totales</p>
                        <p class="text-3xl font-bold">{{ $totalUsers }}</p>
                    </div>
                    <div class="border-t border-white/10 pt-4">
                        <p class="text-xs uppercase tracking-[0.3em] text-white/70">Identidad verificada</p>
                        <p class="text-3xl font-bold">{{ $verifiedUsers }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-10 grid gap-6 lg:grid-cols-3">
            @foreach ($roleStats as $roleStat)
                <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-6 shadow-xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-zinc-400">{{ $roleStat->nombre_rol }}</p>
                    <p class="mt-3 text-4xl font-black text-white">{{ $roleStat->users_count }}</p>
                    <p class="mt-2 text-sm text-zinc-400">Usuarios activos con este rol</p>
                </article>
            @endforeach
        </section>

        <section id="modules" class="mt-12">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Módulos operativos</p>
                    <h2 class="mt-2 text-2xl font-bold text-white">Gestiona cada área sin saturar el panel</h2>
                    <p class="mt-2 text-sm text-zinc-400">Accesos rápidos a vistas dedicadas para cada rol y operación crítica.</p>
                </div>
            </div>
            <div class="mt-6 grid gap-6 md:grid-cols-3">
                <a href="{{ route('admin.roles') }}" class="group rounded-2xl border border-white/10 bg-zinc-900/70 p-6 shadow-lg hover:border-indigo-400/60">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Roles &amp; usuarios</h3>
                        <span class="rounded-full bg-indigo-600/20 px-3 py-1 text-xs font-semibold text-indigo-200">Acceso</span>
                    </div>
                    <p class="mt-3 text-sm text-zinc-400">Asigna, revoca y audita permisos para todos los perfiles.</p>
                    <div class="mt-4 flex items-center gap-2 text-indigo-300 text-sm font-semibold">
                        Ir al módulo
                        <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span>
                    </div>
                </a>

                <a href="{{ route('admin.proyectos') }}" class="group rounded-2xl border border-white/10 bg-zinc-900/70 p-6 shadow-lg hover:border-indigo-400/60">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Proyectos</h3>
                        <span class="rounded-full bg-indigo-600/20 px-3 py-1 text-xs font-semibold text-indigo-200">Control</span>
                    </div>
                    <p class="mt-3 text-sm text-zinc-400">Monitoring de campañas, estados, hitos y verificaciones.</p>
                    <div class="mt-4 flex items-center gap-2 text-indigo-300 text-sm font-semibold">
                        Ir al módulo
                        <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span>
                    </div>
                </a>

                <a href="{{ route('admin.auditorias') }}" class="group rounded-2xl border border-white/10 bg-zinc-900/70 p-6 shadow-lg hover:border-indigo-400/60">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Auditoría &amp; cumplimiento</h3>
                        <span class="rounded-full bg-indigo-600/20 px-3 py-1 text-xs font-semibold text-indigo-200">Revisión</span>
                    </div>
                    <p class="mt-3 text-sm text-zinc-400">Revisión de gastos, KYC/AML y trazabilidad de fondos.</p>
                    <div class="mt-4 flex items-center gap-2 text-indigo-300 text-sm font-semibold">
                        Ir al módulo
                        <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span>
                    </div>
                </a>

                <a href="{{ route('admin.finanzas') }}" class="group rounded-2xl border border-white/10 bg-zinc-900/70 p-6 shadow-lg hover:border-indigo-400/60">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Finanzas</h3>
                        <span class="rounded-full bg-indigo-600/20 px-3 py-1 text-xs font-semibold text-indigo-200">Custodia</span>
                    </div>
                    <p class="mt-3 text-sm text-zinc-400">Fondos en escrow, desembolsos y modelos de financiamiento.</p>
                    <div class="mt-4 flex items-center gap-2 text-indigo-300 text-sm font-semibold">
                        Ir al módulo
                        <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span>
                    </div>
                </a>

                <a href="{{ route('admin.proveedores') }}" class="group rounded-2xl border border-white/10 bg-zinc-900/70 p-6 shadow-lg hover:border-indigo-400/60">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Proveedores</h3>
                        <span class="rounded-full bg-indigo-600/20 px-3 py-1 text-xs font-semibold text-indigo-200">Red</span>
                    </div>
                    <p class="mt-3 text-sm text-zinc-400">Directorio, contratos y performance de proveedores.</p>
                    <div class="mt-4 flex items-center gap-2 text-indigo-300 text-sm font-semibold">
                        Ir al módulo
                        <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span>
                    </div>
                </a>

                <a href="{{ route('admin.reportes') }}" class="group rounded-2xl border border-white/10 bg-zinc-900/70 p-6 shadow-lg hover:border-indigo-400/60">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Reportes &amp; analytics</h3>
                        <span class="rounded-full bg-indigo-600/20 px-3 py-1 text-xs font-semibold text-indigo-200">Insights</span>
                    </div>
                    <p class="mt-3 text-sm text-zinc-400">KPIs operativos, trazabilidad y métricas de éxito.</p>
                    <div class="mt-4 flex items-center gap-2 text-indigo-300 text-sm font-semibold">
                        Ir al módulo
                        <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span>
                    </div>
                </a>
            </div>
        </section>

        <section id="roadmap" class="mt-12">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Hoja de ruta</p>
                    <h3 class="mt-2 text-2xl font-bold text-white">Próximas capacidades para administración</h3>
                </div>
            </div>
            <div class="mt-6 grid gap-6 md:grid-cols-3">
                <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-indigo-300">Prioridad 1</p>
                    <h4 class="mt-2 text-lg font-semibold text-white">Moderación de usuarios</h4>
                    <p class="mt-3 text-sm text-zinc-400">
                        KYC mejorado, seguimiento de verificaciones (HU1, HU3) y control granular de permisos (HU2).
                    </p>
                </article>
                <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-fuchsia-300">Prioridad 2</p>
                    <h4 class="mt-2 text-lg font-semibold text-white">Supervisión financiera</h4>
                    <p class="mt-3 text-sm text-zinc-400">
                        Métricas de proyectos, auditorías y liberación de fondos (Épicas 4, 5 y 8).
                    </p>
                </article>
                <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-300">Prioridad 3</p>
                    <h4 class="mt-2 text-lg font-semibold text-white">Cumplimiento &amp; reportes</h4>
                    <p class="mt-3 text-sm text-zinc-400">
                        Herramientas de AML/KYC, reportes fiscales y monitoreo de proveedores (Épicas 9 y 10).
                    </p>
                </article>
            </div>
        </section>
    </main>
</body>
</html>
