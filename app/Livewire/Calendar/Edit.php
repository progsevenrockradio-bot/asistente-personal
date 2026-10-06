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
#[Title('Editar Evento - Mi Asistente')]
class Edit extends Component
{
    public Event $event;

    public string $titulo = '';

    public ?string $descripcion = '';

    public string $fecha = '';

    public ?string $hora_inicio = '';

    public ?string $hora_fin = '';

    public ?int $duracion = 60;

    public ?string $ubicacion = '';

    public string $prioridad = 'NORMAL';

    public ?string $notas = '';

    public function mount(int $id)
    {
        $this->event = Event::where('user_id', Auth::id())->findOrFail($id);

        $this->titulo = $this->event->titulo;
        $this->descripcion = $this->event->descripcion;
        $this->fecha = $this->event->fecha->format('Y-m-d');
        $this->hora_inicio = $this->event->hora_inicio ? substr($this->event->hora_inicio, 0, 5) : '';
        $this->hora_fin = $this->event->hora_fin ? substr($this->event->hora_fin, 0, 5) : '';
        $this->duracion = $this->event->duracion;
        $this->ubicacion = $this->event->ubicacion;
        $this->prioridad = $this->event->prioridad;
        $this->notas = $this->event->notas;
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

        $conflict = null;
        if ($this->hora_inicio) {
            $start = Carbon::parse($this->fecha.' '.$this->hora_inicio);
            $end = $start->copy()->addMinutes($this->duracion ?: 60);

            $dayEvents = Event::where('user_id', Auth::id())
                ->where('fecha', $this->fecha)
                ->whereNotNull('hora_inicio')
                ->where('id', '!=', $this->event->id)
                ->get();

            foreach ($dayEvents as $e) {
                $eStart = Carbon::parse($e->fecha.' '.$e->hora_inicio);
                $eEnd = $eStart->copy()->addMinutes($e->duracion ?: 60);

                if ($start->lt($eEnd->copy()->addMinutes(15)) && $end->gt($eStart->copy()->subMinutes(15))) {
                    $conflict = true;
                    break;
                }
            }
        }

        $this->event->update([
            'titulo' => trim($this->titulo),
            'descripcion' => $this->descripcion ? trim($this->descripcion) : null,
            'fecha' => $this->fecha,
            'hora_inicio' => $this->hora_inicio ?: null,
            'hora_fin' => $this->hora_fin ?: null,
            'duracion' => $this->duracion ?: 60,
            'ubicacion' => $this->ubicacion ? trim($this->ubicacion) : null,
            'prioridad' => $this->prioridad,
            'notas' => $this->notas ? trim($this->notas) : null,
        ]);

        AuditLog::log('evento_actualizado', $this->event, [
            'titulo' => $this->event->titulo,
            'fecha' => $this->event->fecha->toDateString(),
        ]);

        if (isset($conflict) && $conflict) {
            session()->flash('warning', 'Evento actualizado, pero ten cuidado: hay un solapamiento o menos de 15 minutos de margen con otro evento ese día.');
        } else {
            session()->flash('success', 'Evento actualizado correctamente.');
        }

        return redirect()->route('calendar.index');
    }

    public function render()
    {
        return view('livewire.calendar.edit');
    }
}
