<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = $app->make(App\Services\GoogleLearningRecommendationService::class);
$result = $service->generate([
    'subject' => 'Matematika',
    'score' => 45,
    'wrong_answers' => 11,
    'correct_answers' => 7,
    'level' => 'Menengah',
    'weak_topics' => [
        ['name' => 'Aljabar', 'wrong_ratio' => 0.72],
        ['name' => 'Persamaan Linear', 'wrong_ratio' => 0.64],
        ['name' => 'Fungsi', 'wrong_ratio' => 0.58],
    ],
]);

var_export($result);
