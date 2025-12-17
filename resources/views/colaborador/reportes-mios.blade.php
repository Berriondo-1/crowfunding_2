@extends('colaborador.layouts.panel')

@section('title', 'Mis reportes')
@section('active', 'reportes')

@section('content')
<div class="px-4 pt-6 pb-12 lg:px-8 space-y-8">
    <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-sky-900/70 via-slate-900/80 to-slate-900/80 p-6 lg:p-8 shadow-2xl ring-1 ring-sky-500/15">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-2">
                <p class="text-xs uppercase tracking-[0.3em] text-sky-200/70">Reportes enviados</p>
                <h1 class="text-3xl font-bold text-white">Mis reportes sospechosos</h1>
                <p class="text-sm text-zinc-300">Revisa el estado, evidencias y respuestas de auditoría.</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('colaborador.reportes') }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-semibold text-sky-950 shadow-lg shadow-sky-900/40 hover:bg-sky-400">
                        Crear nuevo reporte
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </a>
                    <span class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-zinc-200">
                        Total: {{ $reportes->count() }}
                    </span>
                </div>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-zinc-200">
                <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-400">Último envío</p>
                <p class="text-lg font-semibold text-white">
                    {{ optional($reportes->first()?->created_at)->format('d/m/Y H:i') ?? 'Sin reportes' }}
                </p>
            </div>
        </div>
    </section>

    <section class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-semibold text-zinc-100 tracking-wide uppercase">Historial</h2>
            <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-300">
                {{ $reportes->count() }} reportes
            </span>
        </div>

        @if ($reportes->isEmpty())
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-6 text-sm text-zinc-300">
                Aún no has enviado reportes. ¿Algo te parece irregular? <a href="{{ route('colaborador.reportes') }}" class="text-sky-300 underline">Inicia un reporte</a>.
            </div>
        @else
            <div class="space-y-4">
                @foreach ($reportes as $reporte)
                    <article class="rounded-2xl border border-white/10 bg-white/5 p-5 shadow-[0_18px_40px_rgba(0,0,0,0.35)] space-y-3">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="space-y-1">
                                <p class="text-[11px] uppercase tracking-[0.25em] text-zinc-400">Proyecto</p>
                                <p class="text-base font-semibold text-white">{{ $reporte->proyecto->titulo ?? 'Proyecto' }}</p>
                                <p class="text-xs text-zinc-400">Enviado: {{ $reporte->created_at?->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-semibold
                                    @class([
                                        'bg-amber-500/15 text-amber-100 border border-amber-400/30' => $reporte->estado === 'pendiente',
                                        'bg-emerald-500/15 text-emerald-100 border border-emerald-400/30' => $reporte->estado === 'aprobado',
                                        'bg-red-500/15 text-red-100 border border-red-400/30' => $reporte->estado === 'rechazado',
                                        'bg-white/10 text-zinc-200 border border-white/10' => !in_array($reporte->estado, ['pendiente','aprobado','rechazado'])
                                    ])">
                                    {{ ucfirst($reporte->estado) }}
                                </span>
                                @if ($reporte->respuesta)
                                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3 py-1 text-[11px] font-semibold text-emerald-100">
                                        Nota auditor
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="rounded-xl border border-white/10 bg-black/30 p-3 text-sm text-zinc-200">
                            <p class="text-xs uppercase tracking-[0.2em] text-zinc-500 mb-1">Motivo reportado</p>
                            <p class="leading-relaxed">{{ $reporte->motivo }}</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 text-[12px] text-zinc-400">
                            @if ($reporte->respuesta)
                                <span class="inline-flex items-center gap-2 rounded-md bg-emerald-500/10 px-3 py-1 text-emerald-100 border border-emerald-400/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" /></svg>
                                    {{ $reporte->respuesta }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 rounded-md bg-white/5 px-3 py-1 text-zinc-300 border border-white/10">
                                    Sin respuesta de auditoría aún
                                </span>
                            @endif
                        </div>

                        @if (!empty($reporte->evidencias))
                            <div class="space-y-2">
                                <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-500">Evidencias</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($reporte->evidencias as $idx => $path)
                                        <a href="{{ asset('storage/'.$path) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/10 px-3 py-1.5 text-[12px] font-semibold text-white hover:border-sky-400/60">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h6" />
                                            </svg>
                                            Evidencia {{ $idx + 1 }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
