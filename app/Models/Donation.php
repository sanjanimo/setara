<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    public const TYPE_BARANG = 'barang';
    public const TYPE_TENAGA = 'tenaga';

    public const STATUS_DIAJUKAN = 'diajukan';
    public const STATUS_DIKONFIRMASI = 'dikonfirmasi';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_SELESAI = 'selesai';

    protected $fillable = [
        'panti_id',
        'panti_need_id',
        'user_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'type',
        'quantity',
        'message',
        'status',
        'proof_url',
        'note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function panti(): BelongsTo
    {
        return $this->belongsTo(Panti::class);
    }

    public function need(): BelongsTo
    {
        return $this->belongsTo(PantiNeed::class, 'panti_need_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopeDiajukan($query)
    {
        return $query->where('status', self::STATUS_DIAJUKAN);
    }

    public function scopeDikonfirmasi($query)
    {
        return $query->where('status', self::STATUS_DIKONFIRMASI);
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', self::STATUS_SELESAI);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_DIAJUKAN;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_DIKONFIRMASI;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }
}
