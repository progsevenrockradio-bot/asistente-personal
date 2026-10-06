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

    public string $filter = 'pendientes'; // pendientes, completadas, todas

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

    public function deleteTask(int $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->delete();
        session()->flash('success', 'Tarea eliminada.');
    }

    public function render()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->when($this->filter === 'pendientes', fn ($q) => $q->where('estado', '!=', 'completada'))
            ->when($this->filter === 'completadas', fn ($q) => $q->where('estado', 'completada'))
            ->orderByRaw("CASE prioridad WHEN 'URGENTE' THEN 1 WHEN 'IMPORTANTE' THEN 2 WHEN 'NORMAL' THEN 3 WHEN 'PUEDE_ESPERAR' THEN 4 ELSE 5 END")
            ->orderBy('fecha_limite')
            ->get();

        return view('livewire.tasks.index', [
            'tasks' => $tasks,
        ]);
    }
}
