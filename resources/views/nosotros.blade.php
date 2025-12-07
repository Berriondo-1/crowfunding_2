@extends('layouts.app')

@section('title', 'Nosotros')

@section('content')
    <section class="bg-slate-950 text-white py-24">
        <div class="max-w-5xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Sobre CrowdUp
            </h1>

            <p class="text-slate-300 mb-8 max-w-3xl">
                CrowdUp es una plataforma de crowdfunding pensada para creadores, ONGs y proyectos
                que quieren validar y financiar sus ideas con una comunidad que confía en ellos.
            </p>

            <div class="grid md:grid-cols-3 gap-6 mb-12 text-sm text-slate-300">
                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6">
                    <h2 class="font-semibold mb-2 text-white">Misión</h2>
                    <p>Hacer que lanzar una campaña de financiación sea simple y accesible para todos.</p>
                </div>
                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6">
                    <h2 class="font-semibold mb-2 text-white">Visión</h2>
                    <p>Convertirnos en la plataforma de referencia en LATAM para proyectos de impacto.</p>
                </div>
                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6">
                    <h2 class="font-semibold mb-2 text-white">Valores</h2>
                    <p>Transparencia, comunidad, responsabilidad y foco en el creador.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
