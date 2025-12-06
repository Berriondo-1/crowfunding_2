<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel de Colaborador') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Hero, igual estilo que el creador --}}
            <section
                class="bg-gradient-to-r from-indigo-500 via-purple-500 to-fuchsia-500 rounded-3xl px-8 py-10 text-white shadow-xl">
                <div class="flex flex-col lg:flex-row justify-between gap-8">
                    <div class="space-y-4 max-w-2xl">
                        <p class="text-sm uppercase tracking-[0.25em] text-indigo-100">
                            PANEL DE COLABORADOR
                        </p>
                        <h1 class="text-3xl md:text-4xl font-bold">
                            Sigue el impacto de tus aportaciones con total transparencia
                        </h1>
                        <p class="text-indigo-100 text-sm md:text-base">
                            Revisa los proyectos que apoyas, el monto aportado y el uso de los fondos
                            que han realizado los creadores.
                        </p>
                    </div>

                    {{-- Tarjetas de métricas --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:w-1/2">
                        <div class="bg-white/10 border border-white/20 rounded-2xl p-4">
                            <p class="text-xs uppercase tracking-wide text-indigo-100">Total aportado</p>
                            <p class="mt-2 text-2xl font-semibold">
                                ${{ number_format($metrics['totalAportado'] ?? 0, 2) }}
                            </p>
                        </div>

                        <div class="bg-white/10 border border-white/20 rounded-2xl p-4">
                            <p class="text-xs uppercase tracking-wide text-indigo-100">Proyectos apoyados</p>
                            <p class="mt-2 text-2xl font-semibold">
                                {{ $metrics['numProyectos'] ?? 0 }}
                            </p>
                        </div>

                        <div class="bg-white/10 border border-white/20 rounded-2xl p-4">
                            <p class="text-xs uppercase tracking-wide text-indigo-100">Aportaciones realizadas</p>
                            <p class="mt-2 text-2xl font-semibold">
                                {{ $metrics['numAportaciones'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Módulos principales para colaborador --}}
            <section class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Proyectos que estás apoyando
                </h3>

                <div class="bg-gray-900/80 border border-gray-800 rounded-3xl p-6 text-gray-100">
                    @if(isset($proyectosAportados) && $proyectosAportados->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-400 border-b border-gray-800">
                                        <th class="py-2 pr-4">Proyecto</th>
                                        <th class="py-2 pr-4">Meta</th>
                                        <th class="py-2 pr-4">Recaudado</th>
                                        <th class="py-2 pr-4">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800">
                                    @foreach($proyectosAportados as $proyecto)
                                        <tr>
                                            <td class="py-3 pr-4">
                                                <span class="font-medium">{{ $proyecto->titulo ?? 'Proyecto' }}</span>
                                            </td>
                                            <td class="py-3 pr-4">
                                                ${{ number_format($proyecto->meta_financiacion ?? 0, 2) }}
                                            </td>
                                            <td class="py-3 pr-4">
                                                ${{ number_format($proyecto->monto_recaudado ?? 0, 2) }}
                                            </td>
                                            <td class="py-3 pr-4">
                                                <span class="inline-flex items-center rounded-full bg-indigo-500/20 px-2.5 py-0.5 text-xs font-medium text-indigo-300">
                                                    {{ $proyecto->estado ?? 'En progreso' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-300">
                            Todavía no has realizado aportaciones. Explora proyectos y apoya tu primera campaña.
                        </p>
                    @endif
                </div>
            </section>

        </div>
    </div>
</x-app-layout>
