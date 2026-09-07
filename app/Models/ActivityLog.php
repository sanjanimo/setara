<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public static function record(
        string $action,
        ?string $description = null,
        ?Model $model = null,
        ?int $userId = null
    ): void {
        static::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'model_type' => $model?->getMorphClass(),
            'model_id' => $model?->getKey(),
            'description' => $description,
        ]);
    }
}
