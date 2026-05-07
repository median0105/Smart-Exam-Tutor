<?php

namespace App\Services;

use App\Models\TryoutAttempt;
use Illuminate\Support\Collection;

class StudentLevelClassifierService
{
    public function classify(TryoutAttempt $attempt, int $k = 5): array
    {
        $feature = $this->featureVector($attempt);

        $history = TryoutAttempt::query()
            ->where('status', 'submitted')
            ->whereNotNull('detected_level')
            ->whereKeyNot($attempt->getKey())
            ->get()
            ->map(function (TryoutAttempt $candidate) use ($feature) {
                $candidateFeature = $this->featureVector($candidate);

                return [
                    'attempt_id' => $candidate->id,
                    'level' => $candidate->detected_level,
                    'distance' => $this->euclideanDistance($feature, $candidateFeature),
                    'feature' => $candidateFeature,
                ];
            })
            ->sortBy('distance')
            ->values();

        if ($history->count() < 3) {
            $fallbackLevel = $this->fallbackLevel((float) $attempt->score);

            return [
                'level' => $fallbackLevel,
                'snapshot' => [
                    'method' => 'fallback_threshold',
                    'feature' => $feature,
                    'neighbors' => [],
                ],
            ];
        }

        $neighbors = $history->take(min($k, $history->count()));
        $votes = $neighbors->groupBy('level')->map(fn (Collection $items) => [
            'count' => $items->count(),
            'avg_distance' => round($items->avg('distance'), 4),
        ]);

        $level = collect($votes)
            ->sortByDesc('count')
            ->sortBy('avg_distance')
            ->keys()
            ->first();

        return [
            'level' => $level,
            'snapshot' => [
                'method' => 'knn',
                'k' => min($k, $history->count()),
                'feature' => $feature,
                'neighbors' => $neighbors->toArray(),
            ],
        ];
    }

    public function fallbackLevel(float $score): string
    {
        return match (true) {
            $score >= 80 => 'Mahir',
            $score >= 60 => 'Menengah',
            default => 'Pemula',
        };
    }

    private function featureVector(TryoutAttempt $attempt): array
    {
        $weightedDifficulty = $attempt->answers->avg(function ($answer) {
            if (! $answer->is_correct) {
                return 0;
            }

            return match ($answer->difficulty) {
                'hard' => 3,
                'medium' => 2,
                default => 1,
            };
        }) ?? 0;

        return [
            'score' => round((float) $attempt->score, 2),
            'correct_answers' => (int) $attempt->correct_answers,
            'wrong_answers' => (int) $attempt->wrong_answers,
            'weighted_difficulty' => round($weightedDifficulty, 2),
        ];
    }

    private function euclideanDistance(array $a, array $b): float
    {
        $sum = 0;

        foreach ($a as $key => $value) {
            $sum += ($value - ($b[$key] ?? 0)) ** 2;
        }

        return sqrt($sum);
    }
}
