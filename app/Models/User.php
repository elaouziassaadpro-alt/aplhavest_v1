<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ROLES (Clean & Maintainable)
    |--------------------------------------------------------------------------
    */

    public const ROLE_AK    = 'AK';
    public const ROLE_CI    = 'CI';
    public const ROLE_BAK   = 'BAK';
    public const ROLE_ADMIN = 'admin';

    public const ROLES = [
        self::ROLE_AK,
        self::ROLE_CI,
        self::ROLE_BAK,
        self::ROLE_ADMIN,
    ];

    /*
    |--------------------------------------------------------------------------
    | Helpers (🔥 Très utile partout)
    |--------------------------------------------------------------------------
    */

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isAK(): bool
    {
        return $this->role === self::ROLE_AK;
    }

    public function isCI(): bool
    {
        return $this->role === self::ROLE_CI;
    }

    public function isBAK(): bool
    {
        return $this->role === self::ROLE_BAK;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes (Queries clean 😎)
    |--------------------------------------------------------------------------
    */

    public function scopeRole(Builder $query, string $role): Builder
    {
        return $query->where('role', $role);
    }

    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_ADMIN);
    }
}
