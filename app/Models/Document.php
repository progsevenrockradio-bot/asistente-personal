<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphedByMany;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'google_account_id',
        'nombre',
        'tipo',
        'tamano',
        'origen',
        'google_drive_file_id',
        'ubicacion',
        'categoria',
        'etiquetas',
        'fecha',
        'hash',
        'texto_extraido',
        'estado_procesamiento',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'tamano' => 'integer',
            'etiquetas' => 'array',
            'fecha' => 'date',
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function googleAccount(): BelongsTo
    {
        return $this->belongsTo(GoogleAccount::class);
    }

    public function events(): MorphedByMany
    {
        return $this->morphedByMany(Event::class, 'documentable', 'documentables');
    }

    public function tasks(): MorphedByMany
    {
        return $this->morphedByMany(Task::class, 'documentable', 'documentables');
    }
}
