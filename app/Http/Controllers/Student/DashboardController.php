<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Recommendation;
use App\Models\Tryout;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = request()->user();
        $latestAttempt = $user->tryoutAttempts()
            ->with(['tryout.subject', 'recommendations.recommendable'])
            ->where('status', 'submitted')
            ->latest('submitted_at')
            ->first();
        $recentAttempts = $user->tryoutAttempts()
            ->with('tryout.subject')
            ->where('status', 'submitted')
            ->latest('submitted_at')
            ->take(5)
            ->get();
        $weakTopics = $user->topicMasteries()
            ->with('topic.subject')
            ->orderByDesc('weakness_score')
            ->take(5)
            ->get();
        $recommendations = $latestAttempt?->recommendations ?? collect();
        $materialRecommendations = $recommendations->where('category', Recommendation::CATEGORY_MATERIAL)->values();
        $questionRecommendations = $recommendations->where('category', Recommendation::CATEGORY_QUESTION)->values();
        $averageScore = round((float) $user->tryoutAttempts()->where('status', 'submitted')->avg('score'), 2);
        $progressPercentage = min(100, (int) round(($averageScore / 100) * 70) + min(30, $recentAttempts->count() * 6));
        $reviewTopics = $weakTopics->take(3)->pluck('topic.name')->values();

        return view('student.dashboard', [
            'availableTryouts' => Tryout::with('subject')->where('is_published', true)->take(6)->get(),
            'recentAttempts' => $recentAttempts,
            'weakTopics' => $weakTopics,
            'recommendations' => $recommendations,
            'materialRecommendations' => $materialRecommendations,
            'questionRecommendations' => $questionRecommendations,
            'averageScore' => $averageScore,
            'totalTryouts' => $recentAttempts->count(),
            'progressPercentage' => $progressPercentage,
            'reviewTopics' => $reviewTopics,
            'performanceSummary' => [
                'best_score' => (float) ($recentAttempts->max('score') ?? 0),
                'latest_score' => (float) ($recentAttempts->first()->score ?? 0),
                'consistency' => $recentAttempts->count() > 0 ? round(($recentAttempts->where('score', '>=', 70)->count() / $recentAttempts->count()) * 100) : 0,
            ],
        ]);
    }
}
