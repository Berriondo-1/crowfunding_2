@extends('admin.layouts.panel')

@section('title', 'Proyectos')
@section('active', 'proyectos')

@section('content')
    @php
        $btnSolid = 'inline-flex items-center gap-2 rounded-xl bg-[#4f46e5] px-4 py-2.5 text-sm font-semibold text-white border border-[#4f46e5] hover:bg-[#4338ca]';
    @endphp

    <div class="space-y-8">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/75 shadow-2xl ring-1 ring-indigo-500/10 admin-accent-card">
            <div class="border-b border-white/5 px-6 py-6 space-y-4">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Monitor</p>
                        <h2 class="mt-1 text-2xl font-bold text-white">Supervision de proyectos</h2>
                        <p class="mt-2 text-sm text-zinc-400">
                            Publica, valida y revisa proyectos activos. Selecciona un proyecto para ver sus detalles.
                        </p>
                        <p class="mt-2 text-xs text-zinc-500">
                            Mostrando {{ $proyectos->count() }} de {{ $proyectos->total() }} proyectos - En revisión: {{ $estadoResumen['en_revision'] ?? 0 }}, Publicados: {{ $estadoResumen['publicado'] ?? 0 }}, Pausados: {{ $estadoResumen['pausado'] ?? 0 }}
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-xs text-zinc-300">
                        <a href="{{ route('admin.proyectos.config') }}" class="admin-btn admin-btn-ghost">
                            Gestionar catalogos
                        </a>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.proyectos') }}" class="grid gap-3 sm:grid-cols-[2fr,1fr,1fr,1fr,auto] sm:items-end">
                    <div>
                        <label class="text-xs text-zinc-400">Busqueda</label>
                        <input type="text" name="q" value="{{ $search }}" placeholder="Titulo, categoria o ubicacion"
                               class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400">
                    </div>
                    <div>
                        <label class="text-xs text-zinc-400">Estado</label>
                        <select name="estado" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                            <option value="">Todos</option>
                            <option value="borrador" @selected($estado === 'borrador')>Borrador</option>
                            <option value="pendiente" @selected($estado === 'pendiente')>Pendiente</option>
                            <option value="en_revision" @selected($estado === 'en_revision')>En revisión</option>
                            <option value="publicado" @selected($estado === 'publicado')>Publicado</option>
                            <option value="pausado" @selected($estado === 'pausado')>Pausado</option>
                            <option value="rechazado" @selected($estado === 'rechazado')>Rechazado</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-zinc-400">Categoria</label>
                        <select name="categoria" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                            <option value="">Todas</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat }}" @selected($categoria === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-zinc-400">Modelo</label>
                        <select name="modelo" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                            <option value="">Todos</option>
                            @foreach ($modelos as $model)
                                <option value="{{ $model }}" @selected($modelo === $model)>{{ $model }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="{{ $btnSolid }}">
                            Filtrar
                        </button>
                        <a href="{{ route('admin.proyectos') }}" class="admin-btn admin-btn-ghost">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            @php
                $estadoBadge = fn($estado) => match($estado) {
                    'publicado' => ['bg' => 'bg-emerald-500 text-emerald-950 border-emerald-600', 'label' => 'Publicado'],
                    'borrador' => ['bg' => 'bg-amber-500 text-amber-950 border-amber-600', 'label' => 'Borrador'],
                    'pendiente', 'en_revision' => ['bg' => 'bg-amber-400 text-amber-950 border-amber-500', 'label' => 'En revisión'],
                    'pausado' => ['bg' => 'bg-rose-500 text-rose-50 border-rose-600', 'label' => 'Pausado'],
                    'rechazado' => ['bg' => 'bg-rose-700 text-rose-50 border-rose-800', 'label' => 'Rechazado'],
                    'finalizado' => ['bg' => 'bg-sky-500 text-sky-950 border-sky-600', 'label' => 'Finalizado'],
                    default => ['bg' => 'bg-zinc-500 text-zinc-50 border-zinc-600', 'label' => strtoupper($estado ?? 'N/D')],
                };
            @endphp

            <div class="p-6">
                @if ($proyectos->isEmpty())
                    <p class="text-center text-sm text-zinc-400">No hay proyectos cargados aun.</p>
                @else
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($proyectos as $proyecto)
                            @php
                                $meta = $proyecto->meta_financiacion ?: 1;
                                $recaudado = $recaudadoPorProyecto[$proyecto->id] ?? ($proyecto->monto_recaudado ?? 0);
                                $progreso = $meta > 0 ? min(100, round(($recaudado / $meta) * 100)) : 0;
                                $imagenPortada = $proyecto->imagen_portada
                                    ? \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada)
                                    : 'https://images.unsplash.com/photo-1512428559087-560fa5ceab42?auto=format&fit=crop&w=900&q=80';
                                $badge = $estadoBadge($proyecto->estado ?? 'borrador');
                            @endphp
                            <article class="rounded-3xl border border-white/10 bg-zinc-950/75 p-4 shadow-[0_12px_45px_rgba(0,0,0,0.35)] ring-1 ring-indigo-500/10 flex flex-col gap-4">
                                <div class="relative overflow-hidden rounded-xl border border-white/10 bg-zinc-900/70 aspect-[4/3]">
                                    <img src="{{ $imagenPortada }}" alt="Portada del proyecto {{ $proyecto->titulo }}" class="h-full w-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                                    <div class="absolute top-3 left-3 flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-[11px] font-semibold border {{ $badge['bg'] }}">
                                            {{ $badge['label'] }}
                                        </span>
                                        @if ($proyecto->categoria)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-500 text-amber-950 px-3 py-1 text-[11px] font-extrabold shadow-lg shadow-amber-500/30">
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
                                        Creador: <span class="text-zinc-200 font-medium">{{ $proyecto->creador->nombre_completo ?? $proyecto->creador->name ?? 'N/D' }}</span>
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
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('admin.proyectos.show', $proyecto) }}" class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-indigo-600 px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-white shadow-[0_10px_24px_rgba(79,70,229,0.4)] hover:bg-indigo-500 transition-colors">
                                        Ver detalle
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="border-t border-white/5 px-4 py-3 text-right text-xs text-zinc-400">
                        {{ $proyectos->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection
