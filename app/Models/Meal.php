<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fecha',
        'tipo',
        'hora',
        'contenido',
        'notas',
        'restricciones',
        'recordatorio_activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'recordatorio_activo' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reminders(): MorphMany
    {
        return $this->morphMany(Reminder::class, 'remindable');
    }
}
