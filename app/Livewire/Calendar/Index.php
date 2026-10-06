<?php

namespace App\Livewire\Calendar;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Calendario - Mi Asistente')]
class Index extends Component
{
    public string $currentMonth;

    public function mount()
    {
        $this->currentMonth = Carbon::now()->format('Y-m');
    }

    public function previousMonth()
    {
        $this->currentMonth = Carbon::parse($this->currentMonth.'-01')->subMonth()->format('Y-m');
    }

    public function nextMonth()
    {
        $this->currentMonth = Carbon::parse($this->currentMonth.'-01')->addMonth()->format('Y-m');
    }

    public function todayMonth()
    {
        $this->currentMonth = Carbon::now()->format('Y-m');
    }

    public function deleteEvent(int $id)
    {
        $event = Event::where('user_id', Auth::id())->findOrFail($id);
        $event->delete();
        session()->flash('success', 'Evento eliminado del calendario.');
    }

    public function render()
    {
        $startOfMonth = Carbon::parse($this->currentMonth.'-01')->startOfMonth();
        $endOfMonth = Carbon::parse($this->currentMonth.'-01')->endOfMonth();

        $events = Event::where('user_id', Auth::id())
            ->whereBetween('fecha', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy(fn ($event) => $event->fecha->format('Y-m-d'));

        return view('livewire.calendar.index', [
            'startOfMonth' => $startOfMonth,
            'endOfMonth' => $endOfMonth,
            'eventsByDay' => $events,
        ]);
    }
}
