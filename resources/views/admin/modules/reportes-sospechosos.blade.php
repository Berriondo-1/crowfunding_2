<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reportes sospechosos | CrowdUp Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden bg-zinc-950">
        <div class="absolute -left-24 top-0 h-72 w-72 rounded-full bg-indigo-600/30 blur-2xl"></div>
        <div class="absolute right-0 top-24 h-72 w-72 rounded-full bg-fuchsia-500/25 blur-2xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.auditorias') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver al overview
                </a>
                <h1 class="text-lg font-semibold text-white">Reportes sospechosos (Admin)</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">ADMIN</span>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-full px-0 pt-0 pb-6">
        <div class="grid gap-0 lg:grid-cols-[280px_1fr] lg:min-h-[calc(100vh-64px)] lg:overflow-hidden admin-shell">
            <aside class="lg:sticky lg:top-0 admin-sidebar">
                @include('admin.partials.modules', ['active' => 'auditorias'])
            </aside>

            <div class="space-y-8 lg:overflow-y-auto lg:h-full lg:pr-2 admin-scroll admin-main">
                <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl space-y-4">
                    <form method="GET" action="{{ route('admin.reportes') }}" class="rounded-3xl border border-white/10 bg-white/5 p-4 grid gap-3 md:grid-cols-[1.5fr,1fr,auto] md:items-end shadow-[0_12px_30px_rgba(0,0,0,0.35)] relative overflow-hidden">
                        <div class="absolute inset-x-0 top-0 h-0.5 bg-white/10"></div>
                        <div>
                            <p class="text-[11px] uppercase tracking-[0.3em] text-zinc-400">Buscar en reportes</p>
                            <input
                                type="text"
                                name="q"
                                value="{{ $q ?? '' }}"
                                placeholder="Proyecto, colaborador o parte del motivo"
                                class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white placeholder:text-zinc-500 focus:border-sky-500 focus:ring-sky-500"
                            >
                            <p class="text-xs text-zinc-400 mt-1">La búsqueda recorre títulos, nombres y descripciones de cada reporte.</p>
                        </div>
                        <div>
                            <label class="text-xs text-zinc-400">Estado</label>
                            <select
                                name="estado"
                                class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-sky-500 focus:ring-sky-500"
                            >
                                <option value="">Todos</option>
                                @php
                                    $estadosFiltro = collect(['pendiente', 'aprobado', 'rechazado'])
                                        ->merge($estados ?? collect())
                                        ->unique()
                                        ->values();
                                @endphp
                                @foreach ($estadosFiltro as $opt)
                                    <option value="{{ $opt }}" {{ ($estado ?? '') === $opt ? 'selected' : '' }}>
                                        {{ ucfirst($opt) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700 w-full">
                                Filtrar
                            </button>
                            <a href="{{ route('admin.reportes') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-zinc-200 hover:border-white/25">
                                Limpiar
                            </a>
                        </div>
                    </form>

                    @if(session('status'))
                        <div class="rounded-2xl border border-emerald-400/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="space-y-5">
                        @forelse ($reportes as $item)
                            @php
                                $estadoActual = $item->estado ?? 'pendiente';
                                $estadoClase = match ($estadoActual) {
                                    'aprobado' => 'bg-emerald-500/15 text-emerald-200 border-emerald-400/40',
                                    'rechazado' => 'bg-rose-500/15 text-rose-200 border-rose-400/40',
                                    default => 'bg-amber-500/15 text-amber-200 border-amber-400/40',
                                };
                            @endphp
                            <article class="rounded-2xl border border-white/10 bg-[#0b1020] p-5 shadow-[0_10px_30px_rgba(0,0,0,0.35)] space-y-4">
                                <div class="flex flex-wrap items-start justify-between gap-4">
                                    <div class="space-y-1">
                                        <p class="text-xs uppercase tracking-[0.3em] text-sky-200">ID #{{ $item->id }} • {{ $item->proyecto->titulo ?? 'Proyecto' }}</p>
                                        <p class="text-sm text-zinc-400">Colaborador: {{ $item->colaborador->nombre_completo ?? $item->colaborador->name ?? 'N/D' }}</p>
                                    </div>
                                    <div class="flex flex-col items-end gap-1 text-right">
                                        <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-[11px] font-semibold {{ $estadoClase }}">
                                            <span class="h-2 w-2 rounded-full bg-current opacity-80"></span>
                                            {{ ucfirst($estadoActual) }}
                                        </span>
                                        <p class="text-[11px] text-zinc-400">Enviado {{ optional($item->created_at)->format('d/m/Y H:i') ?? 'N/D' }}</p>
                                    </div>
                                </div>
                                <p class="text-base font-semibold text-white leading-relaxed">{{ $item->motivo }}</p>
                                @if (!empty($item->evidencias))
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($item->evidencias as $idx => $path)
                                            <a href="{{ asset('storage/'.$path) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-[12px] font-semibold text-white hover:border-indigo-400/60">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h6" />
                                                </svg>
                                                Evidencia {{ $idx + 1 }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('admin.reportes.estado', $item) }}" class="space-y-3 border-t border-white/10 pt-3">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="reporte_id" value="{{ $item->id }}">

                                    <label class="text-xs uppercase tracking-[0.3em] text-zinc-500">Explicación del auditor</label>
                                    <p class="text-[11px] text-zinc-400">Este comentario quedará registrado en el historial del caso. Obligatorio (mínimo 20 caracteres, máximo 500).</p>
                                    <textarea
                                        name="respuesta"
                                        rows="4"
                                        class="w-full rounded-xl border border-zinc-700 bg-white/5 px-3 py-2 text-sm text-white placeholder:text-zinc-500 focus:border-sky-500 focus:ring-sky-500"
                                        placeholder="Describe por qué apruebas o rechazas este reporte."
                                        maxlength="500"
                                    >{{ old('reporte_id') == $item->id ? old('respuesta') : ($item->respuesta ?? '') }}</textarea>
                                    @if ($errors->has('respuesta') && old('reporte_id') == $item->id)
                                        <p class="text-xs text-rose-300">{{ $errors->first('respuesta') }}</p>
                                    @endif
                                    <div class="flex justify-between text-[11px] text-zinc-400">
                                        <span>Mínimo 20 caracteres requeridos.</span>
                                        <span data-char-count class="font-mono">0 / 500</span>
                                    </div>

                                    <div class="flex flex-wrap gap-3">
                                        <button type="submit" name="accion" value="rechazar" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 px-4 py-2 text-sm font-semibold text-white hover:from-purple-500 hover:to-fuchsia-500 flex-1">
                                            Rechazar
                                        </button>
                                        <button type="submit" name="accion" value="aprobar" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 px-4 py-2 text-sm font-semibold text-white hover:from-emerald-500 hover:to-teal-500 flex-1">
                                            Marcar como aprobado
                                        </button>
                                    </div>
                                </form>
                            </article>
                        @empty
                            <p class="text-sm text-zinc-500">No hay reportes de colaboradores registrados.</p>
                        @endforelse
                    </div>

                    @if ($reportes instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4">
                            {{ $reportes->links() }}
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </main>

</body>
</html>
