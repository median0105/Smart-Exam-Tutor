<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tryout extends Model
{
    protected $fillable = [
        'subject_id',
        'title',
        'slug',
        'description',
        'duration_minutes',
        'question_count',
        'difficulty_mix',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class)
            ->withPivot('position')
            ->withTimestamps()
            ->orderBy('question_tryout.position');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(TryoutAttempt::class);
    }
}
