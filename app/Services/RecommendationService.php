<?php

namespace App\Services;

use App\Models\LearningMaterial;
use App\Models\Question;
use App\Models\Recommendation;
use App\Models\Topic;
use App\Models\TryoutAttempt;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RecommendationService
{
    public function refreshForAttempt(TryoutAttempt $attempt): Collection
    {
        $attempt->loadMissing([
            'tryout.subject',
            'answers',
            'recommendations',
        ]);

        $attempt->recommendations()->delete();

        $weaknessVector = $this->buildWeaknessVector($attempt);

        if ($weaknessVector === []) {
            return collect();
        }

        $created = collect();

        $materials = LearningMaterial::query()
            ->where('subject_id', $attempt->tryout->subject_id)
            ->where('is_published', true)
            ->with('topic')
            ->get()
            ->map(fn (LearningMaterial $material) => $this->scoreRecommendationItem($material, $weaknessVector, 'material'))
            ->filter(fn (array $item) => $item['score'] > 0)
            ->sortByDesc('score')
            ->take(3);

        $preferredDifficulties = match ($attempt->detected_level) {
            'Mahir' => ['hard', 'medium'],
            'Menengah' => ['medium', 'easy'],
            default => ['easy', 'medium'],
        };

        $questionIds = $attempt->answers->pluck('question_id');

        $questions = Question::query()
            ->where('subject_id', $attempt->tryout->subject_id)
            ->whereIn('difficulty', $preferredDifficulties)
            ->whereNotIn('id', $questionIds)
            ->where('is_active', true)
            ->with('topic')
            ->get()
            ->map(fn (Question $question) => $this->scoreRecommendationItem($question, $weaknessVector, 'question'))
            ->filter(fn (array $item) => $item['score'] > 0)
            ->sortByDesc('score')
            ->take(5);

        foreach ($materials->concat($questions) as $item) {
            $created->push(Recommendation::create([
                'user_id' => $attempt->user_id,
                'attempt_id' => $attempt->id,
                'recommendable_type' => $item['model']::class,
                'recommendable_id' => $item['model']->id,
                'category' => $item['category'],
                'algorithm' => 'cosine_similarity',
                'similarity_score' => round($item['score'], 4),
                'reason' => $this->buildReason(
                    $item['model']->topic->name,
                    $attempt->detected_level,
                    $item['category'],
                    $item['matched_keywords']
                ),
            ]));
        }

        return $created;
    }

    public function cosineSimilarity(array $a, array $b): float
    {
        $keys = array_unique([...array_keys($a), ...array_keys($b)]);

        $dotProduct = 0;
        $magnitudeA = 0;
        $magnitudeB = 0;

        foreach ($keys as $key) {
            $valueA = $a[$key] ?? 0;
            $valueB = $b[$key] ?? 0;
            $dotProduct += $valueA * $valueB;
            $magnitudeA += $valueA ** 2;
            $magnitudeB += $valueB ** 2;
        }

        if ($magnitudeA == 0 || $magnitudeB == 0) {
            return 0;
        }

        return $dotProduct / (sqrt($magnitudeA) * sqrt($magnitudeB));
    }

    public function buildWeaknessVector(TryoutAttempt $attempt): array
    {
        $topicWeights = collect($attempt->weakness_vector ?? [])
            ->filter(fn ($weight) => (float) $weight > 0)
            ->map(fn ($weight) => round((float) $weight, 4));

        if ($topicWeights->isEmpty()) {
            return [];
        }

        $topics = Topic::query()
            ->whereIn('id', $topicWeights->keys())
            ->get()
            ->keyBy('id');

        $vector = [];

        foreach ($topicWeights as $topicId => $weight) {
            $topic = $topics->get((int) $topicId);

            if (! $topic) {
                continue;
            }

            foreach ($this->topicKeywords($topic) as $keyword) {
                $vector[$keyword] = round(($vector[$keyword] ?? 0) + $weight, 4);
            }
        }

        return $vector;
    }

    private function scoreRecommendationItem(LearningMaterial|Question $model, array $weaknessVector, string $category): array
    {
        $itemVector = $this->buildItemVector(
            topicName: $model->topic->name,
            tags: $model->tags ?? [],
        );

        $matchedKeywords = collect(array_keys($weaknessVector))
            ->intersect(array_keys($itemVector))
            ->values()
            ->all();

        return [
            'model' => $model,
            'score' => $this->cosineSimilarity($weaknessVector, $itemVector),
            'category' => $category,
            'matched_keywords' => $matchedKeywords,
        ];
    }

    public function buildItemVector(string $topicName, array $tags = []): array
    {
        $vector = [];

        foreach ($this->normalizeKeywords([$topicName, ...$tags]) as $keyword) {
            $vector[$keyword] = ($vector[$keyword] ?? 0) + 1;
        }

        return $vector;
    }

    private function topicKeywords(Topic $topic): array
    {
        return $this->normalizeKeywords([
            $topic->name,
            ...preg_split('/[\s,]+/', $topic->name),
        ]);
    }

    private function normalizeKeywords(array $keywords): array
    {
        return collect($keywords)
            ->filter()
            ->map(function ($keyword) {
                $keyword = Str::of((string) $keyword)
                    ->lower()
                    ->ascii()
                    ->replaceMatches('/[^a-z0-9\s-]/', '')
                    ->trim()
                    ->value();

                return $keyword;
            })
            ->filter(fn ($keyword) => $keyword !== '')
            ->unique()
            ->values()
            ->all();
    }

    private function buildReason(string $topicName, ?string $level, string $category, array $matchedKeywords): string
    {
        $itemName = $category === 'material' ? 'materi' : 'soal lanjutan';
        $levelLabel = $level ? "level {$level}" : 'level saat ini';
        $keywords = $matchedKeywords === [] ? $topicName : implode(', ', array_slice($matchedKeywords, 0, 3));

        return "Direkomendasikan karena {$itemName} ini mirip dengan kelemahan Anda pada topik {$topicName}. Kata kunci yang cocok: {$keywords}. Cocok untuk {$levelLabel}.";
    }
}
