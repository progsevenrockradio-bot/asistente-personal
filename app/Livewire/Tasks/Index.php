<?php

namespace App\Livewire\Tasks;

use App\Models\AuditLog;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Tareas - Mi Asistente')]
class Index extends Component
{
    public string $newTitle = '';

    public string $newPriority = 'NORMAL';

    public ?string $newDueDate = null;

    public string $filter = 'todas'; // todas, hoy, vencidas, sin_fecha

    protected function rules(): array
    {
        return [
            'newTitle' => ['required', 'string', 'max:255'],
            'newPriority' => ['required', 'in:URGENTE,IMPORTANTE,NORMAL,PUEDE_ESPERAR'],
            'newDueDate' => ['nullable', 'date'],
        ];
    }

    public function addTask()
    {
        $this->validate();

        $task = Task::create([
            'user_id' => Auth::id(),
            'titulo' => trim($this->newTitle),
            'prioridad' => $this->newPriority,
            'fecha_limite' => $this->newDueDate ?: null,
            'estado' => 'pendiente',
        ]);

        AuditLog::log('tarea_creada', $task, [
            'titulo' => $task->titulo,
            'prioridad' => $task->prioridad,
        ]);

        $this->reset(['newTitle', 'newDueDate']);
        session()->flash('success', 'Tarea creada.');
    }

    public function toggleTask(int $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);

        $states = ['pendiente', 'en_proceso', 'completada', 'bloqueada', 'pospuesta'];
        $currentIndex = array_search($task->estado, $states);
        $nextState = $states[($currentIndex + 1) % count($states)];

        // If next state is completed, check dependency
        if ($nextState === 'completada' && $task->dependencia_id) {
            $parent = Task::find($task->dependencia_id);
            if ($parent && $parent->estado !== 'completada') {
                session()->flash('warning', 'No puedes completar esta tarea porque depende de "'.$parent->titulo.'" que aún no está completada.');
                return;
            }
        }

        $task->update([
            'estado' => $nextState,
            'completed_at' => $nextState === 'completada' ? now() : null,
        ]);
    }

    public function deleteTask(int $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->delete();
        session()->flash('success', 'Tarea eliminada.');
    }

    public function render()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->when($this->filter === 'hoy', fn ($q) => $q->whereDate('fecha_limite', now()->toDateString()))
            ->when($this->filter === 'vencidas', fn ($q) => $q->where('estado', '!=', 'completada')->whereDate('fecha_limite', '<', now()->toDateString()))
            ->when($this->filter === 'sin_fecha', fn ($q) => $q->whereNull('fecha_limite'))
            ->orderByRaw("CASE prioridad WHEN 'URGENTE' THEN 1 WHEN 'IMPORTANTE' THEN 2 WHEN 'NORMAL' THEN 3 WHEN 'PUEDE_ESPERAR' THEN 4 ELSE 5 END")
            ->orderBy('fecha_limite')
            ->get();

        return view('livewire.tasks.index', [
            'tasks' => $tasks,
        ]);
    }
}
