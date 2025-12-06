@extends('colaborador.layouts.panel')

@section('title', 'Tus aportaciones')

@section('content')
<section class="p-8 space-y-6">
    <header class="mb-4">
        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">
            Aportaciones
        </p>
        <h1 class="text-2xl font-bold text-white mt-2">
            Historial de aportaciones
        </h1>
        <p class="text-sm text-zinc-400 mt-1">
            Revisa el detalle de cada aporte que has realizado.
        </p>
    </header>

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
                            <th class="py-3 pr-4">ID transacción</th>
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
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs
                                           bg-emerald-600/20 text-emerald-300 border border-emerald-500/40">
                                        {{ strtoupper($aporte->estado_pago ?? 'CONFIRMADO') }}
                                    </span>
                                </td>
                                <td class="py-3 pr-4 text-xs text-zinc-300">
                                    {{ $aporte->id_transaccion_pago ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-zinc-300">
                Aún no has realizado aportaciones.
            </p>
        @endif
    </div>
</section>
@endsection
