<?php

namespace App\Services\QuestionBank;

use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Tryout;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuestionBankSyncService
{
    public function __construct(private readonly ExternalQuestionBankClient $client)
    {
    }

    /**
     * @return array{created:int,updated:int,skipped:int,processed:int}
     */
    public function sync(int $maxPages = 1, int $perPage = 50): array
    {
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $processed = 0;
        $subjectIds = [];

        for ($page = 1; $page <= max(1, $maxPages); $page++) {
            $items = $this->client->fetchQuestions(page: $page, perPage: $perPage);

            if ($items === []) {
                break;
            }

            foreach ($items as $payload) {
                $processed++;

                $normalized = $this->normalizePayload($payload);
                if ($normalized === null) {
                    $skipped++;
                    continue;
                }

                DB::transaction(function () use ($normalized, &$created, &$updated, &$subjectIds): void {
                    $source = (string) config('services.question_bank.provider', 'question_bank_api');
                    $subjectSlug = Str::slug($normalized['subject_name']);
                    $subject = Subject::firstOrCreate(
                        ['slug' => $subjectSlug],
                        [
                            'name' => $normalized['subject_name'],
                            'code' => Str::upper(Str::substr($subjectSlug, 0, 5)).'-'.Str::upper(Str::substr(sha1($normalized['subject_name']), 0, 4)),
                            'description' => 'Sinkronisasi otomatis dari bank soal eksternal.',
                            'color' => 'from-sky-500 to-cyan-500',
                            'is_active' => true,
                        ]
                    );

                    $topicSlug = Str::slug($normalized['topic_name']);
                    $topic = Topic::firstOrCreate(
                        [
                            'subject_id' => $subject->id,
                            'slug' => $topicSlug,
                        ],
                        [
                            'name' => $normalized['topic_name'],
                            'description' => 'Topik hasil sinkronisasi eksternal.',
                            'target_mastery_percentage' => 75,
                        ]
                    );

                    $question = Question::firstOrNew([
                        'external_source' => $source,
                        'external_id' => $normalized['external_id'],
                    ]);

                    $question->fill([
                        'subject_id' => $subject->id,
                        'topic_id' => $topic->id,
                        'body' => $normalized['body'],
                        'explanation' => $normalized['explanation'],
                        'difficulty' => $normalized['difficulty'],
                        'points' => $normalized['points'],
                        'tags' => $normalized['tags'],
                        'is_active' => true,
                    ]);

                    $isNew = ! $question->exists;
                    $question->save();

                    $question->options()->delete();
                    foreach ($normalized['options'] as $option) {
                        $question->options()->create($option);
                    }

                    if ($isNew) {
                        $created++;
                    } else {
                        $updated++;
                    }

                    $subjectIds[$subject->id] = true;
                });
            }
        }

        foreach (array_keys($subjectIds) as $subjectId) {
            $this->syncStudentTryoutFromExternalBank((int) $subjectId);
        }

        return compact('created', 'updated', 'skipped', 'processed');
    }

    /**
     * @param  array<string,mixed>  $payload
     * @return array<string,mixed>|null
     */
    private function normalizePayload(array $payload): ?array
    {
        if (array_key_exists('correct_answer', $payload) && array_key_exists('incorrect_answers', $payload)) {
            return $this->normalizeOpenTdbPayload($payload);
        }

        $externalId = (string) ($payload['id'] ?? $payload['question_id'] ?? '');
        $body = trim((string) ($payload['body'] ?? $payload['question'] ?? ''));
        $explanation = trim((string) ($payload['explanation'] ?? $payload['solution'] ?? 'Pembahasan belum tersedia.'));

        $subjectName = $this->extractName($payload['subject'] ?? null, $payload['subject_name'] ?? null);
        $topicName = $this->extractName($payload['topic'] ?? null, $payload['topic_name'] ?? null);

        if ($externalId === '' || $body === '' || $subjectName === '' || $topicName === '') {
            return null;
        }

        $difficulty = $this->normalizeDifficulty((string) ($payload['difficulty'] ?? 'medium'));
        $points = (int) ($payload['points'] ?? 5);
        $points = min(100, max(1, $points));

        $rawTags = $payload['tags'] ?? [];
        if (is_string($rawTags)) {
            $rawTags = explode(',', $rawTags);
        }

        $tags = collect($rawTags)
            ->map(fn ($tag) => trim((string) $tag))
            ->filter()
            ->values()
            ->all();

        $rawOptions = $payload['options'] ?? $payload['choices'] ?? [];
        $options = collect($rawOptions)
            ->map(function ($option, $index) {
                if (is_string($option)) {
                    return [
                        'option_label' => $this->labelFromIndex((int) $index),
                        'content' => trim($option),
                        'is_correct' => false,
                        'feedback' => null,
                    ];
                }

                if (! is_array($option)) {
                    return null;
                }

                $label = trim((string) ($option['label'] ?? $option['option_label'] ?? $this->labelFromIndex((int) $index)));
                $content = trim((string) ($option['content'] ?? $option['text'] ?? ''));

                return [
                    'option_label' => $label,
                    'content' => $content,
                    'is_correct' => (bool) ($option['is_correct'] ?? $option['correct'] ?? false),
                    'feedback' => Arr::get($option, 'feedback'),
                ];
            })
            ->filter(fn ($option) => is_array($option) && $option['option_label'] !== '' && $option['content'] !== '')
            ->values();

        if ($options->isEmpty() || ! $options->contains(fn (array $option) => $option['is_correct'] === true)) {
            return null;
        }

        return [
            'external_id' => $externalId,
            'subject_name' => $subjectName,
            'topic_name' => $topicName,
            'body' => $body,
            'explanation' => $explanation,
            'difficulty' => $difficulty,
            'points' => $points,
            'tags' => $tags,
            'options' => $options->all(),
        ];
    }

    /**
     * @param  array<string,mixed>  $payload
     * @return array<string,mixed>|null
     */
    private function normalizeOpenTdbPayload(array $payload): ?array
    {
        $questionText = html_entity_decode(trim((string) ($payload['question'] ?? '')), ENT_QUOTES | ENT_HTML5);
        $correctAnswer = html_entity_decode(trim((string) ($payload['correct_answer'] ?? '')), ENT_QUOTES | ENT_HTML5);
        $incorrectAnswers = collect($payload['incorrect_answers'] ?? [])
            ->map(fn ($answer) => html_entity_decode(trim((string) $answer), ENT_QUOTES | ENT_HTML5))
            ->filter()
            ->values();

        if ($questionText === '' || $correctAnswer === '' || $incorrectAnswers->isEmpty()) {
            return null;
        }

        $category = html_entity_decode(trim((string) ($payload['category'] ?? 'General Knowledge')), ENT_QUOTES | ENT_HTML5);
        $subjectName = Str::of($category)->contains(':')
            ? trim((string) Str::of($category)->before(':'))
            : $category;
        $topicName = $category;

        $allChoices = $incorrectAnswers->push($correctAnswer)->shuffle()->values();
        $options = $allChoices->map(function (string $choice, int $index) use ($correctAnswer) {
            return [
                'option_label' => $this->labelFromIndex($index),
                'content' => $choice,
                'is_correct' => $choice === $correctAnswer,
                'feedback' => null,
            ];
        })->all();

        return [
            'external_id' => sha1($questionText.'|'.$category.'|'.$correctAnswer),
            'subject_name' => $subjectName !== '' ? $subjectName : 'General',
            'topic_name' => $topicName !== '' ? $topicName : 'General Knowledge',
            'body' => $questionText,
            'explanation' => 'Pembahasan dari sumber eksternal belum tersedia.',
            'difficulty' => $this->normalizeDifficulty((string) ($payload['difficulty'] ?? 'medium')),
            'points' => 5,
            'tags' => [Str::lower((string) ($payload['type'] ?? 'multiple')), 'opentdb'],
            'options' => $options,
        ];
    }

    private function extractName(mixed ...$values): string
    {
        foreach ($values as $value) {
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }

            if (is_array($value)) {
                $name = trim((string) ($value['name'] ?? ''));
                if ($name !== '') {
                    return $name;
                }
            }
        }

        return '';
    }

    private function normalizeDifficulty(string $difficulty): string
    {
        $value = Str::lower(trim($difficulty));

        return match ($value) {
            'easy', 'mudah', 'beginner' => 'easy',
            'hard', 'sulit', 'advanced' => 'hard',
            default => 'medium',
        };
    }

    private function labelFromIndex(int $index): string
    {
        return chr(65 + ($index % 26));
    }

    private function syncStudentTryoutFromExternalBank(int $subjectId): void
    {
        $source = (string) config('services.question_bank.provider', 'question_bank_api');
        $subject = Subject::query()->find($subjectId);

        if (! $subject) {
            return;
        }

        $questions = Question::query()
            ->where('subject_id', $subjectId)
            ->where('external_source', $source)
            ->where('is_active', true)
            ->orderBy('id')
            ->get(['id']);

        if ($questions->isEmpty()) {
            return;
        }

        $slug = Str::slug("try-out-api-{$source}-{$subject->slug}");
        $title = "Try Out API {$subject->name}";

        $tryout = Tryout::query()->firstOrCreate(
            ['slug' => $slug],
            [
                'subject_id' => $subject->id,
                'title' => $title,
                'description' => "Try out otomatis dari sinkronisasi API ({$source}).",
                'duration_minutes' => 60,
                'question_count' => $questions->count(),
                'difficulty_mix' => 'balanced',
                'is_published' => true,
            ]
        );

        $syncData = $questions
            ->values()
            ->mapWithKeys(fn ($question, $index) => [$question->id => ['position' => $index + 1]])
            ->all();

        $tryout->questions()->sync($syncData);
        $tryout->forceFill([
            'subject_id' => $subject->id,
            'title' => $title,
            'description' => "Try out otomatis dari sinkronisasi API ({$source}).",
            'question_count' => count($syncData),
            'is_published' => true,
        ])->save();
    }
}
