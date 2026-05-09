<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleLearningRecommendationService
{
    public function generate(array $payload): ?array
    {
        $apiKey = (string) config('services.google_ai.api_key');
        $model = (string) config('services.google_ai.model', 'gemini-1.5-flash');

        if ($apiKey === '') {
            return null;
        }

        $endpoint = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            $model,
            $apiKey
        );

        $prompt = $this->buildPrompt($payload);

        $response = Http::timeout((int) config('services.google_ai.timeout', 20))
            ->post($endpoint, [
                'contents' => [[
                    'parts' => [[
                        'text' => $prompt,
                    ]],
                ]],
                'generationConfig' => [
                    'temperature' => 0.4,
                    'responseMimeType' => 'application/json',
                ],
            ]);

        if (! $response->successful()) {
            return null;
        }

        $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

        if (! is_string($text) || trim($text) === '') {
            return null;
        }

        $decoded = json_decode($text, true);

        if (! is_array($decoded)) {
            return null;
        }

        return [
            'feedback' => Str::limit((string) ($decoded['feedback'] ?? ''), 500),
            'study_advice' => Str::limit((string) ($decoded['study_advice'] ?? ''), 500),
            'topic_advice' => collect($decoded['topic_advice'] ?? [])
                ->mapWithKeys(function ($advice, $topic) {
                    $topic = trim((string) $topic);
                    $advice = trim((string) $advice);

                    return $topic !== '' && $advice !== '' ? [$topic => Str::limit($advice, 220)] : [];
                })
                ->all(),
        ];
    }

    private function buildPrompt(array $payload): string
    {
        $subject = $payload['subject'] ?? 'Try Out';
        $score = (float) ($payload['score'] ?? 0);
        $wrong = (int) ($payload['wrong_answers'] ?? 0);
        $correct = (int) ($payload['correct_answers'] ?? 0);
        $level = $payload['level'] ?? 'Pemula';
        $weakTopics = $payload['weak_topics'] ?? [];

        $topicLines = collect($weakTopics)
            ->map(function ($topic) {
                $name = $topic['name'] ?? '-';
                $wrongRatio = round(((float) ($topic['wrong_ratio'] ?? 0)) * 100, 1);

                return "- {$name} (rasio salah: {$wrongRatio}%)";
            })
            ->implode("\n");

        return <<<PROMPT
Anda adalah tutor belajar siswa Indonesia.
Buat analisis singkat hasil try out mata pelajaran {$subject}.

Data siswa:
- Skor: {$score}
- Jawaban benar: {$correct}
- Jawaban salah: {$wrong}
- Level: {$level}
- Topik paling lemah:
{$topicLines}

Keluarkan JSON valid tanpa markdown dengan format:
{
  "feedback": "ringkasan analisis performa siswa (1-2 kalimat)",
  "study_advice": "saran belajar prioritas 7 hari ke depan (2-3 kalimat)",
  "topic_advice": {
    "nama topik": "saran fokus belajar topik tersebut (1 kalimat)"
  }
}

Gunakan bahasa Indonesia yang sederhana, actionable, dan spesifik.
PROMPT;
    }
}

