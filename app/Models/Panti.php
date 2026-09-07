<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Panti extends Model
{
    use HasFactory;

    public const TYPE_ANAK = 'anak';
    public const TYPE_JOMPO = 'jompo';
    public const TYPE_CAMPURAN = 'campuran';

    public const VERIFICATION_PENDING = 'pending';
    public const VERIFICATION_VERIFIED = 'verified';
    public const VERIFICATION_REJECTED = 'rejected';

    public const LOCATION_EXACT = 'exact';
    public const LOCATION_APPROXIMATE = 'approximate';

    public const URGENCY_AMAN = 'aman';
    public const URGENCY_WASPADA = 'waspada';
    public const URGENCY_KRITIS = 'kritis';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'type',
        'description',
        'province',
        'city',
        'district',
        'address',
        'latitude',
        'longitude',
        'manager_name',
        'manager_phone',
        'capacity',
        'total_residents',
        'children_count',
        'elderly_count',
        'staff_count',
        'verification_status',
        'verification_note',
        'logo_url',
        'cover_url',
        'consent_agreement',
        'location_precision',
        'urgency_score',
        'urgency_status',
        'urgency_calculated_at',
    ];

    protected $casts = [
        'consent_agreement' => 'boolean',
        'urgency_calculated_at' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function needs(): HasMany
    {
        return $this->hasMany(PantiNeed::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function volunteerApplications(): HasMany
    {
        return $this->hasMany(VolunteerApplication::class);
    }

    public function youthProfiles(): HasMany
    {
        return $this->hasMany(YouthProfile::class);
    }

    public function visitReports(): HasMany
    {
        return $this->hasMany(VisitReport::class);
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', self::VERIFICATION_VERIFIED);
    }

    public function scopeKritis($query)
    {
        return $query->where('urgency_status', self::URGENCY_KRITIS);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === self::VERIFICATION_VERIFIED;
    }

    public function isKritis(): bool
    {
        return $this->urgency_status === self::URGENCY_KRITIS;
    }
}
