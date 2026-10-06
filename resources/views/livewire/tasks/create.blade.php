<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-white tracking-tight">Nueva Tarea</h1>
        <p class="text-sm text-slate-400 mt-1">Añade una nueva tarea a tu lista</p>
    </div>

    <form wire:submit="save" class="bg-slate-900/80 border border-slate-800 rounded-3xl p-5 sm:p-6 space-y-5">
        
        <div>
            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Título</label>
            <input type="text" wire:model="titulo" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="Ej. Comprar billetes de tren">
            @error('titulo') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Descripción (Opcional)</label>
            <textarea wire:model="descripcion" rows="3" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
            @error('descripcion') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Fecha Límite</label>
                <input type="date" wire:model="fecha_limite" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                @error('fecha_limite') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Prioridad</label>
                <select wire:model="prioridad" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <option value="URGENTE">Urgente</option>
                    <option value="IMPORTANTE">Importante</option>
                    <option value="NORMAL">Normal</option>
                    <option value="PUEDE_ESPERAR">Puede Esperar</option>
                </select>
                @error('prioridad') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Depende de (Opcional)</label>
            <select wire:model="dependencia_id" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">Ninguna</option>
                @foreach($tasks as $t)
                    <option value="{{ $t->id }}">{{ $t->titulo }}</option>
                @endforeach
            </select>
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <a href="{{ route('tasks.index') }}" class="px-5 py-2.5 rounded-xl text-slate-400 hover:text-white font-semibold text-sm transition-colors">Cancelar</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-md shadow-indigo-600/20 transition-colors">Guardar Tarea</button>
        </div>
    </form>
</div>
