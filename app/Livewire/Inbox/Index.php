<?php

namespace App\Livewire\Inbox;

use App\Models\AuditLog;
use App\Models\InboxItem;
use App\Models\PendingQuestion;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Bandeja de Entrada - Mi Asistente')]
class Index extends Component
{
    public string $filter = 'todos'; // todos, pendiente, analizado, archivado

    public ?int $selectedItemId = null;

    public string $answerInput = '';

    public function archive(int $id)
    {
        $item = InboxItem::where('user_id', Auth::id())->findOrFail($id);
        $item->update(['status' => 'archivado']);

        AuditLog::log('inbox_item_archivado', $item);
        session()->flash('success', 'Elemento archivado.');
    }

    public function answerQuestion(int $questionId)
    {
        $question = PendingQuestion::where('user_id', Auth::id())->findOrFail($questionId);

        if (trim($this->answerInput)) {
            $question->update([
                'respuesta' => trim($this->answerInput),
                'estado' => 'respondida',
                'answered_at' => now(),
            ]);

            AuditLog::log('pregunta_respondida', $question, [
                'campo' => $question->campo_faltante,
                'respuesta' => $this->answerInput,
            ]);

            $this->reset('answerInput');
            session()->flash('success', 'Información registrada con éxito.');
        }
    }

    public function deleteItem(int $id)
    {
        $item = InboxItem::where('user_id', Auth::id())->findOrFail($id);
        $item->delete();

        session()->flash('success', 'Elemento eliminado.');
    }

    public function render()
    {
        $userId = Auth::id();

        $items = InboxItem::where('user_id', $userId)
            ->when($this->filter === 'pendiente', fn ($q) => $q->whereIn('status', ['pendiente_analisis', 'necesita_informacion']))
            ->when($this->filter === 'analizado', fn ($q) => $q->whereIn('status', ['analizado', 'convertido_evento', 'convertido_tarea']))
            ->when($this->filter === 'archivado', fn ($q) => $q->where('status', 'archivado'))
            ->when($this->filter === 'todos', fn ($q) => $q->where('status', '!=', 'archivado'))
            ->with('pendingQuestions')
            ->latest()
            ->paginate(15);

        $pendingQuestions = PendingQuestion::where('user_id', $userId)
            ->where('estado', 'pendiente')
            ->latest()
            ->get();

        return view('livewire.inbox.index', [
            'items' => $items,
            'pendingQuestions' => $pendingQuestions,
        ]);
    }
}
