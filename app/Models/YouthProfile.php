<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class YouthProfile extends Model
{
    use HasFactory;

    public const STATUS_BARU = 'baru';
    public const STATUS_DIDAMPINGI = 'didampingi';
    public const STATUS_SELESAI = 'selesai';

    protected $fillable = [
        'panti_id',
        'initials',
        'age',
        'interests',
        'skill_goals',
        'training_needs',
        'mentor_needed',
        'status',
        'note',
    ];

    protected $casts = [
        'mentor_needed' => 'boolean',
    ];

    public function panti(): BelongsTo
    {
        return $this->belongsTo(Panti::class);
    }

    public function volunteerApplications(): HasMany
    {
        return $this->hasMany(VolunteerApplication::class);
    }

    public function scopeNeedsMentor($query)
    {
        return $query->where('mentor_needed', true);
    }

    public function isNeedMentor(): bool
    {
        return $this->mentor_needed === true;
    }
}
