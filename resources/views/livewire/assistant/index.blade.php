<div class="h-[calc(100vh-12rem)] flex flex-col bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
    <!-- Header Asistente -->
    <div class="p-4 border-b border-slate-800 flex items-center justify-between bg-slate-950/40">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-sky-500 flex items-center justify-center text-white text-base shadow-md">
                🤖
            </div>
            <div>
                <h2 class="text-sm font-bold text-white leading-tight">Asistente Personal IA</h2>
                <span class="text-[10px] text-emerald-400 flex items-center gap-1 font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Motor activo
                </span>
            </div>
        </div>
        <div class="flex gap-1.5">
            <button 
                wire:click="$set('query', 'Mi día')"
                class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-[10px] font-semibold text-slate-300 cursor-pointer"
            >
                «Mi día»
            </button>
            <button 
                wire:click="$set('query', 'Organízame mañana')"
                class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-[10px] font-semibold text-slate-300 cursor-pointer"
            >
                «Organízame mañana»
            </button>
        </div>
    </div>

    <!-- Conversación -->
    <div class="flex-1 p-4 overflow-y-auto space-y-4">
        @foreach ($conversation as $msg)
            <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[85%] rounded-2xl p-3.5 text-xs sm:text-sm leading-relaxed {{ $msg['role'] === 'user' ? 'bg-indigo-600 text-white rounded-br-xs' : 'bg-slate-950 border border-slate-800 text-slate-200 rounded-bl-xs' }}">
                    <div class="whitespace-pre-wrap">{{ $msg['text'] }}</div>
                    <span class="block text-[9px] mt-1 text-right {{ $msg['role'] === 'user' ? 'text-indigo-200' : 'text-slate-500' }}">
                        {{ $msg['time'] }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Input Form -->
    <form wire:submit="send" class="p-3 bg-slate-950/60 border-t border-slate-800 flex gap-2">
        <input 
            type="text"
            wire:model="query"
            placeholder="Pregúntame o dicta tus citas y compromisos..."
            class="flex-1 px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-500 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
        <button 
            type="submit"
            class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold cursor-pointer"
        >
            Enviar
        </button>
    </form>
</div>
