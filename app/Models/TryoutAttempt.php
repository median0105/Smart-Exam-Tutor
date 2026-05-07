<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TryoutAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'tryout_id',
        'started_at',
        'submitted_at',
        'status',
        'score',
        'correct_answers',
        'wrong_answers',
        'unanswered_answers',
        'detected_level',
        'automated_feedback',
        'study_advice',
        'weakness_vector',
        'knn_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'weakness_vector' => 'array',
            'knn_snapshot' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tryout(): BelongsTo
    {
        return $this->belongsTo(Tryout::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class, 'attempt_id');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class, 'attempt_id');
    }
}
