@extends('creator.layouts.panel')

@section('title', 'Proyectos')
@section('active', 'proyectos')

@section('content')
    @php
        $categorias = $proyectos->pluck('categoria')->filter()->unique()->values();
        $borradores = $proyectos->filter(fn($p) => ($p->estado ?? 'borrador') === 'borrador');
        $publicados = $proyectos->filter(fn($p) => ($p->estado ?? 'borrador') !== 'borrador');
        $estadoBadge = fn($estado) => match($estado) {
            'borrador' => ['bg' => 'bg-amber-500/15 text-amber-200 border-amber-400/40', 'label' => 'Borrador'],
            'publicado' => ['bg' => 'bg-emerald-500/15 text-emerald-200 border-emerald-400/40', 'label' => 'Publicado'],
            'finalizado' => ['bg' => 'bg-sky-500/15 text-sky-200 border-sky-400/40', 'label' => 'Finalizado'],
            default => ['bg' => 'bg-zinc-500/15 text-zinc-200 border-white/10', 'label' => strtoupper($estado ?? 'N/D')],
        };
    @endphp

    <div class="px-4 sm:px-6 lg:px-8 space-y-8">
        <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-600/25 via-zinc-900/80 to-zinc-900/80 p-8 shadow-2xl">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-300">Campañas</p>
                    <h2 class="mt-1 text-2xl font-bold text-white">Tus proyectos</h2>
                    <p class="text-sm text-zinc-400">Revisión rápida de estado y acceso directo para crear nuevas campañas.</p>
                </div>
                <div>
                    @php $isVerified = Auth::user()->estado_verificacion; @endphp
                    @if ($isVerified)
                        <a href="{{ route('creador.proyectos.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/40 hover:bg-emerald-400">
                            Crear nuevo proyecto
                        </a>
                    @else
                        <div class="rounded-2xl border border-amber-500/40 bg-amber-500/10 px-4 py-3 text-sm text-amber-100">
                            Tu cuenta debe estar verificada para crear proyectos. <a class="underline text-white" href="{{ route('creador.perfil.verificacion.form') }}">Verifica ahora</a>
                        </div>
                    @endif
                </div>
            </div>
            <form method="GET" action="{{ route('creador.proyectos') }}" class="mt-4 grid gap-3 sm:grid-cols-[1.4fr,0.8fr,0.8fr,auto] sm:items-end">
                <div>
                    <label class="text-xs text-zinc-300">Buscar</label>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Título, descripción o categoría" class="mt-1 w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="text-xs text-zinc-300">Estado</label>
                    <select name="estado" class="mt-1 w-full rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        <option value="">Todos</option>
                        <option value="borrador" @selected(($estado ?? '') === 'borrador')>Borrador</option>
                        <option value="publicado" @selected(($estado ?? '') === 'publicado')>Publicado</option>
                        <option value="finalizado" @selected(($estado ?? '') === 'finalizado')>Finalizado</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-zinc-300">Categoría</label>
                    <select name="categoria" class="mt-1 w-full rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        <option value="">Todas</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria }}" @selected(request('categoria') === $categoria)>{{ $categoria }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500">
                        Filtrar
                    </button>
                    <a href="{{ route('creador.proyectos') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white hover:border-indigo-400/60">
                        Limpiar
                    </a>
                </div>
            </form>
        </section>

        <section id="project-list" class="space-y-6">
            <div class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Borradores ({{ $borradores->count() }})</p>
                        <h3 class="text-lg font-semibold text-white">Lista de borradores</h3>
                    </div>
                </div>
                <div class="mt-4 grid gap-4">
                    @forelse ($borradores as $proyecto)
                        <article class="rounded-2xl border border-white/5 bg-white/5 p-4 shadow-inner ring-1 ring-indigo-500/10 space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    @php $badge = $estadoBadge($proyecto->estado ?? 'borrador'); @endphp
                                    <span class="inline-flex items-center gap-1 rounded-full border px-3 py-1 text-[11px] font-semibold {{ $badge['bg'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                    <p class="text-sm font-semibold text-white mt-1">{{ $proyecto->titulo }}</p>
                                </div>
                                <p class="text-xs text-zinc-400">Modelo: {{ $proyecto->modelo_financiamento ?? 'N/D' }} · Categoría: {{ $proyecto->categoria ?? 'N/D' }}</p>
                            </div>
                            <p class="text-xs text-zinc-400">Meta: ${{ number_format($proyecto->meta_financiacion ?? 0, 0, ',', '.') }}</p>
                            @if($proyecto->imagen_portada)
                                <div class="mt-1 h-32 overflow-hidden rounded-2xl border border-white/10">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada) }}" alt="Portada" class="h-full w-full object-cover">
                                </div>
                            @else
                                <div class="mt-1 h-32 rounded-2xl border border-white/10 bg-gradient-to-r from-zinc-900 to-slate-900 flex items-center justify-center text-sm text-zinc-500">
                                    Sin imagen disponible
                                </div>
                            @endif
                            <p class="text-sm text-zinc-300 line-clamp-2">{{ $proyecto->descripcion_proyecto ?: 'Sin descripción' }}</p>
                            <div class="flex flex-wrap items-center justify-between text-xs text-zinc-400">
                                <span>% recaudado: {{ $proyecto->meta_financiacion ? round(($proyecto->monto_recaudado ?? 0) / $proyecto->meta_financiacion * 100, 1) : 0 }}%</span>
                                <span>{{ $proyecto->aportaciones_count ?? 0 }} aportes</span>
                            </div>
                            <div class="flex justify-end">
                                <a href="{{ route('creador.proyectos.edit', $proyecto) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                                    Editar & publicar
                                </a>
                            </div>
                        </article>
                    @empty
                        <p class="text-sm text-zinc-400">No hay borradores. Usa el botón “Crear nuevo proyecto” para comenzar.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Publicados ({{ $publicados->count() }})</p>
                        <h3 class="text-lg font-semibold text-white">Campañas activas</h3>
                    </div>
                </div>
                <div class="mt-4 grid gap-4">
                    @forelse ($publicados as $proyecto)
                        <article class="rounded-2xl border border-white/5 bg-white/5 p-4 shadow-inner ring-1 ring-indigo-500/10 space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    @php $badge = $estadoBadge($proyecto->estado); @endphp
                                    <span class="inline-flex items-center gap-1 rounded-full border px-3 py-1 text-[11px] font-semibold {{ $badge['bg'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                    <p class="text-sm font-semibold text-white mt-1">{{ $proyecto->titulo }}</p>
                                </div>
                                <p class="text-xs text-zinc-400">Modelo: {{ $proyecto->modelo_financiamento ?? 'N/D' }} · Categoría: {{ $proyecto->categoria ?? 'N/D' }}</p>
                            </div>
                            @if($proyecto->imagen_portada)
                                <div class="mt-1 h-32 overflow-hidden rounded-2xl border border-white/10">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada) }}" alt="Portada" class="h-full w-full object-cover">
                                </div>
                            @else
                                <div class="mt-1 h-32 rounded-2xl border border-white/10 bg-gradient-to-r from-zinc-900 to-slate-900 flex items-center justify-center text-sm text-zinc-500">
                                    Sin imagen disponible
                                </div>
                            @endif
                            <p class="text-sm text-zinc-300 line-clamp-2">{{ $proyecto->descripcion_proyecto ?: 'Sin descripción' }}</p>
                            <div class="flex flex-wrap items-center justify-between text-xs text-zinc-400">
                                <span>% recaudado: {{ $proyecto->meta_financiacion ? round(($proyecto->monto_recaudado ?? 0) / $proyecto->meta_financiacion * 100, 1) : 0 }}%</span>
                                <span>{{ $proyecto->aportaciones_count ?? 0 }} aportes</span>
                            </div>
                            <div class="flex justify-end">
                                <a href="{{ route('creador.proyectos.edit', $proyecto) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                                    Gestionar campaña
                                </a>
                            </div>
                        </article>
                    @empty
                        <p class="text-sm text-zinc-400">Aún no tienes campañas publicadas. Activa alguna desde el módulo de proyectos.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
@endsection
