<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoogleAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'google_id',
        'name',
        'email',
        'avatar',
        'token_payload',
        'token_expires_at',
        'scopes',
        'status',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'token_payload' => 'encrypted:array',
            'token_expires_at' => 'datetime',
            'scopes' => 'array',
            'last_synced_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
