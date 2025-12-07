@extends('colaborador.layouts.panel')

@section('title', 'Mis reportes')
@section('active', 'reportes')

@section('content')
<div class="px-4 pt-6 pb-10 lg:px-8 space-y-6">
    <header class="space-y-2">
        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Reportes enviados</p>
        <h1 class="text-2xl font-bold text-white">Mis reportes sospechosos</h1>
        <p class="text-sm text-zinc-400">Revisa el estado y las respuestas de auditoría.</p>
        <a href="{{ route('colaborador.reportes') }}" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-white hover:border-indigo-400/60">
            Crear nuevo reporte
        </a>
    </header>

    <section class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-semibold text-zinc-100 tracking-wide uppercase">Historial</h2>
            <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-300">
                {{ $reportes->count() }} reportes
            </span>
        </div>

        @if ($reportes->isEmpty())
            <p class="text-sm text-zinc-400">Aun no has enviado reportes.</p>
        @else
            <div class="space-y-3">
                @foreach ($reportes as $reporte)
                    <article class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-white shadow-[0_18px_40px_rgba(0,0,0,0.35)] space-y-2">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <p class="text-xs uppercase tracking-[0.2em] text-zinc-400">Proyecto</p>
                                <p class="text-base font-semibold">{{ $reporte->proyecto->titulo ?? 'Proyecto' }}</p>
                            </div>
                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-semibold
                                @class([
                                    'bg-amber-500/15 text-amber-100 border border-amber-400/30' => $reporte->estado === 'pendiente',
                                    'bg-emerald-500/15 text-emerald-100 border border-emerald-400/30' => $reporte->estado === 'aprobado',
                                    'bg-red-500/15 text-red-100 border border-red-400/30' => $reporte->estado === 'rechazado',
                                    'bg-white/10 text-zinc-200 border border-white/10' => !in_array($reporte->estado, ['pendiente','aprobado','rechazado'])
                                ])">
                                {{ ucfirst($reporte->estado) }}
                            </span>
                        </div>
                        <p class="text-[12px] text-zinc-300 leading-relaxed">{{ $reporte->motivo }}</p>
                        <div class="flex flex-wrap items-center gap-2 text-[12px] text-zinc-400">
                            <span>Enviado: {{ $reporte->created_at?->format('d/m/Y H:i') }}</span>
                            @if ($reporte->respuesta)
                                <span class="inline-flex items-center gap-1 rounded-md bg-white/10 px-2 py-1 text-emerald-100">
                                    Nota auditor: {{ $reporte->respuesta }}
                                </span>
                            @endif
                        </div>
                        @if (!empty($reporte->evidencias))
                            <div class="flex flex-wrap gap-2 pt-1">
                                @foreach ($reporte->evidencias as $idx => $path)
                                    <a href="{{ asset('storage/'.$path) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/10 px-3 py-1.5 text-[12px] font-semibold text-white hover:border-indigo-400/60">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h6" />
                                        </svg>
                                        Evidencia {{ $idx + 1 }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
