<?php

namespace App\Livewire\Calendar;

use App\Models\AuditLog;
use App\Models\Event;
use App\Models\InboxItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Nuevo Evento - Mi Asistente')]
class Create extends Component
{
    public ?int $inbox_item_id = null;

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

        $this->inbox_item_id = request()->query('inbox_item_id');
        if ($this->inbox_item_id) {
            $inboxItem = InboxItem::where('user_id', Auth::id())->find($this->inbox_item_id);
            if ($inboxItem) {
                $this->descripcion = $inboxItem->content;
            }
        }
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

            // Fetch events on the same day
            $dayEvents = Event::where('user_id', Auth::id())
                ->where('fecha', $this->fecha)
                ->whereNotNull('hora_inicio')
                ->get();

            foreach ($dayEvents as $e) {
                $eStart = Carbon::parse($e->fecha.' '.$e->hora_inicio);
                $eEnd = $eStart->copy()->addMinutes($e->duracion ?: 60);

                // Add 15 min buffer to check
                if ($start->lt($eEnd->copy()->addMinutes(15)) && $end->gt($eStart->copy()->subMinutes(15))) {
                    $conflict = true;
                    break;
                }
            }
        }

        $event = Event::create([
            'user_id' => Auth::id(),
            'inbox_item_id' => $this->inbox_item_id,
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

        if ($this->inbox_item_id) {
            InboxItem::where('id', $this->inbox_item_id)->update(['status' => 'convertido_evento']);
        }

        AuditLog::log('evento_creado', $event, [
            'titulo' => $event->titulo,
            'fecha' => $event->fecha->toDateString(),
        ]);

        if (isset($conflict) && $conflict) {
            session()->flash('warning', 'Evento añadido, pero hay un solapamiento (o menos de 15 minutos de margen) con otro evento ese día. Considera moverlo 30 minutos antes o después.');
        } else {
            session()->flash('success', 'Evento añadido al calendario.');
        }

        return redirect()->route('calendar.index');
    }

    public function render()
    {
        return view('livewire.calendar.create');
    }
}
