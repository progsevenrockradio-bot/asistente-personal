<?php

namespace App\Livewire\Assistant;

use App\Models\Event;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Asistente IA - Mi Asistente')]
class Index extends Component
{
    public string $query = '';

    public array $conversation = [];

    public function mount()
    {
        $this->conversation = [
            [
                'role' => 'assistant',
                'text' => 'Hola. Soy tu Asistente Personal. Puedes pedirme "Mi día", "Organízame mañana", o dictarme cualquier información que quieras que clasifique y agende.',
                'time' => Carbon::now()->format('H:i'),
            ],
        ];
    }

    public function send()
    {
        if (! trim($this->query)) {
            return;
        }

        $userText = trim($this->query);
        $this->conversation[] = [
            'role' => 'user',
            'text' => $userText,
            'time' => Carbon::now()->format('H:i'),
        ];

        $this->reset('query');

        // Respuesta provisional estructurada mientras se configura el servicio de proveedor de IA en Fase 10
        $lower = strtolower($userText);
        $reply = '';

        if (str_contains($lower, 'mi día') || str_contains($lower, 'hoy')) {
            $events = Event::where('user_id', Auth::id())
                ->whereDate('fecha', Carbon::today())
                ->orderBy('hora_inicio')
                ->get();

            $tasks = Task::where('user_id', Auth::id())
                ->where('estado', '!=', 'completada')
                ->get();

            $reply = "📅 **Resumen de Hoy:**\n";
            if ($events->count() > 0) {
                foreach ($events as $e) {
                    $reply .= '• '.($e->hora_inicio ? substr($e->hora_inicio, 0, 5).' ' : '').$e->titulo."\n";
                }
            } else {
                $reply .= "No tienes eventos agendados para hoy.\n";
            }
            $reply .= "\n📋 **Tareas prioritarias:** ".$tasks->count().' pendientes.';
        } elseif (str_contains($lower, 'organízame') || str_contains($lower, 'mañana')) {
            $tomorrow = Carbon::tomorrow();
            $events = Event::where('user_id', Auth::id())
                ->whereDate('fecha', $tomorrow)
                ->orderBy('hora_inicio')
                ->get();

            $reply = '🗓 **Planificación para Mañana ('.$tomorrow->isoFormat('D MMM')."):**\n";
            if ($events->count() > 0) {
                foreach ($events as $e) {
                    $reply .= '• '.($e->hora_inicio ? substr($e->hora_inicio, 0, 5).' ' : '').$e->titulo."\n";
                }
            } else {
                $reply .= '• Mañana está completamente despejado para avanzar en tareas.';
            }
        } else {
            $reply = 'He recibido tu información. El motor de interpretación de lenguaje natural la evaluará para detectar citas, fechas y posibles datos faltantes sin inventar información.';
        }

        $this->conversation[] = [
            'role' => 'assistant',
            'text' => $reply,
            'time' => Carbon::now()->format('H:i'),
        ];
    }

    public function render()
    {
        return view('livewire.assistant.index');
    }
}
