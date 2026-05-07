<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class LearningMaterial extends Model
{
    protected $fillable = [
        'subject_id',
        'topic_id',
        'title',
        'slug',
        'summary',
        'content',
        'tags',
        'difficulty',
        'estimated_minutes',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function recommendations(): MorphMany
    {
        return $this->morphMany(Recommendation::class, 'recommendable');
    }
}
