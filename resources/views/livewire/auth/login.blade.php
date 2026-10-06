<div>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-white tracking-tight">Accede a tu asistente</h2>
        <p class="text-sm text-slate-400 mt-1">Introduce tus credenciales para continuar</p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-4">
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                Correo Electrónico
            </label>
            <div class="relative">
                <input
                    wire:model="email"
                    type="email"
                    id="email"
                    autocomplete="email"
                    required
                    class="w-full px-4 py-3 bg-slate-950/60 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="ejemplo@correo.com"
                >
            </div>
            @error('email')
                <p class="mt-1 text-xs text-rose-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                    Contraseña
                </label>
                <a href="{{ route('password.request') }}" class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors">
                    ¿Olvidaste tu clave?
                </a>
            </div>
            <input
                wire:model="password"
                type="password"
                id="password"
                autocomplete="current-password"
                required
                class="w-full px-4 py-3 bg-slate-950/60 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                placeholder="••••••••"
            >
            @error('password')
                <p class="mt-1 text-xs text-rose-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between py-1">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input
                    wire:model="remember"
                    type="checkbox"
                    class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900"
                >
                <span class="text-xs text-slate-300">Mantener sesión iniciada</span>
            </label>
        </div>

        <button
            type="submit"
            wire:loading.attr="disabled"
            class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-sky-600 hover:from-indigo-500 hover:to-sky-500 text-white font-semibold text-sm shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
        >
            <span wire:loading.remove>Entrar al Asistente</span>
            <span wire:loading class="inline-flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Verificando...
            </span>
        </button>
    </form>

    <div class="mt-6 pt-5 border-t border-slate-800/80 text-center text-xs text-slate-400">
        ¿Aún no tienes cuenta?
        <a href="{{ route('register') }}" class="font-semibold text-indigo-400 hover:text-indigo-300 ml-1">
            Crear cuenta
        </a>
    </div>
</div>
