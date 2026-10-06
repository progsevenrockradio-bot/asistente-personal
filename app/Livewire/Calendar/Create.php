<?php

namespace App\Livewire\Calendar;

use App\Models\AuditLog;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Nuevo Evento - Mi Asistente')]
class Create extends Component
{
    public string $titulo = '';

    public ?string $descripcion = '';

    public string $fecha = '';

    public ?string $hora_inicio = '';

    public ?string $hora_fin = '';

    public ?int $duracion = 60;

    public ?string $ubicacion = '';

    public string $prioridad = 'NORMAL';

    public ?string $notas = '';

    public function mount()
    {
        $this->fecha = Carbon::today()->format('Y-m-d');
        $this->hora_inicio = Carbon::now()->addHour()->startOfHour()->format('H:i');
    }

    protected function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'fecha' => ['required', 'date'],
            'hora_inicio' => ['nullable'],
            'hora_fin' => ['nullable'],
            'duracion' => ['nullable', 'integer', 'min:5', 'max:1440'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'prioridad' => ['required', 'in:URGENTE,IMPORTANTE,NORMAL,PUEDE_ESPERAR'],
            'descripcion' => ['nullable', 'string'],
            'notas' => ['nullable', 'string'],
        ];
    }

    public function save()
    {
        $this->validate();

        $event = Event::create([
            'user_id' => Auth::id(),
            'titulo' => trim($this->titulo),
            'descripcion' => $this->descripcion ? trim($this->descripcion) : null,
            'fecha' => $this->fecha,
            'hora_inicio' => $this->hora_inicio ?: null,
            'hora_fin' => $this->hora_fin ?: null,
            'duracion' => $this->duracion ?: 60,
            'ubicacion' => $this->ubicacion ? trim($this->ubicacion) : null,
            'prioridad' => $this->prioridad,
            'estado' => 'confirmado',
            'origen' => 'manual',
            'notas' => $this->notas ? trim($this->notas) : null,
        ]);

        AuditLog::log('evento_creado', $event, [
            'titulo' => $event->titulo,
            'fecha' => $event->fecha->toDateString(),
        ]);

        session()->flash('success', 'Evento añadido al calendario.');

        return redirect()->route('calendar.index');
    }

    public function render()
    {
        return view('livewire.calendar.create');
    }
}
