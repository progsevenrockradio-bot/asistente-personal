<div class="space-y-6">
    <!-- Header / Resumen del Día -->
    <div class="bg-gradient-to-br from-indigo-950/70 via-slate-900 to-slate-900/90 border border-indigo-900/30 rounded-3xl p-5 sm:p-6 shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-widest text-indigo-400">HOY</span>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight mt-0.5">
                    {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-1">
                    Hola, <strong class="text-slate-200">{{ auth()->user()->name }}</strong>. Aquí tienes el pulso de tu jornada.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('calendar.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-slate-800 border border-slate-700 text-xs font-semibold text-slate-200 flex items-center gap-1.5 transition-all">
                    <span>📅</span>
                    <span>Ver Calendario</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Banner de Preguntas Pendientes de IA si las hay -->
    @if ($pendingQuestionsCount > 0)
        <a href="{{ route('inbox.index') }}" class="block p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 hover:bg-amber-500/15 transition-all">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-xl">🤔</span>
                    <div>
                        <h4 class="text-xs font-bold text-amber-300">El asistente necesita datos pendientes</h4>
                        <p class="text-[11px] text-amber-400/80 mt-0.5">Tienes {{ $pendingQuestionsCount }} pregunta(s) para completar citas o tareas pendientes</p>
                    </div>
                </div>
                <span class="text-xs font-semibold text-amber-300">&rarr;</span>
            </div>
        </a>
    @endif

    <!-- Bloques: AHORA y PRÓXIMO -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- AHORA -->
        <div class="bg-slate-900/80 border border-slate-800/90 rounded-3xl p-5 shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-400 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    AHORA
                </span>
                @if ($currentActivity && $currentActivity->hora_inicio)
                    <span class="text-xs font-semibold text-slate-400">
                        {{ substr($currentActivity->hora_inicio, 0, 5) }}
                        @if ($currentActivity->hora_fin)
                            - {{ substr($currentActivity->hora_fin, 0, 5) }}
                        @endif
                    </span>
                @endif
            </div>

            @if ($currentActivity)
                <h3 class="text-base font-bold text-white tracking-tight">{{ $currentActivity->titulo }}</h3>
                @if ($currentActivity->ubicacion)
                    <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                        <span>📍</span> {{ $currentActivity->ubicacion }}
                    </p>
                @endif
                @if ($currentActivity->descripcion)
                    <p class="text-xs text-slate-400 mt-2 line-clamp-2">{{ $currentActivity->descripcion }}</p>
                @endif
            @else
                <div class="py-3 text-center sm:text-left">
                    <p class="text-sm font-medium text-slate-300">Sin actividad en este momento</p>
                    <p class="text-xs text-slate-500 mt-0.5">Tiempo libre o disponible</p>
                </div>
            @endif
        </div>

        <!-- PRÓXIMO -->
        <div class="bg-slate-900/80 border border-slate-800/90 rounded-3xl p-5 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-black uppercase tracking-wider text-sky-400">PRÓXIMO</span>
                @if ($nextActivity && $nextActivity->hora_inicio)
                    <span class="text-xs font-semibold text-sky-300">
                        {{ substr($nextActivity->hora_inicio, 0, 5) }}
                    </span>
                @endif
            </div>

            @if ($nextActivity)
                <h3 class="text-base font-bold text-white tracking-tight">{{ $nextActivity->titulo }}</h3>
                <div class="flex items-center gap-2 mt-1 text-xs text-slate-400">
                    <span>📅 {{ $nextActivity->fecha->isoFormat('D MMM') }}</span>
                    @if ($nextActivity->ubicacion)
                        <span>&bull;</span>
                        <span class="truncate">📍 {{ $nextActivity->ubicacion }}</span>
                    @endif
                </div>
            @else
                <div class="py-3 text-center sm:text-left">
                    <p class="text-sm font-medium text-slate-300">Sin compromisos inmediatos</p>
                    <p class="text-xs text-slate-500 mt-0.5">Todo despejado por ahora</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ACCIONES RÁPIDAS (Section 6) -->
    <div class="bg-slate-900/50 border border-slate-800/60 rounded-3xl p-4 sm:p-5">
        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-3">ACCIONES RÁPIDAS</span>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
            <!-- Hablar -->
            <button 
                @click="quickMode = 'voice'; quickModalOpen = true"
                class="p-3 rounded-2xl bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-left transition-all group cursor-pointer"
            >
                <div class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                    🎙
                </div>
                <div class="text-xs font-bold text-white">Hablar</div>
                <div class="text-[10px] text-slate-400">Dictar a la IA</div>
            </button>

            <!-- Escribir -->
            <button 
                @click="quickMode = 'text'; quickModalOpen = true"
                class="p-3 rounded-2xl bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-left transition-all group cursor-pointer"
            >
                <div class="w-8 h-8 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                    ✍️
                </div>
                <div class="text-xs font-bold text-white">Escribir</div>
                <div class="text-[10px] text-slate-400">Texto desordenado</div>
            </button>

            <!-- Añadir Evento / Cita -->
            <a 
                href="{{ route('calendar.create') }}"
                class="p-3 rounded-2xl bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-left transition-all group"
            >
                <div class="w-8 h-8 rounded-xl bg-violet-500/10 text-violet-400 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                    📅
                </div>
                <div class="text-xs font-bold text-white">Añadir Cita</div>
                <div class="text-[10px] text-slate-400">Nuevo evento</div>
            </a>

            <!-- Importar PDF / Documento -->
            <a 
                href="{{ route('documents.index') }}"
                class="p-3 rounded-2xl bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-left transition-all group"
            >
                <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                    📄
                </div>
                <div class="text-xs font-bold text-white">Importar Doc</div>
                <div class="text-[10px] text-slate-400">PDF, Drive o texto</div>
            </a>
        </div>
    </div>

    <!-- DESPUÉS (Próximas actividades) -->
    @if ($upcomingEvents->count() > 0)
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-5 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">DESPUÉS</span>
                <a href="{{ route('calendar.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300">Ver todas</a>
            </div>

            <div class="space-y-3">
                @foreach ($upcomingEvents as $event)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-slate-700 transition-all">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-slate-800 flex flex-col items-center justify-center text-slate-300 shrink-0">
                                <span class="text-[9px] uppercase font-bold text-indigo-400">{{ $event->fecha->isoFormat('ddd') }}</span>
                                <span class="text-xs font-black leading-none">{{ $event->fecha->format('d') }}</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs sm:text-sm font-semibold text-white truncate">{{ $event->titulo }}</h4>
                                <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5">
                                    @if ($event->hora_inicio)
                                        <span>{{ substr($event->hora_inicio, 0, 5) }}</span>
                                    @endif
                                    @if ($event->ubicacion)
                                        <span>&bull;</span>
                                        <span class="truncate">📍 {{ $event->ubicacion }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase shrink-0
                            {{ $event->prioridad === 'URGENTE' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : '' }}
                            {{ $event->prioridad === 'IMPORTANTE' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : '' }}
                            {{ $event->prioridad === 'NORMAL' ? 'bg-slate-800 text-slate-400' : '' }}
                            {{ $event->prioridad === 'PUEDE_ESPERAR' ? 'bg-slate-800/50 text-slate-500' : '' }}
                        ">
                            {{ $event->prioridad }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- TAREAS IMPORTANTES y NO OLVIDAR -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- TAREAS IMPORTANTES -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-5 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] font-black uppercase tracking-wider text-rose-400">TAREAS IMPORTANTES</span>
                <a href="{{ route('tasks.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300">Ver tareas</a>
            </div>

            @if ($importantTasks->count() > 0)
                <div class="space-y-2.5">
                    @foreach ($importantTasks as $task)
                        <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-950/40 border border-slate-800/70">
                            <button 
                                wire:click="toggleTask({{ $task->id }})"
                                class="mt-0.5 w-4 h-4 rounded border {{ $task->estado === 'completada' ? 'bg-indigo-600 border-indigo-600' : 'border-slate-600 hover:border-indigo-400' }} flex items-center justify-center shrink-0 cursor-pointer transition-colors"
                            >
                                @if ($task->estado === 'completada')
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </button>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-slate-200 {{ $task->estado === 'completada' ? 'line-through text-slate-500' : '' }}">
                                    {{ $task->titulo }}
                                </p>
                                @if ($task->fecha_limite)
                                    <p class="text-[10px] text-slate-400 mt-0.5">
                                        Límite: {{ $task->fecha_limite->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>
                            <span class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase shrink-0
                                {{ $task->prioridad === 'URGENTE' ? 'bg-rose-500/20 text-rose-300' : 'bg-slate-800 text-slate-400' }}
                            ">
                                {{ $task->prioridad }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-500 py-4 text-center">No tienes tareas prioritarias pendientes 🎉</p>
            @endif
        </div>

        <!-- NO OLVIDAR (Recordatorios) -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-5 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] font-black uppercase tracking-wider text-amber-400">NO OLVIDAR</span>
                <span class="text-xs text-slate-500">Recordatorios</span>
            </div>

            @if ($reminders->count() > 0)
                <div class="space-y-2.5">
                    @foreach ($reminders as $reminder)
                        <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-950/40 border border-slate-800/70">
                            <span class="text-base shrink-0">🔔</span>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-slate-200 truncate">
                                    {{ $reminder->remindable?->titulo ?? 'Recordatorio personal' }}
                                </p>
                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    {{ $reminder->programado_para->format('H:i') }} ({{ $reminder->tipo }})
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-500 py-4 text-center">Sin recordatorios urgentes para hoy</p>
            @endif
        </div>
    </div>

    <!-- BANDEJA DE ENTRADA (Inbox pendiente de organizar) -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-5 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-indigo-400">BANDEJA DE ENTRADA</span>
                <p class="text-xs text-slate-400 mt-0.5">Notas, ideas o datos pendientes de estructurar</p>
            </div>
            <a href="{{ route('inbox.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300">
                Ir a Bandeja &rarr;
            </a>
        </div>

        @if ($inboxItems->count() > 0)
            <div class="space-y-2.5">
                @foreach ($inboxItems as $item)
                    <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800/80 hover:border-slate-700 transition-all flex items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase
                                    {{ $item->status === 'pendiente_analisis' ? 'bg-indigo-500/20 text-indigo-300' : 'bg-amber-500/20 text-amber-300' }}
                                ">
                                    {{ str_replace('_', ' ', $item->status) }}
                                </span>
                                <span class="text-[10px] text-slate-500">
                                    {{ $item->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-200 leading-relaxed line-clamp-2">
                                {{ $item->content }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-6 text-center border border-dashed border-slate-800 rounded-2xl">
                <span class="text-2xl mb-1 block">📥</span>
                <p class="text-xs font-medium text-slate-400">La bandeja de entrada está vacía</p>
                <button 
                    @click="quickMode = 'text'; quickModalOpen = true"
                    class="mt-2 text-xs font-semibold text-indigo-400 hover:text-indigo-300 cursor-pointer"
                >
                    + Capturar algo ahora
                </button>
            </div>
        @endif
    </div>
</div>
