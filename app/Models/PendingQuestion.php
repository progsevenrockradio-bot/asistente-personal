<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'inbox_item_id',
        'entidad_tipo',
        'entidad_id',
        'campo_faltante',
        'pregunta',
        'respuesta',
        'estado',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'answered_at' => 'datetime',
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
}
