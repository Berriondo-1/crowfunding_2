@extends('layouts.app')

@section('title', 'Prensa')

@section('content')
    <section class="bg-slate-950 text-white py-24">
        <div class="max-w-5xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Sala de prensa</h1>
            <p class="text-slate-300 mb-8 max-w-2xl">
                Información para medios, notas oficiales y recursos de marca de CrowdUp.
            </p>

            <div class="grid md:grid-cols-2 gap-8 mb-12">
                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6">
                    <h2 class="text-xl font-semibold mb-3">Contacto de prensa</h2>
                    <p class="text-slate-300 text-sm mb-2">
                        Email: <span class="text-white font-medium">prensa@crowdup.com</span>
                    </p>
                    <p class="text-slate-300 text-sm">
                        Para entrevistas, cifras, casos de éxito y lanzamientos.
                    </p>
                </div>

                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6">
                    <h2 class="text-xl font-semibold mb-3">Kit de prensa</h2>
                    <ul class="text-slate-300 text-sm space-y-2">
                        <li>• Logotipos oficiales y guía de uso</li>
                        <li>• Capturas de pantalla de la plataforma</li>
                        <li>• Hoja de datos (fact sheet)</li>
                    </ul>
                    <button class="mt-4 inline-flex items-center rounded-full bg-slate-800 px-4 py-2 text-xs font-semibold hover:bg-slate-700">
                        Descargar kit (PDF)
                    </button>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-10">
                <h2 class="text-2xl font-semibold mb-4">Datos rápidos</h2>
                <div class="grid md:grid-cols-3 gap-6 text-sm text-slate-300">
                    <div>
                        <p class="text-3xl font-bold text-white mb-1">+3K</p>
                        <p>Campañas lanzadas en la plataforma.</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-white mb-1">65</p>
                        <p>Países con donantes registrados.</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-white mb-1">4.9</p>
                        <p>Satisfacción promedio de creadores.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
