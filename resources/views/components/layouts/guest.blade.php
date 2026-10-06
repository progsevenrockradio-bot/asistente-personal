<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Mi Asistente Personal') }}</title>
    
    <!-- PWA meta tags -->
    <meta name="theme-color" content="#0f172a">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body class="h-full antialiased flex flex-col justify-center items-center p-4 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black">
    <div class="w-full max-w-md mx-auto">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-sky-500 shadow-lg shadow-indigo-500/25 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">Mi Asistente</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Tu centro de organización y productividad personal</p>
        </div>

        <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-black/50">
            {{ $slot }}
        </div>

        <div class="mt-8 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} Mi Asistente Personal &bull; Seguro y Privado
        </div>
    </div>

    @livewireScripts
</body>
</html>
