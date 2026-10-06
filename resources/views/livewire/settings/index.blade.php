<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-white tracking-tight">Ajustes & Configuración</h1>
        <p class="text-xs sm:text-sm text-slate-400 mt-1">Personaliza tu cuenta, conexiones externas y reglas del asistente</p>
    </div>

    <!-- Navegación de Pestañas -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-800 text-xs font-semibold scrollbar-none">
        <button 
            wire:click="$set('activeTab', 'cuenta')"
            class="px-4 py-2 rounded-xl whitespace-nowrap transition-all cursor-pointer {{ $activeTab === 'cuenta' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white bg-slate-900' }}"
        >
            👤 Cuenta
        </button>
        <button 
            wire:click="$set('activeTab', 'google')"
            class="px-4 py-2 rounded-xl whitespace-nowrap transition-all cursor-pointer {{ $activeTab === 'google' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white bg-slate-900' }}"
        >
            🔗 Google Calendar & Drive
        </button>
        <button 
            wire:click="$set('activeTab', 'recordatorios')"
            class="px-4 py-2 rounded-xl whitespace-nowrap transition-all cursor-pointer {{ $activeTab === 'recordatorios' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white bg-slate-900' }}"
        >
            🔔 Recordatorios
        </button>
        <button 
            wire:click="$set('activeTab', 'ia')"
            class="px-4 py-2 rounded-xl whitespace-nowrap transition-all cursor-pointer {{ $activeTab === 'ia' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white bg-slate-900' }}"
        >
            🤖 Proveedor IA
        </button>
        <button 
            wire:click="$set('activeTab', 'pwa')"
            class="px-4 py-2 rounded-xl whitespace-nowrap transition-all cursor-pointer {{ $activeTab === 'pwa' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white bg-slate-900' }}"
        >
            📱 PWA / Móvil
        </button>
        <button 
            wire:click="$set('activeTab', 'privacidad')"
            class="px-4 py-2 rounded-xl whitespace-nowrap transition-all cursor-pointer {{ $activeTab === 'privacidad' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white bg-slate-900' }}"
        >
            🛡 Privacidad & Datos
        </button>
    </div>

    <!-- Contenido de la pestaña activa -->
    @if ($activeTab === 'cuenta')
        <div class="space-y-6">
            <!-- Datos de Perfil -->
            <form wire:submit="updateProfile" class="bg-slate-900/80 border border-slate-800 rounded-3xl p-5 sm:p-6 space-y-4">
                <h3 class="text-sm font-bold text-white">Perfil de Usuario</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Nombre</label>
                        <input 
                            type="text" 
                            wire:model="name"
                            required
                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                        @error('name') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Email</label>
                        <input 
                            type="email" 
                            wire:model="email"
                            required
                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                        @error('email') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs cursor-pointer">
                        Guardar Cambios
                    </button>
                </div>
            </form>

            <!-- Cambiar Contraseña -->
            <form wire:submit="updatePassword" class="bg-slate-900/80 border border-slate-800 rounded-3xl p-5 sm:p-6 space-y-4">
                <h3 class="text-sm font-bold text-white">Seguridad de la Cuenta</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Contraseña Actual</label>
                        <input 
                            type="password" 
                            wire:model="currentPassword"
                            required
                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                        @error('currentPassword') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Nueva Contraseña</label>
                        <input 
                            type="password" 
                            wire:model="newPassword"
                            required
                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                        @error('newPassword') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs cursor-pointer">
                        Actualizar Contraseña
                    </button>
                </div>
            </form>
        </div>
    @elseif ($activeTab === 'google')
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-white">Cuentas de Google Conectadas</h3>
            <p class="text-xs text-slate-400">
                La conexión OAuth 2.0 permite sincronizar eventos con Google Calendar (y por tanto tu Samsung Galaxy S24) y explorar archivos en Google Drive.
            </p>

            <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🌐</span>
                    <div>
                        <h4 class="text-xs font-bold text-white">Google OAuth 2.0 (Fase 6 & 7)</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Requiere configurar GOOGLE_CLIENT_ID y GOOGLE_CLIENT_SECRET en .env</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-slate-800 text-[10px] font-bold text-slate-400">
                    Pendiente config
                </span>
            </div>
        </div>
    @elseif ($activeTab === 'pwa')
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-white">Aplicación Web Progresiva (PWA)</h3>
            <p class="text-xs text-slate-400">
                Esta app está diseñada para instalarse directamente en tu Samsung Galaxy S24 (Android) desde Chrome o Samsung Internet.
            </p>
            <div class="p-4 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-xs text-slate-200 space-y-2">
                <p class="font-bold text-indigo-300">📱 Pasos para instalar en Samsung Galaxy S24:</p>
                <ol class="list-decimal list-inside space-y-1 text-slate-300">
                    <li>Abre el navegador Chrome o Samsung Internet.</li>
                    <li>Toca en el menú de tres puntos (o botón de opciones).</li>
                    <li>Selecciona <strong>"Instalar aplicación"</strong> o <strong>"Añadir a la pantalla de inicio"</strong>.</li>
                </ol>
            </div>
        </div>
    @elseif ($activeTab === 'privacidad')
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-white">Privacidad y Gestión de Datos</h3>
            <p class="text-xs text-slate-400">
                Tus datos, citas, tareas y documentos se almacenan localmente y de forma segura en tu propio servidor. Ningún dato se envía a terceros sin tu expresa autorización.
            </p>
        </div>
    @else
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6">
            <p class="text-xs text-slate-400">Configuración disponible en las siguientes fases.</p>
        </div>
    @endif
</div>
