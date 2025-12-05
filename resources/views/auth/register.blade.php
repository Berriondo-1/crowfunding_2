<x-guest-layout>
    <div class="space-y-6" x-data="passwordChecker()">
        <div>
            <p class="text-sm text-indigo-200 font-semibold">Crea tu cuenta</p>
            <h2 class="text-3xl font-extrabold mt-1">Únete a la comunidad CrowdUp</h2>
            <p class="mt-2 text-sm text-zinc-300">Regístrate para lanzar campañas, recibir aportes y seguir cada avance desde un panel unificado.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div class="space-y-2">
                <label for="name" class="text-sm font-semibold text-zinc-100">Nombre completo</label>
                <input id="name" class="block w-full rounded-xl border border-white/10 bg-white/5 text-white placeholder:text-zinc-400 focus:border-indigo-400 focus:ring-indigo-400" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Tu nombre" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email -->
            <div class="space-y-2">
                <label for="email" class="text-sm font-semibold text-zinc-100">Correo electrónico</label>
                <input id="email" class="block w-full rounded-xl border border-white/10 bg-white/5 text-white placeholder:text-zinc-400 focus:border-indigo-400 focus:ring-indigo-400" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="correo@ejemplo.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="text-sm font-semibold text-zinc-100">Contraseña</label>
                <input
                    id="password"
                    class="block w-full rounded-xl border border-white/10 bg-white/5 text-white placeholder:text-zinc-400 focus:border-indigo-400 focus:ring-indigo-400"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    x-model="password"
                    @input="evaluateStrength()"
                    placeholder="Crea una contraseña segura"
                />

                <!-- Barra de progreso -->
                <div class="w-full h-2 rounded-full bg-white/10 overflow-hidden">
                    <div class="h-full transition-all duration-300" :style="`width: ${progress}%; background: ${color};`"></div>
                </div>

                <!-- Texto del nivel -->
                <p class="text-sm font-semibold" :class="textColor" x-text="label"></p>

                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="password_confirmation" class="text-sm font-semibold text-zinc-100">Confirma tu contraseña</label>
                <input id="password_confirmation" class="block w-full rounded-xl border border-white/10 bg-white/5 text-white placeholder:text-zinc-400 focus:border-indigo-400 focus:ring-indigo-400"
                    type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repite tu contraseña" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <div class="flex items-center justify-between text-sm">
                <p class="text-zinc-300">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-indigo-200 hover:text-white font-semibold">Inicia sesión</a></p>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white font-semibold shadow-lg shadow-indigo-500/25 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4l7 7-7 7m-7-7h14"/></svg>
                    Crear cuenta
                </button>
            </div>
        </form>
    </div>

    <!-- AlpineJS script -->
    <script>
        function passwordChecker() {
            return {
                password: '',
                progress: 0,
                label: 'Ingresa una contraseña',
                color: '#a78bfa',
                textColor: 'text-indigo-200',

                evaluateStrength() {
                    let score = 0;

                    if (this.password.length >= 8) score++;
                    if (/[A-Z]/.test(this.password)) score++;
                    if (/[0-9]/.test(this.password)) score++;
                    if (/[^A-Za-z0-9]/.test(this.password)) score++;

                    if (score <= 1) {
                        this.progress = 25;
                        this.label = 'Contraseña débil ❌';
                        this.color = '#ef4444';
                        this.textColor = 'text-red-400';
                    } else if (score === 2) {
                        this.progress = 50;
                        this.label = 'Contraseña moderada ⚠️';
                        this.color = '#f59e0b';
                        this.textColor = 'text-amber-300';
                    } else if (score === 3) {
                        this.progress = 75;
                        this.label = 'Buena contraseña 👍';
                        this.color = '#22c55e';
                        this.textColor = 'text-green-300';
                    } else {
                        this.progress = 100;
                        this.label = 'Contraseña segura ✅';
                        this.color = '#10b981';
                        this.textColor = 'text-emerald-300';
                    }
                }
            }
        }
    </script>
</x-guest-layout>
