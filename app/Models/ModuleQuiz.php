<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleQuiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'sort_order',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function isCorrect(string $answer): bool
    {
        return strtolower($answer) === strtolower($this->correct_option);
    }
}
