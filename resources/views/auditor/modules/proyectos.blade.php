@extends('auditor.layouts.panel')

@section('title', 'Auditoria de proyectos')
@section('active', 'proyectos')

@section('content')
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 pt-6">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Modulo 6</p>
            <h1 class="text-2xl font-bold text-white">Auditoria de proyectos</h1>
            <p class="text-sm text-zinc-400">Revisa publicaciones, estados y toma acciones.</p>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="rounded-[22px] border border-white/10 bg-zinc-950/80 p-5 shadow-xl backdrop-blur">
            <form method="GET" class="grid gap-3 md:grid-cols-[1fr_200px_auto] items-end">
                <div>
                    <label class="block text-xs uppercase tracking-[0.3em] text-zinc-500">Buscar</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Proyecto"
                           class="mt-1 w-full rounded-xl border border-white/10 bg-zinc-900/80 px-3 py-2 text-sm text-white focus:border-indigo-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-[0.3em] text-zinc-500">Estado</label>
                    <select name="estado" class="mt-1 w-full rounded-xl border border-white/10 bg-zinc-900/80 px-3 py-2 text-sm text-white focus:border-indigo-400 focus:outline-none">
                        <option value="">Todos</option>
                        @foreach ($estadosPublicacion as $opt)
                            <option value="{{ $opt }}" {{ ($estado ?? '') === $opt ? 'selected' : '' }}>{{ ucfirst($opt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="admin-btn admin-btn-primary text-xs">Filtrar</button>
                    <a href="{{ route('auditor.proyectos') }}" class="admin-btn admin-btn-ghost text-xs">Limpiar</a>
                </div>
            </form>
        </div>

        @php
            $estadoBadge = fn($estado) => match($estado) {
                'publicado' => ['bg' => 'bg-emerald-500 text-emerald-950 border-emerald-600', 'label' => 'Publicado'],
                'borrador' => ['bg' => 'bg-amber-500 text-amber-950 border-amber-600', 'label' => 'Borrador'],
                'finalizado' => ['bg' => 'bg-sky-500 text-sky-950 border-sky-600', 'label' => 'Finalizado'],
                default => ['bg' => 'bg-zinc-500 text-zinc-50 border-zinc-600', 'label' => ucfirst($estado ?? 'N/D')],
            };
        @endphp

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($proyectos as $item)
                @php
                    $meta = $item->meta_financiacion ?: 1;
                    $recaudado = $item->monto_recaudado ?? 0;
                    $progreso = $meta > 0 ? min(100, round(($recaudado / $meta) * 100)) : 0;
                    $imagenPortada = $item->imagen_portada
                        ? \Illuminate\Support\Facades\Storage::url($item->imagen_portada)
                        : 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=900&q=80';
                    $badge = $estadoBadge($item->estado ?? 'borrador');
                @endphp
                <article class="rounded-3xl border border-white/10 bg-zinc-950/75 p-4 shadow-[0_12px_45px_rgba(0,0,0,0.35)] ring-1 ring-indigo-500/10 flex flex-col gap-4">
                    <div class="relative overflow-hidden rounded-xl border border-white/10 bg-zinc-900/70 aspect-[4/3]">
                        <img src="{{ $imagenPortada }}" alt="Portada del proyecto {{ $item->titulo }}" class="h-full w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                        <div class="absolute top-3 left-3 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-[11px] font-semibold border {{ $badge['bg'] }}">
                                {{ $badge['label'] }}
                            </span>
                            @if ($item->categoria)
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-500 text-amber-950 px-3 py-1 text-[11px] font-extrabold shadow-lg shadow-amber-500/30">
                                    {{ ucfirst($item->categoria) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-2 flex-1">
                        <h3 class="text-xl font-bold text-white leading-tight line-clamp-2">
                            {{ $item->titulo }}
                        </h3>
                        <p class="text-[13px] text-zinc-300 line-clamp-2">
                            {{ \Illuminate\Support\Str::limit($item->descripcion_corta ?? $item->descripcion_proyecto, 110) }}
                        </p>
                        <p class="text-[12px] text-zinc-500">
                            Creado: <span class="text-zinc-200 font-medium">{{ $item->created_at?->format('d/m/Y') ?? 'N/D' }}</span>
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
                                <p class="font-semibold text-white text-base">${{ number_format($item->meta_financiacion ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('auditor.proyectos.show', $item) }}"
                           class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-indigo-600 px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-white shadow-[0_10px_24px_rgba(79,70,229,0.4)] hover:bg-indigo-500 transition-colors">
                            Inspeccionar
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </article>
            @empty
                <p class="text-sm text-zinc-500">No hay proyectos registrados.</p>
            @endforelse
        </div>

        <div>
            {{ $proyectos instanceof \Illuminate\Pagination\LengthAwarePaginator ? $proyectos->links() : '' }}
        </div>
    </div>
@endsection
