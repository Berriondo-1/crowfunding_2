@extends('creator.layouts.panel')

@section('title', 'Nuevo proyecto')
@section('active', 'proyectos')
@section('back_url', route('creador.proyectos'))
@section('back_label', 'Volver a proyectos')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 space-y-6">
        <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-600/25 via-zinc-900/70 to-zinc-900/70 p-8 shadow-2xl">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-300">Publicar</p>
                    <h2 class="text-2xl font-bold text-white">Crea un nuevo proyecto</h2>
                    <p class="text-sm text-zinc-300">Define la informacion base: meta, modelo, ubicacion, fechas y portada.</p>
                </div>
            </div>

            @if (session('status'))
                <div class="mt-4 rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mt-4 rounded-2xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('creador.proyectos.store') }}" enctype="multipart/form-data" class="mt-6 grid gap-4 md:grid-cols-2">
                @csrf
                <div class="md:col-span-2">
                    <label class="text-sm text-zinc-300">Titulo</label>
                    <input name="titulo" required class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Nombre de tu proyecto">
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-zinc-300">Descripcion</label>
                    <textarea name="descripcion_proyecto" rows="3" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Describe el problema, la solucion y el impacto"></textarea>
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Meta de financiacion (USD)</label>
                    <input type="number" step="0.01" min="0" name="meta_financiacion" required class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="10000">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Modelo de financiamiento</label>
                    <select name="modelo_financiamiento_id" class="mt-1 w-full rounded-xl border border-white/10 bg-zinc-900/80 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400">
                        <option value="">Selecciona un modelo</option>
                        @foreach ($modelos as $modelo)
                            <option value="{{ $modelo->id }}">{{ $modelo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Categoria</label>
                    <select name="categoria_id" class="mt-1 w-full rounded-xl border border-white/10 bg-zinc-900/80 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400">
                        <option value="">Selecciona una categoria</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Ubicacion</label>
                    <input name="ubicacion_geografica" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Ciudad, pais">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Fecha limite</label>
                    <input type="date" name="fecha_limite" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-sm text-zinc-300">Imagen de portada</label>
                    <label for="portada" class="group flex flex-col gap-2 rounded-xl border-2 border-dashed border-emerald-400/40 bg-emerald-500/5 px-4 py-6 text-sm text-emerald-50 cursor-pointer hover:border-emerald-400 transition">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-100 border border-emerald-500/40">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </span>
                            <div>
                                <p class="font-semibold text-white">Arrastra o haz clic para seleccionar</p>
                                <p id="portada-name" class="text-[12px] text-emerald-200/80">Sin archivo seleccionado</p>
                            </div>
                        </div>
                        <p class="text-[12px] text-emerald-200/70">JPG, PNG o WEBP. Máx. 8MB.</p>
                        <input id="portada" type="file" name="portada" accept="image/*" class="hidden">
                    </label>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/30 hover:bg-emerald-500">
                        Publicar en borrador
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('portada');
    const label = document.getElementById('portada-name');
    if (!input || !label) return;
    input.addEventListener('change', () => {
        if (input.files && input.files.length) {
            label.textContent = input.files[0].name;
            label.classList.remove('text-emerald-200/80');
            label.classList.add('text-white');
            label.closest('label')?.classList.add('border-emerald-400');
        } else {
            label.textContent = 'Sin archivo seleccionado';
            label.classList.add('text-emerald-200/80');
            label.classList.remove('text-white');
            label.closest('label')?.classList.remove('border-emerald-400');
        }
    });
});
</script>
@endpush
