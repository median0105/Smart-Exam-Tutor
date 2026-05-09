<?php

namespace App\Services\QuestionBank;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Arr;
use RuntimeException;

class ExternalQuestionBankClient
{
    public function __construct(private readonly HttpFactory $http)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchQuestions(int $page = 1, int $perPage = 50): array
    {
        $provider = (string) config('services.question_bank.provider', 'opentdb');
        $baseUrl = (string) config('services.question_bank.base_url');
        $endpoint = (string) config('services.question_bank.endpoint', '/questions');
        $token = (string) config('services.question_bank.token');
        $timeout = (int) config('services.question_bank.timeout', 15);

        if ($baseUrl === '') {
            throw new RuntimeException('QUESTION_BANK_BASE_URL belum diatur.');
        }

        $request = $this->http->baseUrl(rtrim($baseUrl, '/'))
            ->timeout($timeout)
            ->acceptJson();

        if ($token !== '') {
            $request = $request->withToken($token);
        }

        $query = $provider === 'opentdb'
            ? ['amount' => min(50, max(1, $perPage)), 'type' => 'multiple']
            : ['page' => $page, 'per_page' => $perPage];

        $response = $request
            ->get(ltrim($endpoint, '/'), $query)
            ->throw()
            ->json();

        if ($provider === 'opentdb' && is_array(Arr::get($response, 'results'))) {
            return Arr::get($response, 'results', []);
        }

        if (is_array(Arr::get($response, 'data'))) {
            return Arr::get($response, 'data', []);
        }

        if (is_array(Arr::get($response, 'questions'))) {
            return Arr::get($response, 'questions', []);
        }

        return is_array($response) ? array_values($response) : [];
    }
}
