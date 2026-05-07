<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentTopicMastery extends Model
{
    protected $fillable = [
        'user_id',
        'topic_id',
        'total_attempts',
        'correct_attempts',
        'wrong_attempts',
        'mastery_score',
        'weakness_score',
        'last_attempt_at',
    ];

    protected function casts(): array
    {
        return [
            'last_attempt_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
