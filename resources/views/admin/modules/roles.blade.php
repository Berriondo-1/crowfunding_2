<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Roles y usuarios | CrowdUp Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-20 top-0 h-64 w-64 rounded-full bg-indigo-600/30 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-64 w-64 rounded-full bg-fuchsia-500/20 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    ← Volver al dashboard
                </a>
                <h1 class="text-lg font-semibold text-white">Roles y usuarios</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">ADMIN</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 shadow-2xl">
            <div class="border-b border-white/5 px-8 py-6">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Gestión operativa</p>
                <h2 class="mt-2 text-2xl font-bold text-white">Asignación de roles</h2>
                <p class="mt-2 text-sm text-zinc-400">
                    Controla permisos de acceso para todos los usuarios del ecosistema.
                </p>
            </div>

            @if (session('status'))
                <div class="mx-8 mt-6 rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-6 py-4 text-sm text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            <div class="divide-y divide-white/5">
                @forelse ($users as $user)
                    <div class="grid gap-6 px-6 py-6 lg:grid-cols-[1.6fr,1fr] lg:items-center">
                        <div>
                            <div class="flex flex-wrap items-center gap-3">
                                <div>
                                    <p class="text-lg font-semibold text-white">{{ $user->nombre_completo ?? $user->name }}</p>
                                    <p class="text-sm text-zinc-400">{{ $user->email }}</p>
                                </div>
                                @if($user->estado_verificacion)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300">
                                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span> Verificado
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-500/10 px-3 py-1 text-xs font-semibold text-yellow-300">
                                        <span class="h-2 w-2 rounded-full bg-yellow-300"></span> Revisión pendiente
                                    </span>
                                @endif
                            </div>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @forelse ($user->roles as $role)
                                    <span class="rounded-full border border-white/10 px-3 py-1 text-xs font-semibold text-white">
                                        {{ $role->nombre_rol }}
                                    </span>
                                @empty
                                    <span class="rounded-full border border-dashed border-white/20 px-3 py-1 text-xs text-zinc-400">
                                        Sin rol asignado
                                    </span>
                                @endforelse
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.users.roles', $user) }}"
                              class="rounded-2xl border border-white/10 bg-zinc-950/40 p-5 shadow-inner">
                            @csrf
                            @method('PATCH')
                            <div class="grid gap-3">
                                @foreach ($roles as $role)
                                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-white/5 bg-white/5 px-3 py-2 text-sm text-white hover:border-indigo-400/60">
                                        <input type="checkbox"
                                               name="roles[]"
                                               value="{{ $role->id }}"
                                               class="h-4 w-4 rounded border-white/30 bg-transparent text-indigo-500 focus:ring-indigo-400"
                                               @checked($user->roles->contains('id', $role->id))>
                                        <span>{{ $role->nombre_rol }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <button type="submit"
                                    class="mt-4 w-full rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500">
                                Actualizar roles
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="px-8 py-10 text-center text-zinc-400">Aún no existen usuarios registrados.</p>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>
