<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LearningMaterialController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuestionSyncController;
use App\Http\Controllers\Admin\StudentPerformanceController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\TryoutController as AdminTryoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\InsightController;
use App\Http\Controllers\Student\TryoutController as StudentTryoutController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tryouts', [StudentTryoutController::class, 'index'])->name('tryouts.index');
    Route::get('/tryouts/{tryout:slug}', [StudentTryoutController::class, 'show'])->name('tryouts.show');
    Route::post('/tryouts/{tryout:slug}/start', [StudentTryoutController::class, 'start'])->name('tryouts.start');
    Route::get('/attempts/{attempt}/take', [StudentTryoutController::class, 'take'])->name('tryouts.take');
    Route::post('/attempts/{attempt}/submit', [StudentTryoutController::class, 'submit'])->name('tryouts.submit');
    Route::get('/attempts/{attempt}/result', [StudentTryoutController::class, 'result'])->name('tryouts.result');
    Route::get('/analysis', [InsightController::class, 'analysis'])->name('analysis');
    Route::get('/recommendations', [InsightController::class, 'recommendations'])->name('recommendations');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

    Route::get('/topics', [TopicController::class, 'index'])->name('topics.index');
    Route::post('/topics', [TopicController::class, 'store'])->name('topics.store');
    Route::put('/topics/{topic}', [TopicController::class, 'update'])->name('topics.update');
    Route::delete('/topics/{topic}', [TopicController::class, 'destroy'])->name('topics.destroy');

    Route::get('/materials', [LearningMaterialController::class, 'index'])->name('materials.index');
    Route::post('/materials', [LearningMaterialController::class, 'store'])->name('materials.store');
    Route::put('/materials/{material}', [LearningMaterialController::class, 'update'])->name('materials.update');
    Route::delete('/materials/{material}', [LearningMaterialController::class, 'destroy'])->name('materials.destroy');

    Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::post('/questions/sync-external', QuestionSyncController::class)->name('questions.sync-external');
    Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    Route::get('/tryouts', [AdminTryoutController::class, 'index'])->name('tryouts.index');
    Route::post('/tryouts', [AdminTryoutController::class, 'store'])->name('tryouts.store');
    Route::put('/tryouts/{tryout}', [AdminTryoutController::class, 'update'])->name('tryouts.update');
    Route::delete('/tryouts/{tryout}', [AdminTryoutController::class, 'destroy'])->name('tryouts.destroy');

    Route::get('/performances', [StudentPerformanceController::class, 'index'])->name('performances.index');
});

require __DIR__.'/auth.php';
