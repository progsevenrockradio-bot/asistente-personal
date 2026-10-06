<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'google_account_id',
        'inbox_item_id',
        'titulo',
        'descripcion',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'duracion',
        'ubicacion',
        'prioridad',
        'estado',
        'fuente',
        'origen',
        'google_calendar_id',
        'google_event_id',
        'notas',
        'recordatorios',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'duracion' => 'integer',
            'recordatorios' => 'array',
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

    public function inboxItem(): BelongsTo
    {
        return $this->belongsTo(InboxItem::class);
    }

    public function reminders(): MorphMany
    {
        return $this->morphMany(Reminder::class, 'remindable');
    }

    public function documents(): MorphToMany
    {
        return $this->morphToMany(Document::class, 'documentable', 'documentables');
    }
}
