<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Bandeja de Entrada</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Notas rápidas, pensamientos y texto desordenado pendiente de organizar</p>
        </div>
        <button 
            @click="quickMode = 'text'; quickModalOpen = true"
            class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-sky-600 hover:from-indigo-500 hover:to-sky-500 text-white font-semibold text-xs shadow-md shadow-indigo-600/30 flex items-center gap-1.5 cursor-pointer self-start sm:self-auto"
        >
            <span>+</span>
            <span>Nueva Captura</span>
        </button>
    </div>

    <!-- PREGUNTAS PENDIENTES DEL ASISTENTE (Section 9) -->
    @if ($pendingQuestions->count() > 0)
        <div class="bg-amber-500/10 border border-amber-500/30 rounded-3xl p-5 space-y-3">
            <div class="flex items-center gap-2">
                <span class="text-lg">❓</span>
                <div>
                    <h3 class="text-sm font-bold text-amber-300">Preguntas del Asistente</h3>
                    <p class="text-[11px] text-amber-400/80">Completa los datos faltantes para que el asistente pueda agendar con precisión</p>
                </div>
            </div>

            <div class="space-y-3 mt-3">
                @foreach ($pendingQuestions as $q)
                    <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-slate-800 space-y-2">
                        <div class="flex items-start justify-between gap-2">
                            <span class="text-xs font-semibold text-white">{{ $q->pregunta }}</span>
                            <span class="text-[9px] px-2 py-0.5 rounded-full font-bold uppercase bg-amber-500/20 text-amber-300">
                                Falta {{ $q->campo_faltante }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <input 
                                type="text"
                                wire:model="answerInput"
                                placeholder="Escribe la respuesta aquí..."
                                class="flex-1 px-3 py-1.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                            >
                            <button 
                                wire:click="answerQuestion({{ $q->id }})"
                                class="px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-semibold cursor-pointer"
                            >
                                Responder
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filtros de Bandeja -->
    <div class="flex items-center gap-2 border-b border-slate-800 pb-3 text-xs font-medium">
        <button 
            wire:click="$set('filter', 'todos')"
            class="px-3 py-1.5 rounded-xl transition-all cursor-pointer {{ $filter === 'todos' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white' }}"
        >
            Todos
        </button>
        <button 
            wire:click="$set('filter', 'pendiente')"
            class="px-3 py-1.5 rounded-xl transition-all cursor-pointer {{ $filter === 'pendiente' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white' }}"
        >
            Pendientes
        </button>
        <button 
            wire:click="$set('filter', 'analizado')"
            class="px-3 py-1.5 rounded-xl transition-all cursor-pointer {{ $filter === 'analizado' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white' }}"
        >
            Analizados
        </button>
        <button 
            wire:click="$set('filter', 'archivado')"
            class="px-3 py-1.5 rounded-xl transition-all cursor-pointer {{ $filter === 'archivado' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white' }}"
        >
            Archivados
        </button>
    </div>

    <!-- Lista de Elementos -->
    @if ($items->count() > 0)
        <div class="space-y-3">
            @foreach ($items as $item)
                <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 transition-all space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-xs">
                                {{ $item->source === 'voz' ? '🎙' : '✍️' }}
                            </span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase
                                {{ $item->status === 'pendiente_analisis' ? 'bg-indigo-500/20 text-indigo-300' : '' }}
                                {{ $item->status === 'necesita_informacion' ? 'bg-amber-500/20 text-amber-300' : '' }}
                                {{ $item->status === 'convertido_evento' ? 'bg-emerald-500/20 text-emerald-300' : '' }}
                                {{ $item->status === 'convertido_tarea' ? 'bg-sky-500/20 text-sky-300' : '' }}
                                {{ $item->status === 'archivado' ? 'bg-slate-800 text-slate-500' : '' }}
                            ">
                                {{ str_replace('_', ' ', $item->status) }}
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-500">
                            {{ $item->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    <p class="text-sm text-slate-100 whitespace-pre-wrap leading-relaxed">
                        {{ $item->content }}
                    </p>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800/60 text-xs">
                        @if ($item->status !== 'archivado' && !str_starts_with($item->status, 'convertido_'))
                            <a href="{{ route('calendar.create', ['inbox_item_id' => $item->id]) }}" class="px-2.5 py-1 text-emerald-400 hover:text-emerald-300 hover:bg-emerald-500/10 rounded-lg cursor-pointer">
                                Evento
                            </a>
                            <a href="{{ route('tasks.create', ['inbox_item_id' => $item->id]) }}" class="px-2.5 py-1 text-sky-400 hover:text-sky-300 hover:bg-sky-500/10 rounded-lg cursor-pointer">
                                Tarea
                            </a>
                        @endif
                        @if ($item->status !== 'archivado')
                            <button 
                                wire:click="archive({{ $item->id }})"
                                class="px-2.5 py-1 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg cursor-pointer"
                            >
                                Archivar
                            </button>
                        @endif
                        <button 
                            wire:click="deleteItem({{ $item->id }})"
                            wire:confirm="¿Estás seguro de eliminar esta nota de la bandeja?"
                            class="px-2.5 py-1 text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 rounded-lg cursor-pointer"
                        >
                            Eliminar
                        </button>
                    </div>
                </div>
            @endforeach

            <div class="pt-4">
                {{ $items->links() }}
            </div>
        </div>
    @else
        <div class="py-12 text-center border border-dashed border-slate-800 rounded-3xl">
            <span class="text-3xl mb-2 block">📭</span>
            <p class="text-sm font-semibold text-slate-300">No hay elementos en esta vista</p>
            <p class="text-xs text-slate-500 mt-1">Usa el botón de captura o dicta por voz lo que tengas en mente</p>
        </div>
    @endif
</div>
