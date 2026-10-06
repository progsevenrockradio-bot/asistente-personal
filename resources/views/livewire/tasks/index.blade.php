<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Tareas</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Organiza y prioriza lo que tienes que hacer</p>
        </div>
        <div class="flex items-center gap-2">
            <button 
                wire:click="$set('filter', 'pendientes')"
                class="px-3 py-1.5 rounded-xl text-xs font-semibold cursor-pointer {{ $filter === 'pendientes' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white bg-slate-900' }}"
            >
                Pendientes
            </button>
            <button 
                wire:click="$set('filter', 'completadas')"
                class="px-3 py-1.5 rounded-xl text-xs font-semibold cursor-pointer {{ $filter === 'completadas' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white bg-slate-900' }}"
            >
                Completadas
            </button>
            <button 
                wire:click="$set('filter', 'todas')"
                class="px-3 py-1.5 rounded-xl text-xs font-semibold cursor-pointer {{ $filter === 'todas' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white bg-slate-900' }}"
            >
                Todas
            </button>
        </div>
    </div>

    <!-- Formulario Rápido de Tarea -->
    <form wire:submit="addTask" class="bg-slate-900/80 border border-slate-800 rounded-3xl p-4 sm:p-5 space-y-3">
        <div class="flex flex-col sm:flex-row gap-2">
            <input 
                type="text"
                wire:model="newTitle"
                required
                placeholder="Nueva tarea... (ej. Comprar billetes de tren, Llamar a la clínica)"
                class="flex-1 px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
            <div class="flex items-center gap-2">
                <select 
                    wire:model="newPriority"
                    class="px-3 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="URGENTE">🔴 Urgente</option>
                    <option value="IMPORTANTE">🟡 Importante</option>
                    <option value="NORMAL">🔵 Normal</option>
                    <option value="PUEDE_ESPERAR">⚪ Puede esperar</option>
                </select>
                <input 
                    type="date"
                    wire:model="newDueDate"
                    class="px-3 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                <button 
                    type="submit"
                    class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold cursor-pointer shrink-0"
                >
                    + Añadir
                </button>
            </div>
        </div>
        @error('newTitle')
            <p class="text-xs text-rose-400">{{ $message }}</p>
        @enderror
    </form>

    <!-- Lista de Tareas -->
    @if ($tasks->count() > 0)
        <div class="space-y-2.5">
            @foreach ($tasks as $task)
                <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 transition-all flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <button 
                            wire:click="toggleTask({{ $task->id }})"
                            class="w-5 h-5 rounded-lg border {{ $task->estado === 'completada' ? 'bg-indigo-600 border-indigo-600' : 'border-slate-600 hover:border-indigo-400' }} flex items-center justify-center shrink-0 cursor-pointer transition-colors"
                        >
                            @if ($task->estado === 'completada')
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            @endif
                        </button>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm font-medium text-slate-100 {{ $task->estado === 'completada' ? 'line-through text-slate-500' : '' }}">
                                {{ $task->titulo }}
                            </p>
                            @if ($task->fecha_limite)
                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    Fecha límite: {{ $task->fecha_limite->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-[9px] px-2 py-0.5 rounded-full font-bold uppercase
                            {{ $task->prioridad === 'URGENTE' ? 'bg-rose-500/20 text-rose-300' : '' }}
                            {{ $task->prioridad === 'IMPORTANTE' ? 'bg-amber-500/20 text-amber-300' : '' }}
                            {{ $task->prioridad === 'NORMAL' ? 'bg-slate-800 text-slate-400' : '' }}
                            {{ $task->prioridad === 'PUEDE_ESPERAR' ? 'bg-slate-800/50 text-slate-500' : '' }}
                        ">
                            {{ $task->prioridad }}
                        </span>
                        <button 
                            wire:click="deleteTask({{ $task->id }})"
                            wire:confirm="¿Eliminar esta tarea?"
                            class="p-1.5 text-slate-500 hover:text-rose-400 rounded-lg cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="py-12 text-center border border-dashed border-slate-800 rounded-3xl">
            <span class="text-3xl mb-2 block">📋</span>
            <p class="text-sm font-semibold text-slate-300">No hay tareas pendientes</p>
            <p class="text-xs text-slate-500 mt-1">Usa el formulario superior para añadir lo que necesites</p>
        </div>
    @endif
</div>
