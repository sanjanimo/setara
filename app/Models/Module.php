<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    public const TARGET_UMUM = 'umum';
    public const TARGET_ANAK = 'anak';
    public const TARGET_LANSIA = 'lansia';

    protected $fillable = [
        'title',
        'slug',
        'target',
        'level',
        'description',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(ModuleLesson::class)->orderBy('sort_order');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(ModuleQuiz::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ModuleAttempt::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
