<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'remindable_type',
        'remindable_id',
        'tipo',
        'programado_para',
        'estado',
        'configuracion',
    ];

    protected function casts(): array
    {
        return [
            'programado_para' => 'datetime',
            'configuracion' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function remindable(): MorphTo
    {
        return $this->morphTo();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(AppNotification::class);
    }
}
