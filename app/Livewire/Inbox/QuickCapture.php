<?php

namespace App\Livewire\Inbox;

use App\Models\AuditLog;
use App\Models\InboxItem;
use Livewire\Component;

class QuickCapture extends Component
{
    public string $content = '';

    public string $source = 'texto'; // 'texto', 'voz'

    protected function rules(): array
    {
        return [
            'content' => ['required', 'string', 'min:2', 'max:5000'],
        ];
    }

    public function save()
    {
        $this->validate();

        $item = InboxItem::create([
            'user_id' => auth()->id(),
            'content' => trim($this->content),
            'source' => $this->source,
            'status' => 'pendiente_analisis',
        ]);

        AuditLog::log('inbox_item_capturado', $item, [
            'source' => $this->source,
            'chars' => strlen($this->content),
        ]);

        $this->reset('content');

        $this->dispatch('captured');
        $this->dispatch('inbox-updated');

        session()->flash('success', 'Guardado en la Bandeja de Entrada para organizar.');
    }

    public function render()
    {
        return view('livewire.inbox.quick-capture');
    }
}
