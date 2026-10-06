<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Añadir Evento o Cita</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Registra una cita médica, concierto o reunión</p>
        </div>
        <a href="{{ route('calendar.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white">
            &larr; Volver
        </a>
    </div>

    <form wire:submit="save" class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 space-y-4">
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                Título del Evento *
            </label>
            <input 
                type="text"
                wire:model="titulo"
                required
                placeholder="Ej. Cita con el cardiólogo, Concierto en WiZink..."
                class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
            @error('titulo')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                    Fecha *
                </label>
                <input 
                    type="date"
                    wire:model="fecha"
                    required
                    class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                @error('fecha')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                    Hora de Inicio
                </label>
                <input 
                    type="time"
                    wire:model="hora_inicio"
                    class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                    Duración (minutos)
                </label>
                <input 
                    type="number"
                    wire:model="duracion"
                    step="15"
                    min="5"
                    class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                    Prioridad
                </label>
                <select 
                    wire:model="prioridad"
                    class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="URGENTE">🔴 Urgente</option>
                    <option value="IMPORTANTE">🟡 Importante</option>
                    <option value="NORMAL">🔵 Normal</option>
                    <option value="PUEDE_ESPERAR">⚪ Puede esperar</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                Ubicación o Lugar
            </label>
            <input 
                type="text"
                wire:model="ubicacion"
                placeholder="Ej. Hospital Clínico, Sala 4 / Calle Mayor 12"
                class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                Notas / Preparación previa
            </label>
            <textarea 
                wire:model="notas"
                rows="3"
                placeholder="Llevar analíticas previas, ir en ayunas..."
                class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
            ></textarea>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3">
            <a href="{{ route('calendar.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-medium text-slate-400 hover:text-white">
                Cancelar
            </a>
            <button 
                type="submit"
                class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-sky-600 hover:from-indigo-500 hover:to-sky-500 text-white font-semibold text-xs shadow-md shadow-indigo-600/30 cursor-pointer"
            >
                Guardar Evento
            </button>
        </div>
    </form>
</div>
