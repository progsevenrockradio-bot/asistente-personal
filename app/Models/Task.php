<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'inbox_item_id',
        'dependencia_id',
        'titulo',
        'descripcion',
        'fecha_limite',
        'hora',
        'prioridad',
        'estado',
        'duracion_estimada',
        'categoria',
        'recordatorios',
        'orden',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'fecha_limite' => 'date',
            'duracion_estimada' => 'integer',
            'orden' => 'integer',
            'recordatorios' => 'array',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inboxItem(): BelongsTo
    {
        return $this->belongsTo(InboxItem::class);
    }

    public function dependency(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'dependencia_id');
    }

    public function dependents(): HasMany
    {
        return $this->hasMany(Task::class, 'dependencia_id');
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
