<?php

namespace App\Livewire\Documents;

use App\Models\AuditLog;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Documentos - Mi Asistente')]
class Index extends Component
{
    use WithFileUploads;

    public $uploadedFile;

    public string $categoria = 'general'; // general, medico, concierto, trabajo, estudio

    protected function rules(): array
    {
        return [
            'uploadedFile' => ['required', 'file', 'max:20480', 'mimes:pdf,docx,txt,csv,md,jpg,jpeg,png'],
            'categoria' => ['required', 'string'],
        ];
    }

    public function upload()
    {
        $this->validate();

        $originalName = $this->uploadedFile->getClientOriginalName();
        $extension = $this->uploadedFile->getClientOriginalExtension();
        $size = $this->uploadedFile->getSize();

        $path = $this->uploadedFile->store('documents/'.Auth::id(), 'local');

        $doc = Document::create([
            'user_id' => Auth::id(),
            'nombre' => $originalName,
            'tipo' => strtolower($extension),
            'tamano' => $size,
            'origen' => 'telefono',
            'ubicacion' => $path,
            'categoria' => $this->categoria,
            'estado_procesamiento' => 'pendiente',
        ]);

        AuditLog::log('documento_importado', $doc, [
            'nombre' => $doc->nombre,
            'categoria' => $doc->categoria,
        ]);

        $this->reset('uploadedFile');
        session()->flash('success', 'Documento subido correctamente. Listo para procesar.');
    }

    public function render()
    {
        $documents = Document::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('livewire.documents.index', [
            'documents' => $documents,
        ]);
    }
}
