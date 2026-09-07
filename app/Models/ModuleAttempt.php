<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'module_id',
        'score',
        'passed',
        'completed_at',
    ];

    protected $casts = [
        'passed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function isPassed(): bool
    {
        return $this->passed === true;
    }
}
