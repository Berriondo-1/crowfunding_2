@extends('colaborador.layouts.panel')

@section('title', 'Perfil del creador')
@section('active', 'proyectos')
@section('back_url', route('colaborador.proyectos'))
@section('back_label', 'Volver a proyectos')

@section('content')
@php
    $foto = $creador->foto_perfil
        ? \Illuminate\Support\Facades\Storage::url($creador->foto_perfil)
        : 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=800&q=80';
@endphp
<div class="px-4 pt-6 pb-10 lg:px-8 space-y-8">
    <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-800/60 via-slate-900/70 to-slate-900/70 p-6 lg:p-8 shadow-2xl ring-1 ring-emerald-500/15">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-4">
                <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl border border-white/15 bg-white/5">
                    <img src="{{ $foto }}" alt="Foto del creador" class="h-full w-full object-cover">
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-200/80">
                        {{ $creador->estado_verificacion ? 'Creador verificado' : 'Creador en verificación' }}
                    </p>
                    <h1 class="text-2xl font-bold text-white">{{ $creador->nombre_completo ?? $creador->name }}</h1>
                    @if ($creador->profesion)
                        <p class="text-sm text-emerald-100/90">{{ $creador->profesion }}</p>
                    @endif
                    @if ($creador->experiencia)
                        <p class="text-xs text-emerald-50/70">Experiencia: {{ $creador->experiencia }}</p>
                    @endif
                </div>
            </div>
            <div class="grid w-full gap-3 sm:grid-cols-2 lg:w-auto lg:grid-cols-4">
                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <p class="text-[11px] uppercase text-zinc-400">Proyectos publicados</p>
                    <p class="text-xl font-semibold text-white">{{ $metrics['proyectos'] }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <p class="text-[11px] uppercase text-zinc-400">Meta acumulada</p>
                    <p class="text-xl font-semibold text-emerald-200">${{ number_format($metrics['meta'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <p class="text-[11px] uppercase text-zinc-400">Recaudado</p>
                    <p class="text-xl font-semibold text-emerald-200">${{ number_format($metrics['recaudado'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <p class="text-[11px] uppercase text-zinc-400">Colaboradores</p>
                    <p class="text-xl font-semibold text-white">{{ $metrics['colaboradores'] }}</p>
                </div>
            </div>
        </div>
        <div class="mt-4 grid gap-4 lg:grid-cols-[1.2fr,0.8fr]">
            <div class="rounded-2xl border border-white/10 bg-black/30 p-4 text-sm text-zinc-200 space-y-2">
                <p class="font-semibold text-white">Sobre el creador</p>
                <p class="text-zinc-300">{{ $creador->biografia ?? $creador->info_personal ?? 'Sin biografía disponible.' }}</p>
                <div class="grid gap-2 sm:grid-cols-2 text-xs text-zinc-300">
                    <div>
                        <p class="text-[11px] uppercase text-zinc-500">Correo</p>
                        <p class="font-semibold text-white">{{ $creador->email }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase text-zinc-500">Verificación</p>
                        <p class="font-semibold {{ $creador->estado_verificacion ? 'text-emerald-200' : 'text-amber-200' }}">
                            {{ $creador->estado_verificacion ? 'Verificado' : 'Pendiente' }}
                        </p>
                    </div>
                    @if ($creador->indice_confianza)
                        <div>
                            <p class="text-[11px] uppercase text-zinc-500">Índice de confianza</p>
                            <p class="font-semibold text-emerald-200">{{ $creador->indice_confianza }} / 100</p>
                        </div>
                    @endif
                    @if ($creador->experiencia)
                        <div>
                            <p class="text-[11px] uppercase text-zinc-500">Experiencia</p>
                            <p class="font-semibold text-white">{{ $creador->experiencia }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="rounded-2xl border border-white/10 bg-black/30 p-4">
                <p class="font-semibold text-white text-sm mb-2">Redes y enlaces</p>
                @if (!empty($creador->redes_sociales))
                    <div class="flex flex-wrap gap-2 text-xs text-emerald-100">
                        @foreach ($creador->redes_sociales as $label => $url)
                            <a href="{{ $url }}" target="_blank" class="inline-flex items-center gap-2 rounded-full border border-emerald-400/40 bg-emerald-500/10 px-3 py-1 hover:border-emerald-300">
                                <span class="font-semibold">{{ ucfirst($label) }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6h8m0 0v8m0-8L5 19" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-zinc-400">El creador aún no ha agregado redes.</p>
                @endif
            </div>
        </div>
    </section>

    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-zinc-400">Proyectos del creador</p>
                <h2 class="text-xl font-bold text-white">Portafolio publicado</h2>
            </div>
        </div>

        @if ($proyectos->isEmpty())
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 text-sm text-zinc-400">
                Este creador aún no tiene proyectos publicados.
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($proyectos as $proyecto)
                    @php
                        $meta = $proyecto->meta_financiacion ?: 1;
                        $recaudado = $proyecto->monto_recaudado ?? 0;
                        $progreso = $meta > 0 ? min(100, round(($recaudado / $meta) * 100)) : 0;
                        $img = $proyecto->imagen_portada
                            ? \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada)
                            : 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=80';
                    @endphp
                    <article class="rounded-2xl border border-white/10 bg-zinc-900/70 overflow-hidden shadow-[0_16px_40px_rgba(0,0,0,0.35)] flex flex-col">
                        <div class="h-32 w-full overflow-hidden">
                            <img src="{{ $img }}" alt="Proyecto" class="h-full w-full object-cover">
                        </div>
                        <div class="p-4 flex-1 flex flex-col gap-3 text-sm text-zinc-200">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="text-base font-semibold text-white">{{ $proyecto->titulo }}</h3>
                                <span class="rounded-full border border-emerald-400/30 bg-emerald-500/10 px-2 py-0.5 text-[11px] font-semibold text-emerald-200">{{ ucfirst($proyecto->estado) }}</span>
                            </div>
                            <p class="text-xs text-zinc-400 line-clamp-2">{{ $proyecto->descripcion_proyecto }}</p>
                            <div class="h-2 w-full rounded-full bg-white/5 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-sky-400 via-cyan-400 to-emerald-400" style="width: {{ $progreso }}%;"></div>
                            </div>
                            <div class="flex flex-wrap items-center gap-4 text-xs text-zinc-300">
                                <span class="font-semibold text-white">USD {{ number_format($recaudado, 0, ',', '.') }}</span>
                                <span>Meta: USD {{ number_format($meta, 0, ',', '.') }}</span>
                                <span>Rating: {{ number_format($proyecto->rating_promedio ?? 0, 1) }} ({{ $proyecto->rating_total }})</span>
                            </div>
                            <div class="mt-auto flex justify-end">
                                <a href="{{ route('colaborador.proyectos.show', $proyecto) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-500 px-3 py-2 text-[11px] font-bold uppercase tracking-wide text-emerald-950 hover:bg-emerald-400">
                                    Ver proyecto
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
</div>
@endsection
