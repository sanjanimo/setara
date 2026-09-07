<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'panti_id',
        'user_id',
        'volunteer_application_id',
        'activity_date',
        'summary',
        'follow_up_needed',
        'note',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'follow_up_needed' => 'boolean',
    ];

    public function panti(): BelongsTo
    {
        return $this->belongsTo(Panti::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function volunteerApplication(): BelongsTo
    {
        return $this->belongsTo(VolunteerApplication::class);
    }
}
