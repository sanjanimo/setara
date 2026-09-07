<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VolunteerApplication extends Model
{
    use HasFactory;

    public const ACTIVITY_KUNJUNGAN = 'kunjungan';
    public const ACTIVITY_MENTOR = 'mentor';
    public const ACTIVITY_BANTUAN_LOGISTIK = 'bantuan_logistik';

    public const STATUS_DIAJUKAN = 'diajukan';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_SELESAI = 'selesai';

    protected $fillable = [
        'panti_id',
        'user_id',
        'youth_profile_id',
        'activity_type',
        'motivation',
        'skills',
        'organization',
        'availability',
        'status',
        'scheduled_date',
        'note',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function panti(): BelongsTo
    {
        return $this->belongsTo(Panti::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function youthProfile(): BelongsTo
    {
        return $this->belongsTo(YouthProfile::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function visitReport(): HasOne
    {
        return $this->hasOne(VisitReport::class);
    }

    public function scopeDiajukan($query)
    {
        return $query->where('status', self::STATUS_DIAJUKAN);
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', self::STATUS_DISETUJUI);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_DIAJUKAN;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_DISETUJUI;
    }
}
