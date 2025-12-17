@extends('colaborador.layouts.panel')

@section('title', 'Reportar sospecha')
@section('active', 'reportes')

@section('content')
<div class="px-4 pt-6 pb-10 lg:px-8 space-y-6">
    <header class="space-y-2 max-w-4xl mx-auto">
        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Reporte sospechoso</p>
        <h1 class="text-2xl font-bold text-white">Enviar reporte a auditoría</h1>
        <p class="text-sm text-zinc-400">Selecciona el proyecto, adjunta evidencia y explica por qué crees que hay un gasto sospechoso. Un auditor revisará tu reporte.</p>
        <a href="{{ route('colaborador.reportes.mis') }}" class="inline-flex w-max items-center gap-2 rounded-lg border border-white/15 bg-white/5 px-3 py-2 text-[11px] font-semibold text-zinc-200 hover:border-sky-400/70">
            Ver mis reportes enviados
        </a>
    </header>

    <div class="max-w-4xl mx-auto space-y-3">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-400/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-red-400/40 bg-red-500/10 px-4 py-3 text-sm text-red-100 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>

    <section class="max-w-4xl mx-auto rounded-3xl border border-white/15 bg-[#030712] shadow-[0_24px_60px_rgba(0,0,0,0.55)] p-5 space-y-5 relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-1 bg-sky-500/70"></div>

        <form method="GET" action="{{ route('colaborador.reportes') }}" class="grid gap-3 md:grid-cols-[2fr,auto] md:items-end">
            <div class="space-y-2">
                <label class="text-sm text-white">Paso 1: Buscar y seleccionar proyecto</label>
                <p class="text-[12px] text-gray-400">Empieza escribiendo el nombre; luego selecciona uno de la lista.</p>
                <input type="text" name="q" value="{{ $q }}" placeholder="Escribe el nombre del proyecto" class="w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-gray-500 focus:border-sky-500 focus:ring-sky-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-700">
                    Buscar
                </button>
                <a href="{{ route('colaborador.reportes') }}" class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm font-semibold text-gray-300 hover:border-white/25">
                    Limpiar
                </a>
            </div>
        </form>

        @php
            $checkedProyecto = old('proyecto_id', $selectedProjectId ?? null);
        @endphp

        <form method="POST" action="{{ route('colaborador.reportes.store') }}" enctype="multipart/form-data" class="mt-6 grid gap-4">
            @csrf
            <div class="space-y-2">
                <p class="text-sm text-white">Resultado de búsqueda</p>
                @if ($proyectos->isEmpty())
                    <p class="text-xs text-gray-400">No hay resultados con esa búsqueda.</p>
                @else
                    <div class="grid gap-2 max-h-64 overflow-y-auto rounded-xl border border-white/10 bg-white/5 p-3">
                        @foreach ($proyectos as $proj)
                            <label class="flex items-center gap-3 rounded-lg bg-[#0b1020] px-3 py-2 border border-white/10 hover:border-sky-400/70 @if($checkedProyecto == $proj->id) ring-2 ring-sky-500/70 bg-sky-500/10 @endif">
                                <div class="flex-1 space-y-0.5">
                                    <p class="text-sm font-semibold @if($checkedProyecto == $proj->id) text-sky-200 @else text-white @endif">{{ $proj->titulo }}</p>
                                    <p class="text-[11px] text-gray-400">
                                        {{ $proj->categoria ?? 'Sin categoría' }} · Recaudado: ${{ number_format($proj->monto_recaudado ?? 0, 0, ',', '.') }} · Estado: {{ ucfirst($proj->estado ?? 'N/D') }}
                                    </p>
                                </div>
                                <span class="inline-flex items-center gap-2 text-sky-300 text-xs">
                                    @if($checkedProyecto == $proj->id)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                    <input type="radio" name="proyecto_id" value="{{ $proj->id }}" class="h-4 w-4 text-sky-500" @checked($checkedProyecto == $proj->id)>
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="grid gap-3 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm text-white">Paso 2: Adjunta evidencia (imagen o PDF)</label>
                    <label for="evidencia" class="group flex flex-col gap-2 rounded-xl border-2 border-dashed border-white/20 bg-[#0b1020] px-4 py-6 text-sm text-zinc-200 cursor-pointer hover:border-sky-400/70 transition">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-sky-500/15 text-sky-200 border border-sky-500/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </span>
                            <div>
                                <p class="font-semibold">Arrastra y suelta o haz clic para seleccionar</p>
                                <p id="file-name" class="text-[12px] text-zinc-400">Sin archivo seleccionado</p>
                            </div>
                        </div>
                        <p class="text-[12px] text-gray-400">Opcional. Máx. 4MB (JPG, PNG, PDF).</p>
                        <input id="evidencia" type="file" name="evidencia" accept=".jpg,.jpeg,.png,.webp,.pdf" class="hidden">
                    </label>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-zinc-100">Paso 3: Describe qué te parece sospechoso</label>
                <p class="text-[12px] text-gray-400">Ej: factura duplicada, monto excesivo, proveedor desconocido, etc. Mínimo 20 caracteres. Máx. 500.</p>
                <textarea name="motivo" required rows="6" maxlength="500" class="w-full rounded-xl border border-white/20 bg-[#0b1020] px-4 py-3 text-sm text-white placeholder:text-gray-500 focus:border-sky-500 focus:ring-sky-500" placeholder="Explica qué gasto te parece sospechoso, monto, fechas, proveedor, etc.">{{ old('motivo') }}</textarea>
            </div>

            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('colaborador.dashboard') }}" class="text-sm text-gray-400 hover:text-white">Cancelar / Volver</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-sky-700">
                    Enviar a auditoría
                </button>
            </div>
        </form>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('evidencia');
        const label = document.getElementById('file-name');
        if (!input || !label) return;
        input.addEventListener('change', () => {
            if (input.files && input.files.length) {
                label.textContent = input.files[0].name;
                label.classList.remove('text-zinc-400');
                label.classList.add('text-white');
                label.closest('label')?.classList.add('border-sky-400');
            } else {
                label.textContent = 'Sin archivo seleccionado';
                label.classList.add('text-zinc-400');
                label.classList.remove('text-white');
                label.closest('label')?.classList.remove('border-sky-400');
            }
        });
    });
</script>
@endpush
