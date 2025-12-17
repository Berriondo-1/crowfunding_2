@extends('creator.layouts.panel')

@section('title', 'Avances y actualizaciones')
@section('active', 'avances')

@section('content')
    @php
        $estadoBadge = fn($estado) => match($estado) {
            'borrador' => ['label' => 'Borrador', 'classes' => 'bg-amber-500/15 text-amber-200 border-amber-400/40'],
            'publicado' => ['label' => 'Publicado', 'classes' => 'bg-emerald-500/15 text-emerald-200 border-emerald-400/40'],
            'finalizado' => ['label' => 'Finalizado', 'classes' => 'bg-sky-500/15 text-sky-200 border-sky-400/40'],
            default => ['label' => ucfirst($estado ?? 'N/D'), 'classes' => 'bg-zinc-500/15 text-zinc-200 border-white/10'],
        };
        $hasMultipleProjects = $proyectos->count() > 1;
    @endphp

    @if (session('status'))
        <div class="rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="rounded-2xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-100">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-600/25 via-zinc-900/70 to-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Proyectos</p>
                <h2 class="text-2xl font-bold text-white">Gestiona avances y hitos</h2>
                <p class="text-sm text-zinc-400">Selecciona el proyecto y publica actualizaciones destacando los hitos relevantes.</p>
            </div>
            <div>
                @if ($hasMultipleProjects)
                    <form method="GET" action="{{ route('creador.avances') }}" class="flex flex-wrap items-center gap-2">
                        <label class="text-xs text-zinc-400 mr-2">Proyecto</label>
                        <select name="proyecto" class="rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                            @foreach ($proyectos as $proyecto)
                                <option value="{{ $proyecto->id }}" @selected($selectedProjectId == $proyecto->id)>{{ $proyecto->titulo }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500">
                            Aplicar
                        </button>
                    </form>
                @else
                    <p class="text-sm text-white/90">Proyecto activo: <span class="font-semibold">{{ $selectedProject->titulo ?? 'Proyecto' }}</span></p>
                    <a href="{{ route('creador.proyectos') }}" class="text-xs text-emerald-200 hover:text-white">Cambiar proyecto</a>
                @endif
            </div>
        </div>
        @if ($projectContext)
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-zinc-900/60 p-4">
                    <p class="text-[11px] uppercase text-zinc-400">Estado</p>
                    @php $badge = $estadoBadge($projectContext['estado']); @endphp
                    <span class="inline-flex items-center gap-1 rounded-full border px-3 py-1 text-[11px] font-semibold {{ $badge['classes'] }}">
                        {{ $badge['label'] }}
                    </span>
                </div>
                <div class="rounded-2xl border border-white/10 bg-zinc-900/60 p-4">
                    <p class="text-[11px] uppercase text-zinc-400">Recaudado vs meta</p>
                    <p class="text-lg font-semibold text-white">${{ number_format($projectContext['recaudado'],0,',','.') }} de ${{ number_format($projectContext['meta'],0,',','.') }}</p>
                    <div class="mt-2 h-2 rounded-full bg-white/10">
                        <div class="h-2 rounded-full bg-emerald-400" style="width: {{ min(100, $projectContext['progreso']) }}%;"></div>
                    </div>
                    <p class="text-[11px] text-zinc-500 mt-1">{{ $projectContext['progreso'] }}% completado</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-zinc-900/60 p-4">
                    <p class="text-[11px] uppercase text-zinc-400">Próximo hito</p>
                    <p class="text-sm text-white">{{ $projectContext['hito'] }}</p>
                </div>
            </div>
        @endif
    </section>

    @if ($proyectos->isEmpty())
        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 text-sm text-zinc-300 shadow-2xl">
            No tienes proyectos creados aún. <a class="text-indigo-300 underline" href="{{ route('creador.proyectos') }}">Crea un proyecto</a> para comenzar a publicar avances.
        </section>
    @else
        @php
            $cronogramaHitos = collect($selectedProject->cronograma ?? [])->filter(fn($h) => is_array($h))->values();
            $totalCronograma = $cronogramaHitos->count();
            $hitosCumplidosCount = $actualizaciones->where('es_hito', true)->count();
            $progresoHitos = $totalCronograma > 0 ? min(100, round(($hitosCumplidosCount / $totalCronograma) * 100)) : 0;
        @endphp
        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-5 shadow-2xl ring-1 ring-emerald-500/10 space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Progreso de hitos</p>
                    <h3 class="text-lg font-bold text-white">Cumplimiento del cronograma</h3>
                    <p class="text-sm text-zinc-400">Hitos planificados vs cumplidos.</p>
                </div>
                <div class="rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-2 text-sm text-emerald-100 font-semibold">
                    {{ $progresoHitos }}% @if($totalCronograma) ({{ $hitosCumplidosCount }} de {{ $totalCronograma }}) @endif
                </div>
            </div>
            <div class="space-y-3">
                <div class="h-2 w-full rounded-full bg-zinc-900/80 overflow-hidden ring-1 ring-white/10">
                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 via-lime-300 to-amber-300 shadow-[0_0_12px_rgba(74,222,128,0.45)]" style="width: {{ $progresoHitos }}%;"></div>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-3 text-xs text-zinc-200 space-y-1">
                        <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-400">Hitos planificados</p>
                        @if($cronogramaHitos->isEmpty())
                            <p class="text-zinc-500">Cronograma no definido.</p>
                        @else
                            <ul class="space-y-1">
                                @foreach ($cronogramaHitos as $h)
                                    <li class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center justify-center rounded-full bg-emerald-500/15 text-emerald-200 border border-emerald-400/30 px-2 py-0.5 text-[11px] font-semibold">
                                                Hito {{ $h['numero'] ?? $loop->iteration }}
                                            </span>
                                            <span class="text-sm text-white">{{ $h['titulo'] ?? 'Hito' }}</span>
                                        </div>
                                        <span class="text-[11px] text-zinc-400">{{ $h['fecha'] ?? 'Sin fecha' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-3 text-xs text-zinc-200 space-y-1">
                        <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-400">Hitos cumplidos</p>
                        @php $hitosCumplidos = $actualizaciones->where('es_hito', true); @endphp
                        @if($hitosCumplidos->isEmpty())
                            <p class="text-zinc-500">A�n no se han marcado hitos cumplidos.</p>
                        @else
                            <ul class="space-y-1">
                                @foreach ($hitosCumplidos as $h)
                                    <li class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-emerald-200">&#9733;</span>
                                            <span class="text-sm text-white">{{ $h->titulo }}</span>
                                        </div>
                                        <span class="text-[11px] text-zinc-400">{{ optional($h->fecha_publicacion ?? $h->created_at)->format('d/m/Y H:i') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <div class="grid gap-6 lg:grid-cols-[1.05fr,1.15fr]">
            <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-indigo-500/10 space-y-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Publicar actualización</p>
                    <h3 class="text-lg font-semibold text-white">Publica un nuevo avance</h3>
                    <p class="text-sm text-zinc-400">Comparte avances, marca hitos y añade evidencias.</p>
                </div>

                <form method="POST" action="{{ route('creador.proyectos.avances', $selectedProjectId) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="es_hito" id="es-hito-flag" value="{{ old('es_hito', 0) }}">
                    <div>
                        <label class="text-sm font-semibold text-white">Título *</label>
                        <input required name="titulo" value="{{ old('titulo') }}" class="mt-1 w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Ej. Entregamos el primer lote a los backers">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-white">Contenido</label>
                        <textarea name="contenido" rows="4" class="mt-1 w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Comparte avances, bloqueos o pr?ximos pasos...">{{ old('contenido') }}</textarea>
                    </div>
                    @php
                        $cronogramaHitos = collect($selectedProject->cronograma ?? [])->filter(fn($h) => is_array($h))->values();
                    @endphp
                    @if ($cronogramaHitos->isNotEmpty())
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-white">Marcar hito del cronograma</label>
                            <select name="cronograma_hito" id="cronograma-select" class="mt-1 w-full rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400">
                                <option value="">Selecciona un hito (opcional)</option>
                                @foreach ($cronogramaHitos as $hito)
                                    @php
                                        $label = ($hito['numero'] ?? '') ? 'Hito '.$hito['numero'].' - ' : '';
                                        $label .= $hito['titulo'] ?? 'Hito';
                                        if (!empty($hito['fecha'])) {
                                        $label .= ' - ' . $hito['fecha'];
                                        }
                                        $value = $hito['titulo'] ?? ($hito['numero'] ?? 'Hito');
                                    @endphp
                                    <option value="{{ $value }}" @selected(old('cronograma_hito') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-zinc-400">Si eliges un hito, se marcara como cumplido y se usara como titulo si el campo titulo esta vacio.</p>
                        </div>
                    @endif
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-white">Adjuntos (arrastra o selecciona archivos)</label>
                        <label for="adjuntos-nuevo" class="group flex flex-col gap-2 rounded-xl border-2 border-dashed border-emerald-400/40 bg-emerald-500/5 px-4 py-5 text-sm text-emerald-50 cursor-pointer hover:border-emerald-400 transition">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-100 border border-emerald-500/40">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-semibold text-white">Arrastra o haz clic para seleccionar</p>
                                    <p class="text-[12px] text-emerald-200/80" data-file-label="avance-nuevo">Sin archivos seleccionados</p>
                                </div>
                            </div>
                            <p class="text-[12px] text-emerald-200/70">Opcional. Máx. 8MB por archivo.</p>
                            <input id="adjuntos-nuevo" type="file" name="adjuntos[]" multiple class="hidden" data-file-preview="avance-nuevo">
                        </label>
                        <div class="flex flex-wrap gap-2 text-xs text-zinc-300" data-file-list="avance-nuevo"></div>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-900/40 hover:bg-emerald-500">
                            Publicar avance
                        </button>
                    </div>
                </form>
            </section>

            <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-emerald-500/10 flex flex-col space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Historial</p>
                        <h3 class="text-lg font-bold text-white">Últimas actualizaciones</h3>
                    </div>
                    <div class="flex gap-2 text-xs text-zinc-300">
                        <span>Más recientes arriba</span>
                    </div>
                </div>

                <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-1">
                    @forelse ($actualizaciones as $actualizacion)
                        @php
                            $badge = $actualizacion->es_hito
                                ? ['label' => 'Hito cumplido', 'classes' => 'bg-emerald-500/15 text-emerald-100 border border-emerald-400/30']
                                : ['label' => 'Actualización', 'classes' => 'bg-indigo-500/15 text-indigo-100 border border-indigo-400/30'];
                            $adjuntosCount = count($actualizacion->adjuntos ?? []);
                        @endphp
                        <article class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-inner ring-1 ring-indigo-500/10 space-y-3">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        @if($actualizacion->es_hito)
                                            <span class="text-sm text-emerald-200">⭐</span>
                                        @endif
                                        <p class="text-sm font-semibold text-white">{{ $actualizacion->titulo }}</p>
                                    </div>
                                    <p class="text-xs text-zinc-400">{{ $actualizacion->fecha_publicacion?->format('d/m/Y H:i') }}</p>
                                    <p class="text-sm text-zinc-300">{{ \Illuminate\Support\Str::limit($actualizacion->contenido ?? 'Sin descripción', 120) }}</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $badge['classes'] }}">{{ $badge['label'] }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-zinc-400">
                                <p class="{{ $adjuntosCount ? 'text-zinc-200' : 'text-zinc-500' }}">
                                    {{ $adjuntosCount ? "{$adjuntosCount} archivos adjuntos" : 'Sin adjuntos' }}
                                </p>
                                <a href="#gestion-{{ $actualizacion->id }}" class="text-xs text-emerald-300 hover:text-white">Ver / editar avance</a>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 text-xs text-zinc-300">
                                <p class="text-[11px] text-zinc-500 font-semibold">Adjuntos</p>
                                @if ($adjuntosCount)
                                    <div class="mt-1 flex flex-wrap gap-2">
                                        @foreach ($actualizacion->adjuntos as $idx => $archivo)
                                            <a href="{{ asset('storage/'.$archivo) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-1 text-[11px] font-semibold text-white hover:border-indigo-400/60">
                                                Archivo {{ $idx + 1 }}
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="mt-1 text-xs text-zinc-500">Sin adjuntos</p>
                                @endif
                            </div>

                            <details id="gestion-{{ $actualizacion->id }}" class="overflow-hidden rounded-xl border border-white/10 bg-zinc-900/60">
                                <summary class="flex cursor-pointer items-center justify-between px-4 py-3 text-xs font-semibold text-white rounded-xl bg-emerald-600/80 hover:bg-emerald-600 border border-emerald-500/40">
                                    Editar avance
                                    <span class="text-[11px] text-zinc-400">Click para abrir</span>
                                </summary>
                                <div class="border-t border-white/10 px-4 py-4 space-y-3">
                                    <form method="POST" action="{{ route('creador.proyectos.avances.update', [$actualizacion->proyecto_id, $actualizacion->id]) }}" enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <label class="text-sm text-zinc-300">Título *</label>
                                            <input required name="titulo" value="{{ $actualizacion->titulo }}" class="mt-1 w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                                        </div>
                                        <div>
                                            <label class="text-sm text-zinc-300">Contenido</label>
                                            <textarea name="contenido" rows="3" class="mt-1 w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">{{ $actualizacion->contenido }}</textarea>
                                        </div>
                                        <div>
                                            <label class="inline-flex items-center gap-2 text-sm text-zinc-200">
                                                <input type="checkbox" name="es_hito" value="1" @checked($actualizacion->es_hito) class="h-4 w-4 rounded border-white/20 bg-zinc-900 text-indigo-500 focus:ring-indigo-400">
                                                Marcar como hito cumplido
                                            </label>
                                            <p class="text-[11px] text-zinc-500 ml-6">Los hitos destacados se muestran a colaboradores.</p>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm text-zinc-300">Reemplazar adjuntos</label>
                                            <label for="adjuntos-{{ $actualizacion->id }}" class="group flex flex-col gap-2 rounded-xl border-2 border-dashed border-emerald-400/40 bg-emerald-500/5 px-4 py-4 text-sm text-emerald-50 cursor-pointer hover:border-emerald-400 transition">
                                                <div class="flex items-center gap-3">
                                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-100 border border-emerald-500/40">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                    </span>
                                                    <div>
                                                        <p class="font-semibold text-white text-sm">Arrastra o haz clic para seleccionar</p>
                                                        <p class="text-[12px] text-emerald-200/80" data-file-label="avance-{{ $actualizacion->id }}">Sin archivos seleccionados</p>
                                                    </div>
                                                </div>
                                                <p class="text-[12px] text-emerald-200/70">Máx. 8MB por archivo. Reemplaza los existentes.</p>
                                                <input id="adjuntos-{{ $actualizacion->id }}" type="file" name="adjuntos[]" multiple class="hidden" data-file-preview="avance-{{ $actualizacion->id }}">
                                            </label>
                                            <div class="mt-1 flex flex-wrap gap-2 text-xs text-zinc-300" data-file-list="avance-{{ $actualizacion->id }}"></div>
                                        </div>
                                        <div class="flex flex-wrap gap-2 pt-2">
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                                                Guardar cambios
                                            </button>
                                        </div>
                                    </form>
                                    <form method="POST" action="{{ route('creador.proyectos.avances.delete', [$actualizacion->proyecto_id, $actualizacion->id]) }}" onsubmit="return confirm('¿Eliminar este avance?');" class="pt-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-500">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </details>
                        </article>
                    @empty
                        <p class="text-sm text-zinc-400">Aún no hay avances publicados para este proyecto. Usa el panel izquierdo para publicar el primero.</p>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- Calificaciones y comentarios de colaboradores --}}
        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-amber-500/10 space-y-4 mt-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Feedback</p>
                    <h3 class="text-lg font-bold text-white">Calificaciones y comentarios</h3>
                    <p class="text-sm text-zinc-400">Lo que dicen quienes apoyaron el proyecto.</p>
                </div>
                <div class="text-xs text-zinc-300">
                    <span>{{ $calificaciones->count() }} opiniones</span>
                </div>
            </div>
            @if ($calificaciones->isEmpty())
                <p class="text-sm text-zinc-400">Aún no hay calificaciones para este proyecto.</p>
            @else
                <div class="grid gap-3 md:grid-cols-2">
                    @foreach ($calificaciones as $cal)
                        @php $stars = (int) $cal->puntaje; @endphp
                        <article class="rounded-2xl border border-white/10 bg-black/40 p-4 space-y-2 shadow-inner">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ $cal->colaborador->nombre_completo ?? $cal->colaborador->name ?? 'Colaborador' }}</p>
                                    <p class="text-[11px] text-zinc-500">{{ optional($cal->fecha_calificacion ?? $cal->created_at)->format('d/m/Y H:i') }}</p>
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
        </section>
    @endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[type="file"][data-file-preview]').forEach(input => {
        const previewId = input.getAttribute('data-file-preview');
        const container = document.querySelector(`[data-file-list="${previewId}"]`);
        const label = document.querySelector(`[data-file-label="${previewId}"]`);
        const updateList = () => {
            if (!container) return;
            container.innerHTML = '';
            const files = Array.from(input.files || []);
            if (!files.length) {
                container.textContent = 'Sin archivos seleccionados';
                container.classList.add('text-zinc-500');
                if (label) {
                    label.textContent = 'Sin archivos seleccionados';
                    label.classList.add('text-emerald-200/80');
                    label.classList.remove('text-white');
                    label.closest('label')?.classList.remove('border-emerald-400');
                }
                return;
            }
            container.classList.remove('text-zinc-500');
            files.forEach(file => {
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center gap-1 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-white';
                badge.textContent = file.name;
                container.appendChild(badge);
            });
            if (label) {
                label.textContent = files.length === 1 ? files[0].name : `${files.length} archivos seleccionados`;
                label.classList.remove('text-emerald-200/80');
                label.classList.add('text-white');
                label.closest('label')?.classList.add('border-emerald-400');
            }
        };
        input.addEventListener('change', updateList);
        updateList();
    });
    const cronogramaSelect = document.getElementById('cronograma-select');
    const tituloInput = document.querySelector("input[name=\"titulo\"]");
    const esHitoInput = document.getElementById('es-hito-flag');
    const syncHito = () => {
        const selected = cronogramaSelect?.value || '';
        if (esHitoInput) {
            esHitoInput.value = selected ? '1' : '0';
        }
        if (selected && tituloInput && !tituloInput.value.trim()) {
            tituloInput.value = selected;
        }
    };
    if (cronogramaSelect) {
        cronogramaSelect.addEventListener('change', syncHito);
        syncHito();
    }
});
</script>
@endpush
