<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PantiNeed extends Model
{
    use HasFactory;

    public const PRIORITY_RENDAH = 'rendah';
    public const PRIORITY_SEDANG = 'sedang';
    public const PRIORITY_TINGGI = 'tinggi';
    public const PRIORITY_KRITIS = 'kritis';

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_TERPENUHI = 'terpenuhi';
    public const STATUS_DITUNDA = 'ditunda';

    protected $fillable = [
        'panti_id',
        'need_category_id',
        'title',
        'description',
        'unit',
        'quantity_needed',
        'current_stock',
        'stock_days_remaining',
        'priority',
        'status',
        'fulfilled_at',
        'note',
    ];

    protected $casts = [
        'fulfilled_at' => 'datetime',
    ];

    public function panti(): BelongsTo
    {
        return $this->belongsTo(Panti::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(NeedCategory::class, 'need_category_id');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'panti_need_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    public function isCritical(): bool
    {
        return $this->priority === self::PRIORITY_KRITIS;
    }

    public function isFulfilled(): bool
    {
        return $this->status === self::STATUS_TERPENUHI;
    }
}
