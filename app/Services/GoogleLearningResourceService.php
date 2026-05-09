<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleLearningResourceService
{
    public function recommendVideos(string $subjectName, array $topicNames, int $maxItems = 3): array
    {
        $apiKey = (string) (config('services.google.api_key') ?: config('services.google_ai.api_key'));

        if ($apiKey === '') {
            return $this->buildSearchFallback($subjectName, $topicNames, $maxItems);
        }

        $items = [];

        foreach (array_slice($topicNames, 0, $maxItems) as $topicName) {
            $query = trim($subjectName.' '.$topicName.' pembahasan try out');

            $response = Http::timeout((int) config('services.google.timeout', 20))
                ->get('https://www.googleapis.com/youtube/v3/search', [
                    'part' => 'snippet',
                    'q' => $query,
                    'type' => 'video',
                    'maxResults' => 1,
                    'key' => $apiKey,
                ]);

            if (! $response->successful()) {
                continue;
            }

            $videoId = data_get($response->json(), 'items.0.id.videoId');
            $title = data_get($response->json(), 'items.0.snippet.title');

            if (! is_string($videoId) || ! is_string($title)) {
                continue;
            }

            $items[] = [
                'topic' => $topicName,
                'title' => Str::limit(strip_tags($title), 100),
                'url' => 'https://www.youtube.com/watch?v='.$videoId,
                'source' => 'youtube_api',
            ];
        }

        return $items !== [] ? $items : $this->buildSearchFallback($subjectName, $topicNames, $maxItems);
    }

    private function buildSearchFallback(string $subjectName, array $topicNames, int $maxItems): array
    {
        return collect(array_slice($topicNames, 0, $maxItems))
            ->map(function ($topicName) use ($subjectName) {
                $query = urlencode(trim($subjectName.' '.$topicName.' pembahasan try out'));

                return [
                    'topic' => $topicName,
                    'title' => "Cari video {$topicName}",
                    'url' => "https://www.google.com/search?q={$query}",
                    'source' => 'google_search_link',
                ];
            })
            ->values()
            ->all();
    }
}

