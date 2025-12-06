@extends('colaborador.layouts.panel')

@section('title', 'Reportes de aportaciones')

@section('content')
<section class="p-8 space-y-6">

    <header class="mb-4">
        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">
            Reportes
        </p>
        <h1 class="text-2xl font-bold text-white mt-2">
            Resumen de tus aportes
        </h1>
        <p class="text-sm text-zinc-400 mt-1">
            Visión general de tu actividad como colaborador.
        </p>
    </header>

    {{-- Tarjetas de resumen --}}
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-white/10 bg-zinc-900/80 p-4 shadow-inner">
            <p class="text-[11px] uppercase tracking-[0.3em] text-indigo-300">Total aportado</p>
            <p class="mt-2 text-2xl font-bold">
                ${{ number_format($totalAportado ?? 0, 2) }}
            </p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-zinc-900/80 p-4 shadow-inner">
            <p class="text-[11px] uppercase tracking-[0.3em] text-indigo-300">Proyectos apoyados</p>
            <p class="mt-2 text-2xl font-bold">
                {{ $numProyectos ?? 0 }}
            </p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-zinc-900/80 p-4 shadow-inner">
            <p class="text-[11px] uppercase tracking-[0.3em] text-indigo-300">Aportaciones</p>
            <p class="mt-2 text-2xl font-bold">
                {{ $numAportaciones ?? 0 }}
            </p>
        </div>
    </div>

    {{-- Tabla detallada (opcional) --}}
    <div class="rounded-3xl border border-white/10 bg-zinc-900/70 shadow-xl p-6">
        @if($aportaciones->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-white">
                    <thead class="border-b border-white/10 text-zinc-300">
                        <tr>
                            <th class="py-3 pr-4">Fecha</th>
                            <th class="py-3 pr-4">Proyecto</th>
                            <th class="py-3 pr-4">Monto</th>
                            <th class="py-3 pr-4">Estado de pago</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($aportaciones as $aporte)
                            <tr class="hover:bg-white/5">
                                <td class="py-3 pr-4">
                                    {{ optional($aporte->fecha_aportacion)->format('d/m/Y') ?? $aporte->created_at->format('d/m/Y') }}
                                </td>
                                <td class="py-3 pr-4 font-semibold">
                                    {{ optional($aporte->proyecto)->titulo ?? 'Proyecto eliminado' }}
                                </td>
                                <td class="py-3 pr-4">
                                    ${{ number_format($aporte->monto, 2) }}
                                </td>
                                <td class="py-3 pr-4">
                                    {{ strtoupper($aporte->estado_pago ?? 'CONFIRMADO') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-zinc-300">
                No hay datos suficientes para generar reportes.
            </p>
        @endif
    </div>
</section>
@endsection
