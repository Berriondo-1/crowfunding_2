@extends('creator.layouts.panel')

@section('title', 'Panel de Creador')
@section('active', 'dashboard')
@section('back_url', '')

@section('content')
    <div class="space-y-10 px-4 sm:px-6 lg:px-8">
        <section id="overview" class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-900/90 to-slate-900/80 px-8 py-10 shadow-2xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.2),_transparent_45%)]"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/70">Panel de creador</p>
                    <h1 class="mt-3 text-3xl font-black text-white">Controla tu campaña con datos claros</h1>
                    <p class="mt-3 text-base text-white/80">Administra campañas, define recompensas y monitorea desembolsos sin salir del panel.</p>
                    <div class="mt-5 flex flex-wrap items-center gap-3">
                        <a href="{{ $heroCta['route'] }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/60 hover:bg-emerald-400">
                            {{ $heroCta['label'] }}
                        </a>
                    </div>
                </div>
                <div class="grid gap-3 text-sm text-white sm:grid-cols-3">
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 backdrop-blur">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                        Proyectos activos: <span class="font-semibold">{{ $metrics['proyectos'] }}</span>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 backdrop-blur">
                        <span class="h-2.5 w-2.5 rounded-full bg-lime-300"></span>
                        Monto recaudado: <span class="font-semibold">${{ number_format($metrics['montoRecaudado'], 0, ',', '.') }}</span>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 backdrop-blur">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-200"></span>
                        Colaboradores: <span class="font-semibold">{{ $metrics['colaboradores'] }}</span>
                    </div>
                </div>
            </div>
        </section>

        <section id="modules">
            <div class="flex flex-col gap-2">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Estado financiero</p>
                <h2 class="text-2xl font-bold text-white">Financiamiento y salud del proyecto</h2>
                <p class="text-sm text-zinc-400">Recaudado vs meta, fondos disponibles y transparencia.</p>
            </div>
            <div class="mt-4 grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-[11px] uppercase text-zinc-400">Recaudado / Meta</p>
                    <p class="text-2xl font-semibold text-emerald-200">${{ number_format($metrics['montoRecaudado'], 0, ',', '.') }} / ${{ number_format($metrics['metaTotal'], 0, ',', '.') }}</p>
                    <div class="mt-3 h-2 rounded-full bg-white/10">
                        <div class="h-2 rounded-full bg-emerald-400" style="width: {{ $metrics['metaTotal'] ? min(100, round(($metrics['montoRecaudado'] / $metrics['metaTotal']) * 100)) : 0 }}%;"></div>
                    </div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-[11px] uppercase text-zinc-400">Fondos registrados</p>
                    <p class="text-2xl font-semibold text-lime-200">${{ number_format($metrics['fondosRetenidos'], 0, ',', '.') }} / ${{ number_format($metrics['fondosLiberados'], 0, ',', '.') }}</p>
                    <p class="text-xs text-zinc-500 mt-2">Disponibles: ${{ number_format($metrics['fondosDisponibles'], 0, ',', '.') }} · Pendientes: ${{ number_format($metrics['pendientesPorJustificar'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-[11px] uppercase text-zinc-400">Gastos validados</p>
                    <p class="text-2xl font-semibold text-emerald-200">${{ number_format($metrics['fondosGastados'], 0, ',', '.') }}</p>
                    <p class="text-xs text-zinc-500 mt-2">Transparencia: {{ $metrics['transparencia'] }}% fondos justificados</p>
                </div>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Gastos y desembolsos</p>
                            <h3 class="text-lg font-semibold text-white">Últimos movimientos</h3>
                        </div>
                        <a href="{{ route('creador.fondos') }}" class="text-xs text-emerald-200 hover:text-white">Ver todos los movimientos</a>
                    </div>
                    <div class="mt-4 space-y-3 text-sm text-zinc-300">
                        @forelse ($movimientos as $mov)
                            <div class="flex items-center justify-between rounded-xl border border-white/5 bg-white/5 px-4 py-3">
                                <div>
                                    <p class="font-semibold text-white">{{ $mov['titulo'] }}</p>
                                    <p class="text-[11px] text-zinc-400">{{ $mov['meta'] }}</p>
                                </div>
                                <p class="font-semibold text-emerald-200">{{ $mov['detalle'] }}</p>
                            </div>
                        @empty
                            <p>Aún no se registran movimientos. Empieza desde el módulo de fondos → <a href="{{ route('creador.fondos') }}" class="text-emerald-300 underline">Agregar solicitud</a>.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Proyecciones</p>
                            <h3 class="text-lg font-semibold text-white">Hitos próximos</h3>
                        </div>
                        <a href="{{ route('creador.avances') }}" class="text-xs text-emerald-200 hover:text-white">Ver avances recientes</a>
                    </div>
                    <div class="mt-4 space-y-3 text-sm text-zinc-300">
                        <p>Define cronograma y presupuesto en el módulo de avances para activar esta vista.</p>
                        <p class="text-xs text-zinc-500">Mantén actualizados los hitos para atraer confianza y transparencia.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[1.4fr,0.9fr]">
            <div class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Acciones pendientes</p>
                        <h3 class="text-lg font-semibold text-white">Siguiente paso rápido</h3>
                    </div>
                    <a href="{{ route('creador.comprobantes') }}" class="text-xs text-emerald-200 hover:text-white">Ir a comprobantes</a>
                </div>
                <ul class="mt-4 space-y-3 text-sm text-zinc-300">
                    @foreach ($accionesPendientes as $accion)
                        <li class="flex items-center justify-between rounded-xl border border-white/5 bg-white/5 px-4 py-3">
                            <span>{{ $accion['label'] }}</span>
                            <span class="text-xs font-semibold text-white">{{ $accion['count'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Confianza</p>
                        <h3 class="text-lg font-semibold text-white">
                            {{ $perfilCompletado ? 'Perfil verificado' : 'Completa tu perfil y verificación' }}
                        </h3>
                    </div>
                    @if (!$perfilCompletado)
                        <span class="text-xs uppercase tracking-[0.3em] text-emerald-300">
                            {{ $perfilCompletadoCount }}/{{ count($perfilSteps) }}
                        </span>
                    @endif
                </div>
                <p class="mt-2 text-sm text-zinc-400">
                    {{ $perfilCompletado ? 'Todos los pasos están completos, sigue comunicando transparencia.' : 'Sube documentos, redes y certificaciones para elevar tu índice de confianza.' }}
                </p>
                @if ($perfilCompletado)
                    <div class="mt-4 rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-100">
                        Perfil verificado ✅ – Mira cómo aumenta tu índice de confianza.
                    </div>
                @else
                    <ul class="mt-4 space-y-2 text-sm">
                        @foreach ($perfilSteps as $label => $done)
                            <li class="flex items-center justify-between rounded-xl border border-white/5 px-4 py-2 bg-white/5">
                                <span>{{ $label }}</span>
                                <span class="text-xs font-semibold {{ $done ? 'text-emerald-300' : 'text-zinc-400' }}">
                                    {{ $done ? 'Completado' : 'Pendiente' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('creador.perfil') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-white hover:border-emerald-400">
                        Abrir perfil completo
                    </a>
                @endif
            </div>
        </section>
    </div>
@endsection
