@extends('layouts.app')

@section('title', 'Contacto')

@section('content')
    <section class="bg-slate-950 text-white py-24">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-start">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">
                        Hablemos de tu próxima campaña
                    </h1>
                    <p class="text-slate-300 mb-6">
                        ¿Tienes dudas sobre cómo empezar, pagos, o quieres que te ayudemos a diseñar tu campaña?
                        Escríbenos y te respondemos lo antes posible.
                    </p>

                    <div class="space-y-3 text-slate-300 text-sm">
                        <p><span class="font-semibold text-white">Soporte general:</span> soporte@crowdup.com</p>
                        <p><span class="font-semibold text-white">Empresas y alianzas:</span> partners@crowdup.com</p>
                        <p><span class="font-semibold text-white">Horario:</span> Lunes a viernes, 9:00 – 18:00 (GMT-5)</p>
                    </div>
                </div>

                <div class="bg-slate-900/70 border border-slate-800 rounded-2xl p-6 shadow-xl">
                    @if(session('status'))
                        <div class="mb-4 rounded-xl bg-emerald-600/20 border border-emerald-500 px-4 py-2 text-sm text-emerald-100">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h2 class="text-xl font-semibold mb-4">Envíanos un mensaje</h2>

                    <form method="POST" action="{{ route('contacto.enviar') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm mb-1" for="nombre">Nombre completo</label>
                            <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required
                                   class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('nombre')
                                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm mb-1" for="email">Correo electrónico</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                   class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('email')
                                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm mb-1" for="tipo">Motivo</label>
                            <select id="tipo" name="tipo"
                                    class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Selecciona una opción</option>
                                <option value="soporte" @selected(old('tipo') === 'soporte')>Soporte / ayuda</option>
                                <option value="pagos" @selected(old('tipo') === 'pagos')>Pagos y cobros</option>
                                <option value="alianzas" @selected(old('tipo') === 'alianzas')>Alianzas / empresas</option>
                                <option value="otro" @selected(old('tipo') === 'otro')>Otro</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm mb-1" for="mensaje">Mensaje</label>
                            <textarea id="mensaje" name="mensaje" rows="4" required
                                      class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('mensaje') }}</textarea>
                            @error('mensaje')
                                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="w-full md:w-auto inline-flex items-center justify-center rounded-full bg-indigo-500 px-6 py-2 text-sm font-semibold text-white hover:bg-indigo-400 transition">
                            Enviar mensaje
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
