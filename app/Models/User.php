<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function inboxItems(): HasMany
    {
        return $this->hasMany(InboxItem::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function pendingQuestions(): HasMany
    {
        return $this->hasMany(PendingQuestion::class);
    }

    public function googleAccounts(): HasMany
    {
        return $this->hasMany(GoogleAccount::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function appNotifications(): HasMany
    {
        return $this->hasMany(AppNotification::class);
    }

    public function meals(): HasMany
    {
        return $this->hasMany(Meal::class);
    }

    public function medications(): HasMany
    {
        return $this->hasMany(Medication::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
