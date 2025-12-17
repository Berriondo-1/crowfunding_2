@extends('colaborador.layouts.panel')

@section('title', 'Detalle del proyecto')
@section('active', 'proyectos')
@section('back_url', route('colaborador.dashboard'))
@section('back_label', 'Volver al panel')

@section('content')
<div class="px-4 pt-6 pb-10 lg:px-8 space-y-8">

    {{-- Header proyecto --}}
    @php
        $meta = $proyecto->meta_financiacion ?: 1;
        $recaudado = $proyecto->monto_recaudado ?? 0;
        $progreso = $meta > 0 ? min(100, round(($recaudado / $meta) * 100)) : 0;
        $hero = $proyecto->imagen_portada
            ? \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada)
            : 'https://images.unsplash.com/photo-1471879832106-c7ab9e0cee23?auto=format&fit=crop&w=1200&q=80';
    @endphp
    <section class="rounded-2xl border border-white/10 bg-zinc-950/80 shadow-[0_24px_60px_rgba(0,0,0,0.5)] overflow-hidden">
        {{-- Hero image --}}
        <div class="relative aspect-[16/9] w-full overflow-hidden">
            <img src="{{ $hero }}" alt="Imagen del proyecto {{ $proyecto->titulo }}" class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6 flex flex-col gap-4 text-white">
                <div class="flex flex-wrap items-center gap-2">
                    @if ($proyecto->categoria)
                        <span class="inline-flex items-center rounded-full bg-indigo-500/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide">
                            {{ ucfirst($proyecto->categoria) }}
                        </span>
                    @endif
                    @if ($proyecto->modelo_financiamiento)
                        <span class="inline-flex items-center rounded-full bg-emerald-500/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide">
                            {{ ucfirst($proyecto->modelo_financiamiento) }}
                        </span>
                    @endif
                    @if ($proyecto->fecha_limite)
                        <span class="inline-flex items-center rounded-full bg-black/60 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide border border-white/10">
                            Fecha límite: {{ optional($proyecto->fecha_limite)->format('d/m/Y') }}
                        </span>
                    @endif
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold leading-tight max-w-3xl">
                    {{ $proyecto->titulo }}
                </h1>
                <p class="text-sm sm:text-base text-indigo-50/90 max-w-3xl">
                    {{ $proyecto->descripcion_proyecto }}
                </p>
                <p class="text-[12px] text-indigo-100/80">
                    Por
                    <span class="font-semibold text-white">
                        {{ $proyecto->creador->nombre_completo ?? $proyecto->creador->name ?? 'Creador' }}
                    </span>
                </p>
                <div>
                    <a href="{{ route('colaborador.creadores.show', $proyecto->creador) }}" class="inline-flex items-center gap-2 rounded-lg border border-white/15 bg-white/10 px-3 py-1.5 text-[12px] font-semibold text-white hover:border-emerald-400/60 hover:text-emerald-100">
                        Ver creador
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('colaborador.proyectos.aportar', $proyecto) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-500 px-4 py-2.5 text-sm font-bold uppercase tracking-wide text-emerald-950 shadow-[0_12px_35px_rgba(16,185,129,0.35)] hover:bg-emerald-400 transition-colors">
                        Apoyar este proyecto
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Progreso y aporte --}}
        <div class="grid gap-4 p-6 lg:grid-cols-[2fr_1fr] bg-black/40">
            @php
                $avgRating = round((float) ($proyecto->rating_promedio ?? 0), 1);
                $ratingTotal = (int) ($proyecto->rating_total ?? 0);
                $filledStars = (int) floor($avgRating);
                $halfStar = ($avgRating - $filledStars) >= 0.5;
            @endphp
            <div class="space-y-4">
                <div class="flex items-center justify-between text-sm text-indigo-50/90">
                    <span class="font-semibold">Progreso del proyecto</span>
                    <span class="text-lg font-bold text-sky-200">
                        {{ $progreso }}%
                        @if ($progreso >= 100)
                            <span class="ml-2 inline-flex items-center rounded-full bg-emerald-500/20 px-2 py-0.5 text-[11px] font-semibold text-emerald-200 border border-emerald-400/40">
                                Proyecto financiado
                            </span>
                        @endif
                    </span>
                </div>
                <div class="h-3 w-full rounded-full bg-zinc-900/80 overflow-hidden ring-1 ring-white/10">
                    <div class="h-full rounded-full bg-gradient-to-r from-sky-400 via-cyan-400 to-emerald-400 shadow-[0_0_12px_rgba(56,189,248,0.35)]"
                         style="width: {{ $progreso }}%;"></div>
                </div>
                <div class="flex flex-wrap items-center gap-6 text-sm text-indigo-50/90">
                    <div class="space-y-0.5">
                        <p class="text-[11px] uppercase tracking-wide text-indigo-100/70">Recaudado</p>
                        <p class="text-lg font-semibold text-white">${{ number_format($recaudado, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-0.5">
                        <p class="text-[11px] uppercase tracking-wide text-indigo-100/70">Meta</p>
                        <p class="text-lg font-semibold text-white">${{ number_format($meta, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-0.5">
                        <p class="text-[11px] uppercase tracking-wide text-indigo-100/70">Estado</p>
                        <p class="text-sm font-semibold text-emerald-200">{{ ucfirst($proyecto->estado ?? 'Activo') }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <div id="apoyar" class="rounded-2xl border border-white/10 bg-zinc-900/80 p-4 space-y-3 shadow-[0_18px_40px_rgba(0,0,0,0.45)]">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-100/70">
                        Tu aporte a este proyecto
                    </p>
                    <p class="text-3xl font-extrabold text-emerald-300">
                        ${{ number_format($aporteUsuario, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-indigo-50/80">
                        Puedes decidir aumentar tu aporte si este proyecto te inspira.
                    </p>
                    <a href="{{ route('colaborador.aportaciones') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-500 px-4 py-2.5 text-sm font-bold uppercase tracking-wide text-white shadow-[0_10px_28px_rgba(79,70,229,0.35)] hover:bg-indigo-400 transition-colors">
                        Revisar mis aportes
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="rounded-2xl border border-white/10 bg-zinc-900/80 p-4 space-y-3 shadow-[0_18px_40px_rgba(0,0,0,0.45)]">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-100/70">Calificación</p>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1 text-amber-300">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $filledStars)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @elseif ($i === $filledStars + 1 && $halfStar)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><defs><linearGradient id="half"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs><path fill="url(#half)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-zinc-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.21 2.91-3.105.23c-.833.062-1.17 1.107-.536 1.651l2.36 2.003-.711 3.03c-.191.813.691 1.456 1.405 1.028L10 12.347l2.765 1.39c.713.428 1.596-.215 1.405-1.028l-.711-3.03 2.36-2.003c.634-.544.297-1.589-.536-1.65l-3.105-.231-1.21-2.91z" clip-rule="evenodd"/></svg>
                                        @endif
                                    @endfor
                                </div>
                                <div class="text-sm text-indigo-50/80 font-semibold">{{ $avgRating }} / 5</div>
                            </div>
                            <p class="text-[12px] text-zinc-400">{{ $ratingTotal }} calificaciones</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Top aportantes --}}
    <section class="space-y-3">
        <div class="rounded-2xl border border-[#1F2937] bg-[#0B1220] p-4 shadow-[0_8px_24px_rgba(0,0,0,0.35)]">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-[#111827]">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.3em] text-zinc-400">Top 5 aportantes</p>
                    <p class="text-xs text-zinc-500">Últimos 30 días</p>
                </div>
                <a href="{{ route('colaborador.aportaciones', ['proyecto' => $proyecto->titulo]) }}" class="text-[12px] font-semibold text-indigo-200 hover:text-white">
                    Ver todos
                </a>
            </div>

            @if ($topAportantes->isEmpty())
                <div class="rounded-xl border border-white/10 bg-black/40 px-3 py-3 text-xs text-zinc-400 mt-3">
                    Aún no hay aportes registrados en este proyecto.
                </div>
            @else
                @php
                    $topMax = max($topAportantes->pluck('total')->all() ?: [1]);
                @endphp
                <div class="divide-y divide-[#111827]">
                    @foreach ($topAportantes as $rank => $ap)
                        @php
                            $col = $ap->colaborador;
                            $nombre = $col->nombre_completo ?? $col->name ?? 'Colaborador #'.$ap->colaborador_id;
                            $badgeColors = [
                                0 => 'bg-amber-500/20 text-amber-100 border border-amber-400/40',
                                1 => 'bg-slate-400/20 text-slate-100 border border-slate-300/40',
                                2 => 'bg-orange-400/20 text-orange-100 border border-orange-300/40',
                            ];
                            $progressColors = [
                                0 => 'bg-amber-400',
                                1 => 'bg-slate-300',
                                2 => 'bg-orange-400',
                            ];
                            $pill = $badgeColors[$rank] ?? 'bg-indigo-500/15 text-indigo-100 border border-indigo-400/30';
                            $fill = $progressColors[$rank] ?? 'bg-indigo-500';
                            $percent = $topMax ? min(100, round(($ap->total / $topMax) * 100)) : 0;
                            $avatar = $col?->foto_perfil ? \Illuminate\Support\Facades\Storage::url($col->foto_perfil) : null;
                            $initials = collect(explode(' ', $nombre))->map(fn($p) => mb_substr($p, 0, 1))->implode('');
                        @endphp
                        <div class="py-3 px-1 flex flex-col gap-2 hover:bg-indigo-500/5 rounded-lg transition-colors {{ $rank === 0 ? 'bg-[linear-gradient(90deg,rgba(251,191,36,0.12),transparent_55%)]' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full {{ $pill }} text-[11px] font-semibold">#{{ $rank + 1 }}</span>
                                <div class="h-10 w-10 rounded-full overflow-hidden border border-white/10 bg-white/5 flex items-center justify-center text-sm font-semibold text-white">
                                    @if ($avatar)
                                        <img src="{{ $avatar }}" alt="Avatar" class="h-full w-full object-cover">
                                    @else
                                        {{ strtoupper($initials) }}
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-white flex items-center gap-1">
                                        {{ $nombre }}
                                        @if ($rank === 0)
                                            <span class="text-amber-300 text-xs">👑</span>
                                        @elseif ($rank === 1)
                                            <span class="text-slate-200 text-xs">⭐</span>
                                        @elseif ($rank === 2)
                                            <span class="text-orange-200 text-xs">⭐</span>
                                        @endif
                                    </p>
                                    <p class="text-[12px] text-zinc-400">{{ $ap->aportes }} aporte{{ $ap->aportes === 1 ? '' : 's' }}</p>
                                </div>
                                <p class="text-sm font-semibold text-emerald-200">${{ number_format($ap->total, 2, ',', '.') }}</p>
                            </div>
                            <div class="h-1.5 rounded-full bg-[#111827] overflow-hidden">
                                <div class="h-full rounded-full {{ $fill }}" style="width: {{ $percent }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Recompensas --}}
    <section class="space-y-3">
        <h2 class="text-sm font-semibold text-zinc-100 tracking-wide uppercase">
            Recompensas disponibles
        </h2>

        @if ($proyecto->recompensas->isEmpty())
            <div class="rounded-2xl border border-white/5 bg-black/40 p-4 text-xs text-zinc-400">
                Este proyecto no tiene recompensas configuradas por el momento.
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($proyecto->recompensas as $recompensa)
                    @php
                        $recompensaImg = $proyecto->imagen_portada
                            ? \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada)
                            : 'https://images.unsplash.com/photo-1518779578993-ec3579fee39f?auto=format&fit=crop&w=600&q=80';
                    @endphp
                    <article class="rounded-2xl border border-white/10 bg-zinc-900/70 overflow-hidden shadow-[0_16px_40px_rgba(0,0,0,0.35)] flex flex-col">
                        <div class="h-28 w-full overflow-hidden">
                            <img src="{{ $recompensaImg }}" alt="Recompensa" class="h-full w-full object-cover">
                        </div>
                        <div class="p-4 space-y-2 text-sm text-zinc-200 flex-1 flex flex-col">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-indigo-200">
                                A partir de
                                <span class="text-emerald-300">
                                    ${{ number_format($recompensa->monto_minimo ?? $recompensa->monto_minimo_aportacion ?? 0, 0, ',', '.') }}
                                </span>
                            </p>
                            <h3 class="text-sm font-semibold text-white">
                                {{ $recompensa->titulo ?? 'Recompensa' }}
                            </h3>
                            <p class="text-xs text-zinc-300 flex-1">
                                {{ $recompensa->descripcion ?? 'Recompensa por tu apoyo a este proyecto.' }}
                            </p>
                            <div class="flex justify-end">
                                <a href="#apoyar" class="inline-flex items-center gap-2 rounded-md bg-emerald-500 px-3 py-2 text-[11px] font-bold uppercase tracking-wide text-emerald-950 hover:bg-emerald-400 transition-colors">
                                    Seleccionar recompensa
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Hitos / Calificaciones --}}
    <section class="space-y-3">
        <div class="grid gap-4 lg:grid-cols-2">
            <div class="space-y-3">
                <h2 class="text-sm font-semibold text-zinc-100 tracking-wide uppercase">
                    Hitos y actualizaciones
                </h2>

                @if ($proyecto->hitos->isEmpty())
                    <div class="rounded-2xl border border-white/5 bg-black/40 p-4 text-xs text-zinc-400">
                        El creador aún no ha publicado hitos o actualizaciones para este proyecto.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($proyecto->hitos as $hito)
                            @php $adjuntosCount = count($hito->adjuntos ?? []); @endphp
                            <article class="relative overflow-hidden rounded-2xl border border-emerald-400/25 bg-gradient-to-r from-emerald-500/10 via-indigo-500/10 to-sky-500/10 p-4 shadow-[0_12px_30px_rgba(16,185,129,0.18)] ring-1 ring-emerald-400/15 space-y-3">
                                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-400 via-sky-300 to-indigo-300"></div>
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-emerald-200">&#9733;</span>
                                            <p class="text-sm font-semibold text-white">{{ $hito->titulo ?? 'Actualizacion' }}</p>
                                        </div>
                                        <p class="text-xs text-zinc-300">{{ optional($hito->created_at)->format('d/m/Y H:i') }}</p>
                                        <p class="text-sm text-zinc-100/90">{{ \Illuminate\Support\Str::limit($hito->descripcion ?? $hito->contenido ?? 'Sin descripcion', 160) }}</p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-[11px] font-semibold bg-emerald-500/15 text-emerald-100 border border-emerald-400/30">Hito cumplido</span>
                                </div>
                                <div class="flex items-center justify-between text-xs text-zinc-300">
                                    <p class="{{ $adjuntosCount ? 'text-emerald-200' : 'text-zinc-500' }}">
                                        {{ $adjuntosCount ? "{$adjuntosCount} archivos adjuntos" : 'Sin adjuntos' }}
                                    </p>
                                </div>
                                <div class="rounded-xl border border-white/10 bg-zinc-900/70 px-3 py-2 text-xs text-zinc-200">
                                    <p class="text-[11px] text-zinc-400 font-semibold">Adjuntos</p>
                                    @if ($adjuntosCount)
                                        <div class="mt-1 flex flex-wrap gap-2">
                                            @foreach ($hito->adjuntos as $idx => $archivo)
                                                <a href="{{ \Illuminate\Support\Facades\Storage::url($archivo) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-emerald-400/40 bg-emerald-500/10 px-3 py-1 text-[11px] font-semibold text-white hover:border-emerald-300/70">
                                                    Archivo {{ $idx + 1 }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="mt-1 text-xs text-zinc-500">Sin adjuntos</p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="space-y-3">
                <h2 class="text-sm font-semibold text-zinc-100 tracking-wide uppercase">
                    Comentarios y calificaciones
                </h2>
                @if ($calificaciones->isEmpty())
                    <div class="rounded-2xl border border-white/5 bg-black/40 p-4 text-xs text-zinc-400">
                        Aún no hay calificaciones para este proyecto.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($calificaciones as $cal)
                            @php
                                $stars = (int) $cal->puntaje;
                            @endphp
                            <article class="rounded-2xl border border-white/5 bg-black/40 p-4 space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <div>
                                        <p class="text-sm font-semibold text-white">
                                            {{ $cal->colaborador->nombre_completo ?? $cal->colaborador->name ?? 'Colaborador' }}
                                        </p>
                                        <p class="text-[11px] text-zinc-500">
                                            {{ optional($cal->fecha_calificacion ?? $cal->created_at)->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-1 text-amber-300">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $i <= $stars ? 'fill-current' : 'text-zinc-600' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                </div>
                                @if ($cal->comentarios)
                                    <p class="text-sm text-zinc-200">
                                        {{ $cal->comentarios }}
                                    </p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
