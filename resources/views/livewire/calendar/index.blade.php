<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Calendario</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Citas, conciertos, compromisos y eventos organizados</p>
        </div>
        <div class="flex items-center gap-2">
            <a 
                href="{{ route('calendar.create') }}"
                class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-sky-600 hover:from-indigo-500 hover:to-sky-500 text-white font-semibold text-xs shadow-md shadow-indigo-600/30 flex items-center gap-1.5"
            >
                <span>+</span>
                <span>Añadir Evento</span>
            </a>
        </div>
    </div>

    <!-- Navegación de Mes -->
    <div class="flex items-center justify-between bg-slate-900/80 border border-slate-800 rounded-2xl p-3">
        <button 
            wire:click="previousMonth"
            class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer"
        >
            &larr; Anterior
        </button>
        <div class="text-center">
            <h2 class="text-base font-bold text-white uppercase tracking-wider">
                {{ $startOfMonth->locale('es')->isoFormat('MMMM YYYY') }}
            </h2>
        </div>
        <button 
            wire:click="nextMonth"
            class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer"
        >
            Siguiente &rarr;
        </button>
    </div>

    <!-- Lista de Eventos Agrupados por Día -->
    <div class="space-y-4">
        @if ($eventsByDay->count() > 0)
            @foreach ($eventsByDay as $date => $dayEvents)
                @php
                    $carbonDate = \Carbon\Carbon::parse($date);
                    $isToday = $carbonDate->isToday();
                @endphp
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider {{ $isToday ? 'text-indigo-400' : 'text-slate-400' }}">
                            {{ $carbonDate->locale('es')->isoFormat('dddd, D [de] MMMM') }}
                        </span>
                        @if ($isToday)
                            <span class="px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 text-[10px] font-bold">HOY</span>
                        @endif
                    </div>

                    <div class="space-y-2">
                        @foreach ($dayEvents as $event)
                            <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 transition-all flex items-center justify-between gap-3">
                                <div class="min-w-0 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-base shrink-0">
                                        📅
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-xs sm:text-sm font-semibold text-white truncate">{{ $event->titulo }}</h4>
                                        <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5">
                                            @if ($event->hora_inicio)
                                                <span>{{ substr($event->hora_inicio, 0, 5) }}</span>
                                            @endif
                                            @if ($event->duracion)
                                                <span>({{ $event->duracion }} min)</span>
                                            @endif
                                            @if ($event->ubicacion)
                                                <span>&bull;</span>
                                                <span class="truncate">📍 {{ $event->ubicacion }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-[9px] px-2 py-0.5 rounded-full font-bold uppercase
                                        {{ $event->prioridad === 'URGENTE' ? 'bg-rose-500/20 text-rose-300' : 'bg-slate-800 text-slate-400' }}
                                    ">
                                        {{ $event->prioridad }}
                                    </span>
                                    <a href="{{ route('calendar.edit', $event->id) }}" class="p-1.5 text-slate-500 hover:text-indigo-400 rounded-lg cursor-pointer" title="Editar evento">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <button 
                                        wire:click="deleteEvent({{ $event->id }})"
                                        wire:confirm="¿Deseas eliminar este evento?"
                                        class="p-1.5 text-slate-500 hover:text-rose-400 rounded-lg cursor-pointer"
                                        title="Eliminar evento"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="py-12 text-center border border-dashed border-slate-800 rounded-3xl">
                <span class="text-3xl mb-2 block">🗓</span>
                <p class="text-sm font-semibold text-slate-300">No hay eventos en este mes</p>
                <a href="{{ route('calendar.create') }}" class="mt-2 inline-block text-xs font-semibold text-indigo-400 hover:text-indigo-300">
                    + Añadir tu primer evento
                </a>
            </div>
        @endif
    </div>
</div>
