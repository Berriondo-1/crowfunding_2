@extends('auditor.layouts.panel')

@section('title', 'Reportes sospechosos')
@section('active', 'sospechosos')

@section('content')
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 pt-6">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Modulo 4</p>
            <h1 class="text-2xl font-bold text-white">Reportes sospechosos de colaboradores</h1>
            <p class="text-sm text-zinc-400">Centralizar denuncias, cruzar con gastos y cerrar casos.</p>
        </div>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="inline-flex items-center rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
            Salir
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 space-y-3">
        @forelse ($reportesColab as $item)
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 flex flex-col gap-3">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">ID #{{ $item->id }} — {{ ucfirst($item->estado) }}</p>
                        <p class="text-lg font-semibold text-white">{{ $item->proyecto->titulo ?? 'Proyecto' }}</p>
                        <p class="text-sm text-zinc-400">Colaborador: {{ $item->colaborador->nombre_completo ?? $item->colaborador->name ?? 'N/D' }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2 text-[12px] text-zinc-300">
                        <span class="rounded-full bg-white/10 px-3 py-1">Enviado {{ $item->created_at?->format('d/m/Y H:i') }}</span>
                        <span class="rounded-full bg-white/10 px-3 py-1">Estado: {{ ucfirst($item->estado) }}</span>
                    </div>
                </div>
                <p class="text-sm text-zinc-200 leading-relaxed">{{ $item->motivo }}</p>
                @if (!empty($item->evidencias))
                    <div class="flex flex-wrap gap-2">
                        @foreach ($item->evidencias as $idx => $path)
                            <a href="{{ asset('storage/'.$path) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/10 px-3 py-1.5 text-[12px] font-semibold text-white hover:border-indigo-400/60">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h6" />
                                </svg>
                                Evidencia {{ $idx + 1 }}
                            </a>
                        @endforeach
                    </div>
                @endif
                @if ($item->respuesta)
                    <div class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-[12px] text-emerald-100">
                        Nota auditor: {{ $item->respuesta }}
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-zinc-500">No hay reportes de colaboradores registrados.</p>
        @endforelse
    </div>
@endsection
