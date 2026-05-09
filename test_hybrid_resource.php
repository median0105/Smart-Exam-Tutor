<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = $app->make(App\Services\GoogleLearningResourceService::class);
$result = $service->recommendVideos('Matematika', ['Aljabar', 'Fungsi', 'Peluang']);
var_export($result);
