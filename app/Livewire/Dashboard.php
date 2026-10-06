<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\InboxItem;
use App\Models\PendingQuestion;
use App\Models\Reminder;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Hoy - Mi Asistente Personal')]
class Dashboard extends Component
{
    #[On('inbox-updated')]
    public function refresh()
    {
        // Re-render
    }

    public function toggleTask(int $taskId)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($taskId);

        if ($task->estado === 'completada') {
            $task->update([
                'estado' => 'pendiente',
                'completed_at' => null,
            ]);
        } else {
            $task->update([
                'estado' => 'completada',
                'completed_at' => now(),
            ]);
        }
    }

    public function render()
    {
        $userId = Auth::id();
        $today = Carbon::today();
        $now = Carbon::now();

        // Eventos de hoy
        $todayEvents = Event::where('user_id', $userId)
            ->whereDate('fecha', $today)
            ->where('estado', '!=', 'cancelado')
            ->orderBy('hora_inicio')
            ->get();

        // AHORA: Actividad actual o más inmediata
        $currentActivity = $todayEvents->first(function ($event) use ($now) {
            if (! $event->hora_inicio) {
                return false;
            }
            $start = Carbon::parse($event->fecha->format('Y-m-d').' '.$event->hora_inicio);
            $end = $event->hora_fin
                ? Carbon::parse($event->fecha->format('Y-m-d').' '.$event->hora_fin)
                : $start->copy()->addMinutes($event->duracion ?: 60);

            return $now->between($start, $end);
        });

        // PRÓXIMO: Siguiente compromiso
        $nextActivity = null;
        if (! $currentActivity) {
            $nextActivity = $todayEvents->first(function ($event) use ($now) {
                if (! $event->hora_inicio) {
                    return true;
                }
                $start = Carbon::parse($event->fecha->format('Y-m-d').' '.$event->hora_inicio);

                return $start->isAfter($now);
            });
        } else {
            $nextActivity = $todayEvents->first(function ($event) use ($currentActivity) {
                return $event->id !== $currentActivity->id && $event->hora_inicio > $currentActivity->hora_inicio;
            });
        }

        // DESPUÉS: Próximas actividades (las siguientes de hoy o de los próximos 3 días)
        $upcomingEvents = Event::where('user_id', $userId)
            ->where('fecha', '>=', $today)
            ->where('estado', '!=', 'cancelado')
            ->when($nextActivity, fn ($q) => $q->where('id', '!=', $nextActivity->id))
            ->when($currentActivity, fn ($q) => $q->where('id', '!=', $currentActivity->id))
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->take(4)
            ->get();

        // TAREAS IMPORTANTES: Prioritarias no completadas
        $importantTasks = Task::where('user_id', $userId)
            ->where('estado', '!=', 'completada')
            ->orderByRaw("CASE prioridad WHEN 'URGENTE' THEN 1 WHEN 'IMPORTANTE' THEN 2 WHEN 'NORMAL' THEN 3 WHEN 'PUEDE_ESPERAR' THEN 4 ELSE 5 END")
            ->orderBy('fecha_limite')
            ->take(5)
            ->get();

        // NO OLVIDAR: Recordatorios pendientes
        $reminders = Reminder::where('user_id', $userId)
            ->where('estado', 'pendiente')
            ->where('programado_para', '<=', $today->copy()->endOfDay())
            ->with('remindable')
            ->orderBy('programado_para')
            ->take(5)
            ->get();

        // BANDEJA DE ENTRADA: Elementos sin organizar
        $inboxItems = InboxItem::where('user_id', $userId)
            ->whereIn('status', ['pendiente_analisis', 'necesita_informacion'])
            ->latest()
            ->take(4)
            ->get();

        // PREGUNTAS PENDIENTES
        $pendingQuestionsCount = PendingQuestion::where('user_id', $userId)
            ->where('estado', 'pendiente')
            ->count();

        return view('livewire.dashboard', [
            'todayEvents' => $todayEvents,
            'currentActivity' => $currentActivity,
            'nextActivity' => $nextActivity,
            'upcomingEvents' => $upcomingEvents,
            'importantTasks' => $importantTasks,
            'reminders' => $reminders,
            'inboxItems' => $inboxItems,
            'pendingQuestionsCount' => $pendingQuestionsCount,
        ]);
    }
}
