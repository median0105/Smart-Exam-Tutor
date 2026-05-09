<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = config('services.google_ai.api_key');
$model = config('services.google_ai.model');
$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}";

$response = Illuminate\Support\Facades\Http::timeout(30)->post($url, [
    'contents' => [[
        'parts' => [[
            'text' => 'Balas JSON: {"ok":true,"message":"test"}',
        ]],
    ]],
    'generationConfig' => [
        'temperature' => 0.1,
        'responseMimeType' => 'application/json',
    ],
]);

echo "STATUS: " . $response->status() . PHP_EOL;
echo "BODY: " . $response->body() . PHP_EOL;
