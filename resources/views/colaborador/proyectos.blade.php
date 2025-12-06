@extends('colaborador.layouts.panel')

@section('title', 'Proyectos que apoyas')

@section('content')
<section class="p-8 space-y-6">

    <header class="mb-4">
        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">
            Proyectos
        </p>
        <h1 class="text-2xl font-bold text-white mt-2">
            Proyectos que estás apoyando
        </h1>
        <p class="text-sm text-zinc-400 mt-1">
            Resumen rápido de los proyectos asociados a tus aportaciones.
        </p>
    </header>

    <div class="rounded-3xl border border-white/10 bg-zinc-900/70 shadow-xl p-6">
        @if(isset($proyectosAportados) && $proyectosAportados->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-white">
                    <thead class="border-b border-white/10 text-zinc-300">
                        <tr>
                            <th class="py-3 pr-4">Proyecto</th>
                            <th class="py-3 pr-4">Meta</th>
                            <th class="py-3 pr-4">Recaudado</th>
                            <th class="py-3 pr-4">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($proyectosAportados as $proyecto)
                            <tr class="hover:bg-white/5">
                                <td class="py-3 pr-4 font-semibold">
                                    {{ $proyecto->titulo ?? 'Proyecto sin título' }}
                                </td>
                                <td class="py-3 pr-4">
                                    ${{ number_format($proyecto->meta_financiacion ?? 0, 2) }}
                                </td>
                                <td class="py-3 pr-4">
                                    ${{ number_format($proyecto->monto_recaudado ?? 0, 2) }}
                                </td>
                                <td class="py-3 pr-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs
                                           bg-indigo-600/20 text-indigo-300 border border-indigo-500/40">
                                        {{ strtoupper($proyecto->estado ?? 'EN PROGRESO') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-zinc-300">
                Todavía no has realizado aportaciones. Explora proyectos y apoya tu primera campaña.
            </p>
        @endif
    </div>
</section>
@endsection

