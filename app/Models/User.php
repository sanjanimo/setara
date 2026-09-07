<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_PANTI = 'panti';
    public const ROLE_RELAWAN = 'relawan';
    public const ROLE_DONATUR = 'donatur';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'organization_name',
        'is_active',
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
            'is_active' => 'boolean',
        ];
    }

    public function panti(): HasOne
    {
        return $this->hasOne(Panti::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function volunteerApplications(): HasMany
    {
        return $this->hasMany(VolunteerApplication::class);
    }

    public function moduleAttempts(): HasMany
    {
        return $this->hasMany(ModuleAttempt::class);
    }

    public function visitReports(): HasMany
    {
        return $this->hasMany(VisitReport::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isPanti(): bool
    {
        return $this->role === self::ROLE_PANTI;
    }

    public function isRelawan(): bool
    {
        return $this->role === self::ROLE_RELAWAN;
    }

    public function isDonatur(): bool
    {
        return $this->role === self::ROLE_DONATUR;
    }

    public function hasPassedModule(int $moduleId): bool
    {
        return $this->moduleAttempts()
            ->where('module_id', $moduleId)
            ->where('passed', true)
            ->exists();
    }
}
