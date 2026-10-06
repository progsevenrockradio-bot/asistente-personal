<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InboxItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'source',
        'status',
        'analysis_payload',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'analysis_payload' => 'array',
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pendingQuestions(): HasMany
    {
        return $this->hasMany(PendingQuestion::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
