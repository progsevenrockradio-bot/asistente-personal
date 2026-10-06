<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Documentos & PDF</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Sube recetas médicas, entradas de conciertos o documentos de NotebookLM</p>
        </div>
    </div>

    <!-- Zona de Subida / Importación -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-5 space-y-4">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300">Subir nuevo documento</h3>
        <form wire:submit="upload" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2">
                    <input 
                        type="file" 
                        wire:model="uploadedFile"
                        class="w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer"
                    >
                </div>
                <div>
                    <select 
                        wire:model="categoria"
                        class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white"
                    >
                        <option value="general">📁 General</option>
                        <option value="medico">🩺 Médico / Salud</option>
                        <option value="concierto">🎟 Concierto / Entradas</option>
                        <option value="trabajo">💼 Trabajo</option>
                        <option value="estudio">📚 NotebookLM / Estudio</option>
                    </select>
                </div>
            </div>

            @error('uploadedFile')
                <p class="text-xs text-rose-400">{{ $message }}</p>
            @enderror

            <div class="flex justify-end pt-1">
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-sky-600 hover:from-indigo-500 hover:to-sky-500 text-white font-semibold text-xs shadow-md cursor-pointer disabled:opacity-50"
                >
                    <span wire:loading.remove>Importar Documento</span>
                    <span wire:loading>Subiendo archivo...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Listado de Documentos -->
    <div class="space-y-3">
        @if ($documents->count() > 0)
            @foreach ($documents as $doc)
                <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 transition-all flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-lg shrink-0">
                            {{ $doc->categoria === 'medico' ? '🩺' : ($doc->tipo === 'pdf' ? '📕' : '📄') }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-semibold text-white truncate">{{ $doc->nombre }}</h4>
                            <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5">
                                <span class="uppercase font-bold text-indigo-400">{{ $doc->tipo }}</span>
                                <span>&bull;</span>
                                <span>{{ round($doc->tamano / 1024, 1) }} KB</span>
                                <span>&bull;</span>
                                <span>{{ $doc->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-[9px] px-2 py-0.5 rounded-full font-bold uppercase
                            {{ $doc->estado_procesamiento === 'completado' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}
                        ">
                            {{ $doc->estado_procesamiento }}
                        </span>
                    </div>
                </div>
            @endforeach
        @else
            <div class="py-12 text-center border border-dashed border-slate-800 rounded-3xl">
                <span class="text-3xl mb-2 block">📄</span>
                <p class="text-sm font-semibold text-slate-300">No hay documentos importados</p>
                <p class="text-xs text-slate-500 mt-1">Sube archivos PDF o exportaciones de NotebookLM</p>
            </div>
        @endif
    </div>
</div>
