@extends('auditor.layouts.panel')

@section('title', 'Panel de Auditor')
@section('active', 'dashboard')
@section('back_url', '')

@section('content')
    <div class="flex justify-end px-4 sm:px-6 lg:px-8 pt-6">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="inline-flex items-center rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
            Salir
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

    @php
        $metrics = $metrics ?? [
            'auditoriasActivas' => 4,
            'hallazgosPendientes' => 12,
            'kycPendientes' => 7,
            'alertasCriticas' => 2,
            'solicitudesRevisadas' => 18,
        ];
        $hallazgos = $hallazgos ?? [
            ['titulo' => 'Reembolso sin soporte', 'proyecto' => 'Proyecto Verde', 'fecha' => '2025-06-01', 'riesgo' => 'Alto'],
            ['titulo' => 'KYC pendiente de colaborador', 'proyecto' => 'TechHub', 'fecha' => '2025-06-02', 'riesgo' => 'Medio'],
            ['titulo' => 'Gasto fuera de categoria', 'proyecto' => 'Arte Vivo', 'fecha' => '2025-05-30', 'riesgo' => 'Medio'],
        ];
        $controles = $controles ?? [
            ['label' => 'Revisar reportes mensuales (HU12-HU15)', 'estado' => 'En curso'],
            ['label' => 'Validar soportes de gastos mayores a $2,500', 'estado' => 'Pendiente'],
            ['label' => 'Cruzar alertas AML/KYC con proveedores nuevos', 'estado' => 'Pendiente'],
            ['label' => 'Cerrar hallazgos abiertos con evidencias', 'estado' => 'En curso'],
        ];
    @endphp

    <div class="space-y-10 px-4 sm:px-6 lg:px-8">
        <section id="overview" class="relative overflow-hidden rounded-[22px] admin-hero px-8 py-10 shadow-2xl ring-1 ring-white/15">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.22),_transparent_45%)] blur-[2px]"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/70">Vigilancia y cumplimiento</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-wide text-white">Panel de auditoria continua</h1>
                    <p class="mt-3 max-w-2xl text-base text-white/75">
                        Monitorea hallazgos, KYC y flujos de fondos. Prioriza riesgos y documenta acciones correctivas en un solo lugar.
                    </p>
                </div>
                <div class="grid gap-3 rounded-2xl bg-white/10 p-6 text-white backdrop-blur-sm shadow-inner">
                    <div class="flex items-center gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-white/70">Auditorias activas</p>
                            <p class="text-4xl font-extrabold">{{ $metrics['auditoriasActivas'] }}</p>
                        </div>
                    </div>
                    <div class="border-t border-white/10 pt-3 flex items-center gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-white/70">Alertas criticas</p>
                            <p class="text-4xl font-extrabold">{{ $metrics['alertasCriticas'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="auditorias" class="space-y-4">
            <div class="flex flex-col gap-2">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Auditorias</p>
                <h2 class="text-2xl font-bold text-white">Estado de las revisiones</h2>
                <p class="text-sm text-zinc-400">Cobertura de casos, hallazgos y avances documentados.</p>
            </div>
            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase text-zinc-400">Hallazgos abiertos</p>
                    <p class="text-3xl font-bold text-white">{{ $metrics['hallazgosPendientes'] }}</p>
                    <p class="text-xs text-zinc-500">Pendientes de evidencia</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase text-zinc-400">Solicitudes revisadas</p>
                    <p class="text-3xl font-bold text-indigo-200">{{ $metrics['solicitudesRevisadas'] }}</p>
                    <p class="text-xs text-zinc-500">Ultimos 14 dias</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase text-zinc-400">KYC pendientes</p>
                    <p class="text-3xl font-bold text-sky-200">{{ $metrics['kycPendientes'] }}</p>
                    <p class="text-xs text-zinc-500">En cola de identidad</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase text-zinc-400">Alertas criticas</p>
                    <p class="text-3xl font-bold text-amber-200">{{ $metrics['alertasCriticas'] }}</p>
                    <p class="text-xs text-zinc-500">Requieren atencion inmediata</p>
                </div>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 admin-accent-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Hallazgos recientes</p>
                            <h3 class="text-lg font-semibold text-white">Resumen de observaciones</h3>
                        </div>
                        <a href="#reportes" class="text-xs text-indigo-200 hover:text-white">Documentar</a>
                    </div>
                    <div class="mt-4 space-y-3 text-sm text-zinc-300">
                        @foreach ($hallazgos as $item)
                            <div class="flex items-start gap-3 rounded-xl border border-white/5 bg-white/5 px-4 py-3">
                                <span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full {{ $item['riesgo'] === 'Alto' ? 'bg-amber-400' : 'bg-indigo-300' }}"></span>
                                <div class="space-y-1">
                                    <p class="font-semibold text-white">{{ $item['titulo'] }}</p>
                                    <p class="text-xs text-zinc-400">{{ $item['proyecto'] }} • {{ $item['fecha'] }} • Riesgo {{ $item['riesgo'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="kyc" class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Identidad</p>
                            <h3 class="text-lg font-semibold text-white">KYC pendientes</h3>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white">
                            {{ $metrics['kycPendientes'] }} en cola
                        </span>
                    </div>
                    <div class="mt-4 grid gap-3 text-sm text-zinc-300">
                        <div class="flex items-center justify-between rounded-xl border border-white/5 bg-white/5 px-4 py-3">
                            <div>
                                <p class="font-semibold text-white">Validar IDs subidos</p>
                                <p class="text-xs text-zinc-400">Cruzar selfie vs documento</p>
                            </div>
                            <span class="text-[11px] font-semibold uppercase tracking-[0.22em] text-indigo-200">Prioridad alta</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-white/5 bg-white/5 px-4 py-3">
                            <div>
                                <p class="font-semibold text-white">Revisar coincidencias AML</p>
                                <p class="text-xs text-zinc-400">Listas restrictivas y sanciones</p>
                            </div>
                            <span class="text-[11px] font-semibold uppercase tracking-[0.22em] text-amber-200">Pendiente</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-white/5 bg-white/5 px-4 py-3">
                            <div>
                                <p class="font-semibold text-white">Aprobar KYC confiables</p>
                                <p class="text-xs text-zinc-400">Registrar evidencia y hash</p>
                            </div>
                            <span class="text-[11px] font-semibold uppercase tracking-[0.22em] text-emerald-200">Listo hoy</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="riesgos" class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Matriz de riesgo</p>
                        <h3 class="text-lg font-semibold text-white">Alertas y seguimiento</h3>
                    </div>
                    <a href="#overview" class="text-xs text-indigo-200 hover:text-white">Actualizar</a>
                </div>
                <div class="mt-4 grid gap-3 text-sm text-zinc-300">
                    <div class="flex items-center justify-between rounded-xl border border-amber-400/40 bg-amber-500/10 px-4 py-3">
                        <div>
                            <p class="font-semibold text-white">Flujo sospechoso detectado</p>
                            <p class="text-xs text-amber-100/90">Monto dividido en multiples transferencias</p>
                        </div>
                        <span class="text-[11px] font-semibold uppercase tracking-[0.22em] text-amber-100">Riesgo alto</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl border border-indigo-400/40 bg-indigo-500/10 px-4 py-3">
                        <div>
                            <p class="font-semibold text-white">Proveedor sin comprobantes</p>
                            <p class="text-xs text-indigo-100/90">Solicitar soporte y validar RFC</p>
                        </div>
                        <span class="text-[11px] font-semibold uppercase tracking-[0.22em] text-indigo-100">Riesgo medio</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl border border-emerald-400/40 bg-emerald-500/10 px-4 py-3">
                        <div>
                            <p class="font-semibold text-white">Reversion completada</p>
                            <p class="text-xs text-emerald-100/90">Caso cerrado con evidencia almacenada</p>
                        </div>
                        <span class="text-[11px] font-semibold uppercase tracking-[0.22em] text-emerald-100">Cerrado</span>
                    </div>
                </div>
            </div>

            <div id="reportes" class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl admin-accent-card">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Checklist</p>
                <h3 class="text-lg font-semibold text-white">Acciones de control</h3>
                <div class="mt-4 space-y-3 text-sm text-zinc-300">
                    @foreach ($controles as $control)
                        <div class="flex items-start gap-3 rounded-xl border border-white/5 bg-white/5 px-4 py-3">
                            <input type="checkbox" class="mt-1 h-4 w-4 rounded border-white/20 bg-transparent text-indigo-400 focus:ring-indigo-500" />
                            <div class="flex-1">
                                <p class="font-semibold text-white">{{ $control['label'] }}</p>
                                <p class="text-[11px] uppercase tracking-[0.22em] {{ $control['estado'] === 'Pendiente' ? 'text-amber-200' : 'text-indigo-200' }}">{{ $control['estado'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-5 flex flex-col gap-3">
                    <button class="admin-btn admin-btn-primary">Registrar reporte</button>
                    <button class="admin-btn admin-btn-ghost">Exportar evidencia</button>
                </div>
            </div>
        </section>
    </div>
@endsection
