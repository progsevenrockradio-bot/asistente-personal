<?php

namespace App\Livewire\Settings;

use App\Models\AuditLog;
use App\Models\GoogleAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Ajustes - Mi Asistente')]
class Index extends Component
{
    public string $activeTab = 'cuenta'; // cuenta, google, recordatorios, ia, pwa, privacidad

    // Perfil
    public string $name = '';

    public string $email = '';

    public string $currentPassword = '';

    public string $newPassword = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        AuditLog::log('perfil_actualizado', $user);
        session()->flash('success', 'Perfil actualizado con éxito.');
    }

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => ['required', 'current_password'],
            'newPassword' => ['required', 'string', 'min:8'],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->reset(['currentPassword', 'newPassword']);
        AuditLog::log('contraseña_actualizada', $user);
        session()->flash('success', 'Contraseña actualizada.');
    }

    public function render()
    {
        $googleAccounts = GoogleAccount::where('user_id', Auth::id())->get();

        return view('livewire.settings.index', [
            'googleAccounts' => $googleAccounts,
        ]);
    }
}
