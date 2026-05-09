<?php

namespace App\Services;

use App\Models\StudentTopicMastery;
use App\Models\TryoutAttempt;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TryoutEvaluationService
{
    public function __construct(
        private readonly KnnClassifierService $classifier,
        private readonly RecommendationService $recommendationService,
        private readonly GoogleLearningRecommendationService $googleLearningRecommendationService,
    ) {
    }

    public function evaluate(TryoutAttempt $attempt, array $submittedAnswers): TryoutAttempt
    {
        return DB::transaction(function () use ($attempt, $submittedAnswers) {
            $attempt->loadMissing('tryout.questions.options', 'tryout.questions.topic', 'answers');

            $questions = $attempt->tryout->questions;
            $correct = 0;
            $wrong = 0;
            $unanswered = 0;
            $earnedPoints = 0;
            $totalPoints = max(1, (int) $questions->sum('points'));
            $topicStats = [];

            $attempt->answers()->delete();

            foreach ($questions as $question) {
                $selectedOptionId = $submittedAnswers[$question->id]['option_id'] ?? null;
                $timeSpent = (int) ($submittedAnswers[$question->id]['time_spent_seconds'] ?? 0);
                $selectedOption = $question->options->firstWhere('id', $selectedOptionId);
                $isCorrect = (bool) optional($selectedOption)->is_correct;
                $pointsEarned = $isCorrect ? (int) $question->points : 0;

                if ($selectedOptionId === null) {
                    $unanswered++;
                } elseif ($isCorrect) {
                    $correct++;
                } else {
                    $wrong++;
                }

                $earnedPoints += $pointsEarned;

                $topicStats[$question->topic_id] ??= ['total' => 0, 'wrong' => 0, 'correct' => 0];
                $topicStats[$question->topic_id]['total']++;
                $topicStats[$question->topic_id]['correct'] += $isCorrect ? 1 : 0;
                $topicStats[$question->topic_id]['wrong'] += $isCorrect ? 0 : 1;

                $attempt->answers()->create([
                    'question_id' => $question->id,
                    'question_option_id' => $selectedOptionId,
                    'topic_id' => $question->topic_id,
                    'difficulty' => $question->difficulty,
                    'is_correct' => $isCorrect,
                    'points_earned' => $pointsEarned,
                    'time_spent_seconds' => $timeSpent,
                    'answer_payload' => ['selected_option_id' => $selectedOptionId],
                ]);
            }

            $score = round(($earnedPoints / $totalPoints) * 100, 2);
            $weaknessVector = collect($topicStats)->mapWithKeys(function (array $stat, int $topicId) {
                $ratio = $stat['total'] > 0 ? $stat['wrong'] / $stat['total'] : 0;

                return [$topicId => round($ratio, 4)];
            })->all();

            $attempt->forceFill([
                'submitted_at' => Carbon::now(),
                'status' => 'submitted',
                'score' => $score,
                'correct_answers' => $correct,
                'wrong_answers' => $wrong,
                'unanswered_answers' => $unanswered,
                'weakness_vector' => $weaknessVector,
            ])->save();

            $attempt->load('answers');

            $classification = $this->classifier->classify($attempt);
            $weakTopics = $attempt->tryout->subject->topics()
                ->whereIn('id', array_keys($weaknessVector))
                ->get()
                ->sortByDesc(fn ($topic) => $weaknessVector[$topic->id] ?? 0)
                ->take(3)
                ->values();

            $attempt->forceFill([
                'detected_level' => $classification['level'],
                'knn_snapshot' => $classification['snapshot'],
                'automated_feedback' => $this->buildFeedback($score, $classification['level'], $weakTopics->pluck('name')->all()),
                'study_advice' => $this->buildStudyAdvice($classification['level'], $weakTopics->pluck('name')->all()),
            ])->save();

            $wrongRatio = ($wrong + $correct) > 0 ? $wrong / ($wrong + $correct) : 0;
            $topicAdvice = [];

            if ($wrongRatio >= 0.35) {
                $aiRecommendation = $this->googleLearningRecommendationService->generate([
                    'subject' => $attempt->tryout->subject->name ?? 'Try Out',
                    'score' => $score,
                    'wrong_answers' => $wrong,
                    'correct_answers' => $correct,
                    'level' => $classification['level'],
                    'weak_topics' => $weakTopics->map(function ($topic) use ($weaknessVector) {
                        return [
                            'name' => $topic->name,
                            'wrong_ratio' => (float) ($weaknessVector[$topic->id] ?? 0),
                        ];
                    })->values()->all(),
                ]);

                if (is_array($aiRecommendation)) {
                    $topicAdvice = $aiRecommendation['topic_advice'] ?? [];

                    $attempt->forceFill([
                        'automated_feedback' => $aiRecommendation['feedback'] ?: $attempt->automated_feedback,
                        'study_advice' => $aiRecommendation['study_advice'] ?: $attempt->study_advice,
                    ])->save();
                }
            }

            $this->updateStudentProfile($attempt);
            $this->updateMasteries($attempt, $topicStats);
            $this->recommendationService->refreshForAttempt(
                $attempt->fresh(['tryout.subject', 'answers', 'recommendations']),
                $topicAdvice
            );

            return $attempt->fresh(['tryout.subject', 'answers.question.topic', 'recommendations.recommendable']);
        });
    }

    private function updateStudentProfile(TryoutAttempt $attempt): void
    {
        $user = $attempt->user;
        $profile = $user->profile()->firstOrCreate();
        $submittedAttempts = $user->tryoutAttempts()->where('status', 'submitted');

        $profile->update([
            'average_score' => round((float) $submittedAttempts->avg('score'), 2),
            'total_attempts' => $submittedAttempts->count(),
        ]);

        $user->update([
            'learning_level' => $attempt->detected_level,
        ]);
    }

    private function updateMasteries(TryoutAttempt $attempt, array $topicStats): void
    {
        foreach ($topicStats as $topicId => $stat) {
            $mastery = StudentTopicMastery::firstOrNew([
                'user_id' => $attempt->user_id,
                'topic_id' => $topicId,
            ]);

            $mastery->total_attempts += $stat['total'];
            $mastery->correct_attempts += $stat['correct'];
            $mastery->wrong_attempts += $stat['wrong'];
            $mastery->mastery_score = round(($mastery->correct_attempts / max(1, $mastery->total_attempts)) * 100, 2);
            $mastery->weakness_score = round(100 - $mastery->mastery_score, 2);
            $mastery->last_attempt_at = now();
            $mastery->save();
        }
    }

    private function buildFeedback(float $score, string $level, array $weakTopics): string
    {
        $topics = $weakTopics === [] ? 'semua topik inti' : implode(', ', $weakTopics);

        return "Skor Anda {$score}. Sistem mengklasifikasikan kemampuan saat ini pada level {$level}. Fokus perbaikan terbesar ada pada topik {$topics}.";
    }

    private function buildStudyAdvice(string $level, array $weakTopics): string
    {
        $topics = $weakTopics === [] ? 'topik prioritas' : implode(', ', $weakTopics);

        return match ($level) {
            'Mahir' => "Pertahankan performa Anda dengan latihan menantang dan review cepat pada {$topics}.",
            'Menengah' => "Perkuat konsep dasar dan biasakan latihan bertahap pada {$topics} sebelum masuk ke soal sulit.",
            default => "Mulai dari materi konsep dasar, contoh sederhana, lalu kerjakan soal mudah bertopik {$topics} secara rutin.",
        };
    }
}
