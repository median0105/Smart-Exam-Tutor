<?php

namespace App\Services;

use App\Models\TryoutAttempt;
use Illuminate\Support\Collection;

class KnnClassifierService
{
    public function classify(TryoutAttempt $attempt, int $k = 3): array
    {
        $attempt->loadMissing('answers');

        $testingData = $this->buildFeatureVectorFromAttempt($attempt);
        $trainingData = $this->getTrainingData($attempt);

        if ($trainingData->count() < $k) {
            return [
                'level' => $this->fallbackLevel((float) $attempt->score),
                'snapshot' => [
                    'method' => 'fallback_threshold',
                    'k' => $k,
                    'testing_data' => $testingData,
                    'neighbors' => [],
                ],
            ];
        }

        $neighbors = $trainingData
            ->map(function (array $row) use ($testingData) {
                $distance = $this->euclideanDistance($testingData, $row);

                return [
                    ...$row,
                    'distance' => round($distance, 4),
                ];
            })
            ->sortBy('distance')
            ->take($k)
            ->values();

        $winningLevel = $this->voteLevel($neighbors);

        return [
            'level' => $winningLevel,
            'snapshot' => [
                'method' => 'knn',
                'k' => $k,
                'testing_data' => $testingData,
                'neighbors' => $neighbors->toArray(),
            ],
        ];
    }

    public function buildFeatureVectorFromAttempt(TryoutAttempt $attempt): array
    {
        $easyCorrect = $attempt->answers->where('difficulty', 'easy')->where('is_correct', true)->count();
        $mediumCorrect = $attempt->answers->where('difficulty', 'medium')->where('is_correct', true)->count();
        $hardCorrect = $attempt->answers->where('difficulty', 'hard')->where('is_correct', true)->count();
        $totalAnsweredQuestions = max(1, $attempt->correct_answers + $attempt->wrong_answers + $attempt->unanswered_answers);
        $percentageCorrect = round(($attempt->correct_answers / $totalAnsweredQuestions) * 100, 2);

        return [
            'final_score' => round((float) $attempt->score, 2),
            'correct_answers' => (int) $attempt->correct_answers,
            'wrong_answers' => (int) $attempt->wrong_answers,
            'percentage_correct' => $percentageCorrect,
            'easy_correct' => $easyCorrect,
            'medium_correct' => $mediumCorrect,
            'hard_correct' => $hardCorrect,
        ];
    }

    public function euclideanDistance(array $testingData, array $trainingRow): float
    {
        $sum = 0;

        $sum += ($testingData['final_score'] - $trainingRow['final_score']) ** 2;
        $sum += ($testingData['correct_answers'] - $trainingRow['correct_answers']) ** 2;
        $sum += ($testingData['wrong_answers'] - $trainingRow['wrong_answers']) ** 2;
        $sum += ($testingData['percentage_correct'] - $trainingRow['percentage_correct']) ** 2;
        $sum += ($testingData['easy_correct'] - $trainingRow['easy_correct']) ** 2;
        $sum += ($testingData['medium_correct'] - $trainingRow['medium_correct']) ** 2;
        $sum += ($testingData['hard_correct'] - $trainingRow['hard_correct']) ** 2;

        return sqrt($sum);
    }

    public function fallbackLevel(float $score): string
    {
        return match (true) {
            $score >= 80 => 'Mahir',
            $score >= 60 => 'Menengah',
            default => 'Pemula',
        };
    }

    private function getTrainingData(TryoutAttempt $attempt): Collection
    {
        return TryoutAttempt::query()
            ->where('status', 'submitted')
            ->whereNotNull('detected_level')
            ->whereKeyNot($attempt->id)
            ->with('answers')
            ->get()
            ->map(function (TryoutAttempt $row) {
                return [
                    ...$this->buildFeatureVectorFromAttempt($row),
                    'attempt_id' => $row->id,
                    'level' => $row->detected_level,
                ];
            })
            ->values();
    }

    private function voteLevel(Collection $neighbors): string
    {
        $grouped = $neighbors->groupBy('level')->map(function (Collection $items) {
            return [
                'total_neighbors' => $items->count(),
                'average_distance' => round($items->avg('distance'), 4),
            ];
        });

        return collect($grouped)
            ->sortBy('average_distance')
            ->sortByDesc('total_neighbors')
            ->keys()
            ->first();
    }
}
