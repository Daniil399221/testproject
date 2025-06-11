<?php

namespace App\Models;

use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory,
        Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => UserStatus::class
    ];

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Task::class
        );
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Role::class
        );
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(
            related: Notification::class
        );
    }
}
