<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100 antialiased selection:bg-indigo-500 selection:text-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Mi Asistente Personal') }}</title>
    
    <!-- PWA Settings -->
    <meta name="theme-color" content="#0b1120">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Mi Asistente">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    <!-- Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            -webkit-tap-highlight-color: transparent;
            overscroll-behavior-y: none;
        }
        /* Mobile safe area padding */
        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom, 1rem);
        }
        .pt-safe {
            padding-top: env(safe-area-inset-top, 0.5rem);
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-950 text-slate-100 pb-24 md:pb-0" x-data="{ quickModalOpen: false, quickMode: 'text' }">
    <!-- Top Header -->
    <header class="sticky top-0 z-40 bg-slate-950/80 backdrop-blur-xl border-b border-slate-800/80 pt-safe">
        <div class="max-w-4xl mx-auto px-4 h-14 sm:h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-sky-500 flex items-center justify-center shadow-md shadow-indigo-600/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-bold text-sm sm:text-base text-white tracking-tight block leading-tight">Mi Asistente</span>
                        <span class="text-[10px] text-slate-400 font-medium block">
                            {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}
                        </span>
                    </div>
                </a>
            </div>

            <!-- Quick Action Icons & Profile -->
            <div class="flex items-center gap-1 sm:gap-2">
                <!-- Audio / Voice Trigger -->
                <button 
                    @click="quickMode = 'voice'; quickModalOpen = true"
                    title="Entrada por voz"
                    class="p-2 sm:px-3 sm:py-1.5 rounded-xl bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 border border-indigo-500/20 text-xs font-semibold flex items-center gap-1.5 transition-all cursor-pointer"
                >
                    <span class="text-base sm:text-sm">🎙</span>
                    <span class="hidden sm:inline">Hablar</span>
                </button>

                <!-- Document Trigger -->
                <a 
                    href="{{ route('documents.index') }}"
                    title="Importar Documento"
                    class="p-2 sm:px-3 sm:py-1.5 rounded-xl bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/20 text-xs font-semibold flex items-center gap-1.5 transition-all"
                >
                    <span class="text-base sm:text-sm">📄</span>
                    <span class="hidden sm:inline">Docs</span>
                </a>

                <!-- User Dropdown / Logout -->
                <form method="POST" action="{{ route('logout') }}" class="inline ml-1">
                    @csrf
                    <button 
                        type="submit" 
                        title="Cerrar sesión"
                        class="p-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 border border-transparent hover:border-rose-500/20 transition-all cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 py-5 sm:py-6">
        @if (session('success'))
            <div class="mb-5 p-3.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs sm:text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Bottom Mobile Navigation Bar (Mobile-first, optimized for Samsung S24 & handheld use) -->
    <nav class="fixed bottom-0 inset-x-0 z-40 bg-slate-950/90 backdrop-blur-2xl border-t border-slate-800/90 pb-safe md:hidden">
        <div class="max-w-md mx-auto px-2 py-1.5 flex items-center justify-around text-[10px] font-medium text-slate-400">
            <!-- HOY -->
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-1 px-2.5 rounded-xl transition-colors {{ request()->routeIs('dashboard') ? 'text-indigo-400 font-bold' : 'hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>HOY</span>
            </a>

            <!-- CALENDARIO -->
            <a href="{{ route('calendar.index') }}" class="flex flex-col items-center py-1 px-2.5 rounded-xl transition-colors {{ request()->routeIs('calendar.*') ? 'text-indigo-400 font-bold' : 'hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>CALENDARIO</span>
            </a>

            <!-- QUICK CAPTURE BUTTON (Center Highlight) -->
            <button 
                @click="quickMode = 'text'; quickModalOpen = true"
                class="-mt-5 w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-sky-500 text-white flex items-center justify-center shadow-lg shadow-indigo-500/40 border-2 border-slate-950 active:scale-95 transition-transform cursor-pointer"
                title="Añadir rápido"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
            </button>

            <!-- TAREAS -->
            <a href="{{ route('tasks.index') }}" class="flex flex-col items-center py-1 px-2.5 rounded-xl transition-colors {{ request()->routeIs('tasks.*') ? 'text-indigo-400 font-bold' : 'hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span>TAREAS</span>
            </a>

            <!-- BANDEJA -->
            <a href="{{ route('inbox.index') }}" class="flex flex-col items-center py-1 px-2.5 rounded-xl transition-colors {{ request()->routeIs('inbox.*') ? 'text-indigo-400 font-bold' : 'hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <span>BANDEJA</span>
            </a>
        </div>
    </nav>

    <!-- Desktop Navigation Bar (Header sub-tabs for tablet & desktop) -->
    <div class="hidden md:block fixed bottom-6 left-1/2 -translate-x-1/2 z-40 bg-slate-900/90 backdrop-blur-2xl border border-slate-800 rounded-full shadow-2xl px-3 py-2">
        <div class="flex items-center gap-1 text-xs font-semibold">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-full transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">HOY</a>
            <a href="{{ route('calendar.index') }}" class="px-4 py-2 rounded-full transition-all {{ request()->routeIs('calendar.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">CALENDARIO</a>
            <a href="{{ route('tasks.index') }}" class="px-4 py-2 rounded-full transition-all {{ request()->routeIs('tasks.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">TAREAS</a>
            <a href="{{ route('inbox.index') }}" class="px-4 py-2 rounded-full transition-all {{ request()->routeIs('inbox.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">BANDEJA</a>
            <a href="{{ route('documents.index') }}" class="px-4 py-2 rounded-full transition-all {{ request()->routeIs('documents.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">DOCUMENTOS</a>
            <a href="{{ route('assistant.index') }}" class="px-4 py-2 rounded-full transition-all {{ request()->routeIs('assistant.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">ASISTENTE</a>
            <a href="{{ route('settings.index') }}" class="px-4 py-2 rounded-full transition-all {{ request()->routeIs('settings.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">AJUSTES</a>
        </div>
    </div>

    <!-- Quick Capture Modal (Bottom Sheet on Mobile, Modal on Desktop) -->
    <div 
        x-show="quickModalOpen" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-black/70 backdrop-blur-sm"
        @keydown.escape.window="quickModalOpen = false"
    >
        <div 
            @click.outside="quickModalOpen = false"
            x-show="quickModalOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-4 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-4 sm:scale-95"
            class="w-full sm:max-w-lg bg-slate-900 border border-slate-800 rounded-t-3xl sm:rounded-3xl p-5 sm:p-6 shadow-2xl max-h-[90vh] overflow-y-auto"
        >
            <livewire:inbox.quick-capture @captured="quickModalOpen = false" />
        </div>
    </div>

    @livewireScripts
</body>
</html>
