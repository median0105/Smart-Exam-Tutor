<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Recommendation extends Model
{
    public const CATEGORY_MATERIAL = 'material';

    public const CATEGORY_QUESTION = 'question';

    protected $fillable = [
        'user_id',
        'attempt_id',
        'recommendable_type',
        'recommendable_id',
        'category',
        'algorithm',
        'similarity_score',
        'reason',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(TryoutAttempt::class, 'attempt_id');
    }

    public function recommendable(): MorphTo
    {
        return $this->morphTo();
    }
}
