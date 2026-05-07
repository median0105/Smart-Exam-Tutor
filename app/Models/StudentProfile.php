<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'school',
        'grade_level',
        'target_exam',
        'average_score',
        'total_attempts',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
