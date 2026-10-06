<div x-data="{
    recording: false,
    recognition: null,
    speechSupported: false,
    initSpeech() {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (SpeechRecognition) {
            this.speechSupported = true;
            this.recognition = new SpeechRecognition();
            this.recognition.continuous = true;
            this.recognition.interimResults = true;
            this.recognition.lang = 'es-ES';

            this.recognition.onresult = (event) => {
                let transcript = '';
                for (let i = event.resultIndex; i < event.results.length; ++i) {
                    transcript += event.results[i][0].transcript;
                }
                const current = $wire.get('content') || '';
                $wire.set('content', current ? current + ' ' + transcript : transcript);
            };

            this.recognition.onerror = (event) => {
                console.error('Speech error:', event.error);
                this.recording = false;
            };

            this.recognition.onend = () => {
                this.recording = false;
            };
        }
    },
    toggleSpeech() {
        if (!this.speechSupported) {
            alert('Tu navegador no soporta entrada de voz directa. Puedes escribir el texto.');
            return;
        }
        if (this.recording) {
            this.recognition.stop();
            this.recording = false;
            $wire.set('source', 'voz');
        } else {
            this.recognition.start();
            this.recording = true;
            $wire.set('source', 'voz');
        }
    }
}" x-init="initSpeech()">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <h3 class="font-bold text-base text-white tracking-tight">Captura Rápida</h3>
        </div>
        <div class="flex items-center gap-1">
            <button 
                type="button"
                @click="toggleSpeech()"
                class="px-2.5 py-1 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-all cursor-pointer"
                :class="recording ? 'bg-rose-500/20 text-rose-400 border border-rose-500/30 animate-pulse' : 'bg-slate-800 text-slate-300 hover:text-white'"
            >
                <span>🎙</span>
                <span x-text="recording ? 'Grabando...' : 'Hablar'"></span>
            </button>
        </div>
    </div>

    <form wire:submit="save">
        <div class="relative">
            <textarea
                wire:model="content"
                rows="4"
                required
                placeholder="Escribe o dicta libremente:&#10;«El viernes tengo médico a las 10 y el sábado tengo un concierto...»"
                class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-2xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none"
            ></textarea>
        </div>
        @error('content')
            <p class="mt-1 text-xs text-rose-400 font-medium">{{ $message }}</p>
        @enderror

        <div class="mt-4 flex items-center justify-end gap-2">
            <button
                type="button"
                @click="$dispatch('captured')"
                class="px-4 py-2.5 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer"
            >
                Cancelar
            </button>
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-sky-600 hover:from-indigo-500 hover:to-sky-500 text-white font-semibold text-xs shadow-md shadow-indigo-600/30 flex items-center gap-2 cursor-pointer disabled:opacity-50"
            >
                <span wire:loading.remove>Guardar en Inbox</span>
                <span wire:loading class="inline-flex items-center gap-1.5">
                    <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Guardando...
                </span>
            </button>
        </div>
    </form>
</div>
