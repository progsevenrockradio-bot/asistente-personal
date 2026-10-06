<?php

namespace App\Livewire\Tasks;

use App\Models\AuditLog;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Editar Tarea - Mi Asistente')]
class Edit extends Component
{
    public Task $task;

    public string $titulo = '';

    public ?string $descripcion = '';

    public ?string $fecha_limite = '';

    public string $prioridad = 'NORMAL';

    public ?int $dependencia_id = null;

    public function mount(int $id)
    {
        $this->task = Task::where('user_id', Auth::id())->findOrFail($id);

        $this->titulo = $this->task->titulo;
        $this->descripcion = $this->task->descripcion;
        $this->fecha_limite = $this->task->fecha_limite ? Carbon::parse($this->task->fecha_limite)->format('Y-m-d') : '';
        $this->prioridad = $this->task->prioridad;
        $this->dependencia_id = $this->task->dependencia_id;
    }

    protected function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'fecha_limite' => ['nullable', 'date'],
            'prioridad' => ['required', 'in:URGENTE,IMPORTANTE,NORMAL,PUEDE_ESPERAR'],
            'dependencia_id' => ['nullable', 'exists:tasks,id'],
        ];
    }

    public function save()
    {
        $this->validate();

        if ($this->dependencia_id == $this->task->id) {
            $this->addError('dependencia_id', 'Una tarea no puede depender de sí misma.');

            return;
        }

        $this->task->update([
            'dependencia_id' => $this->dependencia_id,
            'titulo' => trim($this->titulo),
            'descripcion' => $this->descripcion ? trim($this->descripcion) : null,
            'fecha_limite' => $this->fecha_limite ?: null,
            'prioridad' => $this->prioridad,
        ]);

        AuditLog::log('tarea_actualizada', $this->task, [
            'titulo' => $this->task->titulo,
        ]);

        session()->flash('success', 'Tarea actualizada.');

        return redirect()->route('tasks.index');
    }

    public function render()
    {
        return view('livewire.tasks.edit', [
            'tasks' => Task::where('user_id', Auth::id())
                ->where('estado', '!=', 'completada')
                ->where('id', '!=', $this->task->id)
                ->get(),
        ]);
    }
}
