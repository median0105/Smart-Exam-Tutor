<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Question extends Model
{
    protected $fillable = [
        'subject_id',
        'topic_id',
        'body',
        'explanation',
        'difficulty',
        'points',
        'tags',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_active' => 'boolean',
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

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function tryouts(): BelongsToMany
    {
        return $this->belongsToMany(Tryout::class)
            ->withPivot('position')
            ->withTimestamps();
    }

    public function recommendations(): MorphMany
    {
        return $this->morphMany(Recommendation::class, 'recommendable');
    }
}
