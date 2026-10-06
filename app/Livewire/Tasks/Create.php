<?php

namespace App\Livewire\Tasks;

use App\Models\AuditLog;
use App\Models\InboxItem;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Nueva Tarea - Mi Asistente')]
class Create extends Component
{
    public string $titulo = '';

    public ?string $descripcion = '';

    public ?string $fecha_limite = '';

    public string $prioridad = 'NORMAL';

    public ?int $inbox_item_id = null;

    public ?int $dependencia_id = null;

    public function mount()
    {
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
            'descripcion' => ['nullable', 'string'],
            'fecha_limite' => ['nullable', 'date'],
            'prioridad' => ['required', 'in:URGENTE,IMPORTANTE,NORMAL,PUEDE_ESPERAR'],
            'dependencia_id' => ['nullable', 'exists:tasks,id'],
        ];
    }

    public function save()
    {
        $this->validate();

        $task = Task::create([
            'user_id' => Auth::id(),
            'inbox_item_id' => $this->inbox_item_id,
            'dependencia_id' => $this->dependencia_id,
            'titulo' => trim($this->titulo),
            'descripcion' => $this->descripcion ? trim($this->descripcion) : null,
            'fecha_limite' => $this->fecha_limite ?: null,
            'prioridad' => $this->prioridad,
            'estado' => 'pendiente',
        ]);

        if ($this->inbox_item_id) {
            InboxItem::where('id', $this->inbox_item_id)->update(['status' => 'convertido_tarea']);
        }

        AuditLog::log('tarea_creada', $task, [
            'titulo' => $task->titulo,
        ]);

        session()->flash('success', 'Tarea creada.');

        return redirect()->route('tasks.index');
    }

    public function render()
    {
        return view('livewire.tasks.create', [
            'tasks' => Task::where('user_id', Auth::id())->where('estado', '!=', 'completada')->get(),
        ]);
    }
}
