@extends('colaborador.layouts.panel')

@section('title', 'Resumen del proyecto')
@section('active', 'proyectos')
@section('back_url', route('colaborador.proyectos'))
@section('back_label', 'Volver a proyectos')

@section('content')
<div class="px-4 pt-6 pb-10 lg:px-8 space-y-8">
    @php
        $hero = $proyecto->imagen_portada
            ? \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada)
            : null;
    @endphp
    <section class="rounded-3xl border border-white/15 bg-[#030712] shadow-[0_24px_60px_rgba(0,0,0,0.55)] overflow-hidden">
        <div class="relative">
            @if ($hero)
                <div class="absolute inset-0">
                    <img src="{{ $hero }}" alt="Imagen del proyecto {{ $proyecto->titulo }}" class="h-full w-full object-cover opacity-60">
                    <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/70 to-zinc-900/70"></div>
                </div>
            @endif
            <div class="relative p-6 space-y-3 text-white">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="space-y-1">
                        <p class="text-xs uppercase tracking-[0.3em] text-sky-200">Proyecto</p>
                        <h1 class="text-2xl font-bold">{{ $proyecto->titulo }}</h1>
                        <p class="text-sm text-sky-100/80">Estado: <span class="font-semibold">{{ ucfirst($proyecto->estado ?? 'En progreso') }}</span></p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-black/30 px-4 py-3 text-sm text-indigo-100">
                        <p class="text-xs uppercase tracking-[0.2em] text-sky-200">Tu aporte acumulado</p>
                        <p class="text-2xl font-extrabold text-emerald-300">${{ number_format($aporteUsuario, 0, ',', '.') }}</p>
                        <a href="{{ route('colaborador.proyectos.aportar', $proyecto) }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-3 py-2 text-[12px] font-bold uppercase tracking-wide text-white shadow-[0_10px_28px_rgba(59,130,246,0.35)] hover:bg-sky-700 transition-colors mt-2">
                            Apoyar ahora
                        </a>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 text-sm text-indigo-100/90">
                    <div class="rounded-xl border border-white/15 bg-black/30 p-3">
                        <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-400">Meta</p>
                        <p class="text-lg font-semibold text-white">${{ number_format($proyecto->meta_financiacion ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-black/30 p-3">
                        <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-400">Recaudado</p>
                        <p class="text-lg font-semibold text-white">${{ number_format($proyecto->monto_recaudado ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-black/30 p-3">
                        <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-400">Modelo</p>
                        <p class="text-lg font-semibold text-white">{{ $proyecto->modelo_financiamiento ?? 'N/A' }}</p>
                    </div>
                    @php
                        $avgRating = round((float) ($proyecto->rating_promedio ?? 0), 1);
                        $ratingTotal = (int) ($proyecto->rating_total ?? 0);
                        $filled = (int) floor($avgRating);
                        $half = ($avgRating - $filled) >= 0.5;
                    @endphp
                    <div class="sm:col-span-2 lg:col-span-1 rounded-xl border border-white/15 bg-black/30 p-3 space-y-2">
                        <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-400">Calificación</p>
                        <div class="flex items-center gap-2 text-amber-300">
                            <div class="flex items-center gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $filled)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @elseif ($i === $filled + 1 && $half)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><defs><linearGradient id="half-r"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs><path fill="url(#half-r)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-zinc-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.21 2.91-3.105.23c-.833.062-1.17 1.107-.536 1.651l2.36 2.003-.711 3.03c-.191.813.691 1.456 1.405 1.028L10 12.347l2.765 1.39c.713.428 1.596-.215 1.405-1.028l-.711-3.03 2.36-2.003c.634-.544.297-1.589-.536-1.65l-3.105-.231-1.21-2.91z" clip-rule="evenodd"/></svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-white font-semibold">{{ $avgRating }} / 5</span>
                            <span class="text-zinc-400 text-[12px]">({{ $ratingTotal }})</span>
                        </div>
                        @if ($haAportado)
                            <form method="POST" action="{{ route('colaborador.proyectos.calificar', $proyecto) }}" class="space-y-2 mt-2">
                                @csrf
                                <label class="text-xs text-zinc-300">Califica este proyecto</label>
                                <div class="flex items-center gap-3">
                                    <select name="puntaje" class="rounded-lg border border-white/10 bg-zinc-900 px-3 py-2 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}" @selected(optional($calificacionUsuario)->puntaje == $i)>{{ $i }} ⭐</option>
                                        @endfor
                                    </select>
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-500 px-4 py-2 text-xs font-bold uppercase tracking-wide text-emerald-950 hover:bg-emerald-400 transition-colors">
                                        Guardar
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </button>
                                </div>
                                <textarea name="comentarios" rows="2" class="w-full rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400" placeholder="Deja un breve comentario (opcional)">{{ old('comentarios', optional($calificacionUsuario)->comentarios) }}</textarea>
                            </form>
                        @else
                            <p class="text-[12px] text-zinc-400">Aporta al proyecto para dejar tu calificación.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 bg-black/40 px-6 py-4 flex flex-wrap gap-2">
            <a href="{{ route('colaborador.proyectos.proveedores', $proyecto) }}" class="inline-flex items-center gap-2 rounded-lg border border-white/15 bg-white/5 px-3 py-2 text-xs font-semibold text-white hover:border-sky-400/60">
                Ver proveedores
            </a>
        </div>
    </section>

    <section class="space-y-3">
        <div class="grid gap-4 lg:grid-cols-2">
            <div class="space-y-3">
                <h2 class="text-sm font-semibold text-zinc-100 tracking-wide uppercase">
                    Hitos recientes
                </h2>
                @if ($proyecto->hitos->isEmpty())
                    <div class="rounded-2xl border border-indigo-500/20 bg-indigo-500/5 p-4 text-xs text-zinc-400">
                        Aún no hay hitos publicados para este proyecto.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($proyecto->hitos as $hito)
                            <article class="rounded-2xl border border-indigo-500/25 bg-indigo-500/5 p-4 space-y-1 shadow-[0_10px_24px_rgba(79,70,229,0.25)]">
                                <h3 class="text-sm font-semibold text-zinc-50">{{ $hito->titulo ?? 'Actualización' }}</h3>
                                <p class="text-[11px] text-zinc-400">{{ optional($hito->created_at)->format('d/m/Y H:i') }}</p>
                                <p class="text-xs text-zinc-200">{{ $hito->descripcion ?? $hito->contenido }}</p>
                                @if (!empty($hito->adjuntos))
                                    <div class="mt-2 space-y-1">
                                        <p class="text-[11px] uppercase tracking-[0.18em] text-zinc-400">Adjuntos</p>
                                        <div class="flex flex-wrap gap-2 text-[12px] text-emerald-200">
                                            @foreach ($hito->adjuntos as $adj)
                                                <a href="{{ \Illuminate\Support\Facades\Storage::url($adj) }}" target="_blank" class="inline-flex items-center gap-1 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 hover:border-emerald-400 hover:text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                                                    </svg>
                                                    Ver adjunto
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="space-y-3">
                <h2 class="text-sm font-semibold text-zinc-100 tracking-wide uppercase">Comentarios y calificaciones</h2>
                @php
                    $calificacionesResumen = $proyecto->calificaciones ?? collect();
                @endphp
                @if ($calificacionesResumen->isEmpty())
                    <div class="rounded-2xl border border-emerald-500/25 bg-emerald-500/5 p-4 text-xs text-zinc-400">
                        Aún no hay calificaciones para este proyecto.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($calificacionesResumen as $cal)
                            @php $stars = (int) $cal->puntaje; @endphp
                            <article class="rounded-2xl border border-emerald-500/25 bg-emerald-500/5 p-4 space-y-2 shadow-[0_10px_24px_rgba(16,185,129,0.25)]">
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
                                    <p class="text-sm text-zinc-200">{{ $cal->comentarios }}</p>
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
