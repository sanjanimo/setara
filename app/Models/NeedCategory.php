<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NeedCategory extends Model
{
    use HasFactory;

    public const TARGET_ANAK = 'anak';
    public const TARGET_LANSIA = 'lansia';
    public const TARGET_UMUM = 'umum';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'target',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function pantiNeeds(): HasMany
    {
        return $this->hasMany(PantiNeed::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
