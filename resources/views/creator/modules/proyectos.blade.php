@extends('creator.layouts.panel')

@section('title', 'Proyectos')
@section('active', 'proyectos')

@section('content')
    @php
        $categorias = $proyectos->pluck('categoria')->filter()->unique()->values();
        $borradores = $proyectos->filter(fn($p) => ($p->estado ?? 'borrador') === 'borrador');
        $publicados = $proyectos->filter(fn($p) => ($p->estado ?? 'borrador') !== 'borrador');
        $estadoBadge = fn($estado) => match($estado) {
            'borrador' => ['bg' => 'bg-amber-500 text-amber-950 border-amber-600', 'label' => 'Borrador'],
            'publicado' => ['bg' => 'bg-emerald-500 text-emerald-950 border-emerald-600', 'label' => 'Publicado'],
            'finalizado' => ['bg' => 'bg-sky-500 text-sky-950 border-sky-600', 'label' => 'Finalizado'],
            default => ['bg' => 'bg-zinc-500 text-zinc-50 border-zinc-600', 'label' => strtoupper($estado ?? 'N/D')],
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
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Proyectos ({{ $proyectos->count() }})</p>
                        <h3 class="text-lg font-semibold text-white">Tus campañas</h3>
                    </div>
                </div>

                @if ($proyectos->isEmpty())
                    <p class="mt-4 text-sm text-zinc-400">Aún no tienes proyectos creados. Usa “Crear nuevo proyecto” para comenzar.</p>
                @else
                    <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($proyectos as $proyecto)
                            @php
                                $meta = $proyecto->meta_financiacion ?: 1;
                                $recaudado = $proyecto->monto_recaudado ?? 0;
                                $progreso = $meta > 0 ? min(100, round(($recaudado / $meta) * 100)) : 0;
                                $imagenPortada = $proyecto->imagen_portada
                                    ? \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada)
                                    : 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&q=80';
                                $badge = $estadoBadge($proyecto->estado ?? 'borrador');
                            @endphp
                            <article class="rounded-3xl border border-white/10 bg-zinc-950/75 p-4 shadow-[0_12px_45px_rgba(0,0,0,0.35)] ring-1 ring-indigo-500/10 flex flex-col gap-4">
                                <div class="relative overflow-hidden rounded-xl border border-white/10 bg-zinc-900/70 aspect-[4/3]">
                                    <img src="{{ $imagenPortada }}" alt="Portada del proyecto {{ $proyecto->titulo }}" class="h-full w-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/35 to-transparent"></div>
                                    <div class="absolute top-3 left-3 flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-[11px] font-semibold border {{ $badge['bg'] }}">
                                            {{ $badge['label'] }}
                                        </span>
                                        @if ($proyecto->categoria)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/85 text-amber-950 px-3 py-1 text-[11px] font-extrabold shadow-lg shadow-amber-500/30">
                                                {{ ucfirst($proyecto->categoria) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="space-y-2 flex-1">
                                    <h3 class="text-xl font-bold text-white leading-tight line-clamp-2">
                                        {{ $proyecto->titulo }}
                                    </h3>
                                    <p class="text-[13px] text-zinc-300 line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit($proyecto->descripcion_corta ?? $proyecto->descripcion_proyecto, 110) }}
                                    </p>
                                    <p class="text-[12px] text-zinc-500">
                                        Modelo: <span class="text-zinc-200 font-medium">{{ $proyecto->modelo_financiamento ?? $proyecto->modelo_financiamiento ?? 'N/D' }}</span>
                                    </p>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center justify-between text-[12px] text-zinc-300">
                                        <span class="font-semibold">Progreso</span>
                                        <span class="text-base font-bold text-sky-200">{{ $progreso }}%</span>
                                    </div>
                                    <div class="h-2.5 w-full rounded-full bg-zinc-900/80 overflow-hidden ring-1 ring-white/10">
                                        <div class="h-full rounded-full bg-gradient-to-r from-sky-400 via-cyan-400 to-emerald-400 shadow-[0_0_12px_rgba(56,189,248,0.45)]"
                                             style="width: {{ $progreso }}%;"></div>
                                    </div>
                                    <div class="flex items-start justify-between text-sm text-zinc-200">
                                        <div class="space-y-1">
                                            <p class="text-[11px] uppercase tracking-wide text-zinc-500">Recaudado</p>
                                            <p class="font-semibold text-white text-base">${{ number_format($recaudado, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="space-y-1 text-right">
                                            <p class="text-[11px] uppercase tracking-wide text-zinc-500">Meta</p>
                                            <p class="font-semibold text-white text-base">${{ number_format($meta, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <p class="text-[12px] text-zinc-500">{{ $proyecto->aportaciones_count ?? 0 }} aportes</p>
                                </div>

                                <div class="flex gap-2">
                                    @php
                                        $isPublished = ($proyecto->estado ?? '') === 'publicado';
                                        $buttonLabel = $isPublished ? 'Gestionar campaña' : 'Editar / Publicar';
                                        $buttonUrl = $isPublished ? route('creador.avances') : route('creador.proyectos.edit', $proyecto);
                                    @endphp
                                    <a href="{{ $buttonUrl }}"
                                       class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-emerald-600 px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-emerald-950 shadow-[0_10px_24px_rgba(16,185,129,0.4)] hover:bg-emerald-500 transition-colors">
                                        {{ $buttonLabel }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection
