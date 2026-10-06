<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reminder_id',
        'canal',
        'titulo',
        'mensaje',
        'payload',
        'estado',
        'fecha_envio',
        'error_mensaje',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'fecha_envio' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reminder(): BelongsTo
    {
        return $this->belongsTo(Reminder::class);
    }
}
