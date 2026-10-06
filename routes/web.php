<?php

use App\Livewire\Assistant\Index as AssistantIndex;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Calendar\Create as CalendarCreate;
use App\Livewire\Calendar\Index as CalendarIndex;
use App\Livewire\Dashboard;
use App\Livewire\Documents\Index as DocumentsIndex;
use App\Livewire\Inbox\Index as InboxIndex;
use App\Livewire\Settings\Index as SettingsIndex;
use App\Livewire\Tasks\Index as TasksIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Mi Asistente Personal
|--------------------------------------------------------------------------
*/

// PWA assets
Route::get('/manifest.json', function () {
    return response()->file(public_path('manifest.json'), [
        'Content-Type' => 'application/json',
    ]);
});

Route::get('/offline.html', function () {
    return response()->file(public_path('offline.html'));
});

// Rutas de invitados (Autenticación)
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// Rutas protegidas (Requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/inbox', InboxIndex::class)->name('inbox.index');

    Route::get('/calendar', CalendarIndex::class)->name('calendar.index');
    Route::get('/calendar/create', CalendarCreate::class)->name('calendar.create');

    Route::get('/tasks', TasksIndex::class)->name('tasks.index');
    Route::get('/documents', DocumentsIndex::class)->name('documents.index');
    Route::get('/assistant', AssistantIndex::class)->name('assistant.index');
    Route::get('/settings', SettingsIndex::class)->name('settings.index');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});
