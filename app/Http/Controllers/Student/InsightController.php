<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TryoutAttempt;
use Illuminate\Contracts\View\View;

class InsightController extends Controller
{
    public function analysis(): View
    {
        $user = request()->user();

        return view('student.insights.analysis', [
            'masteries' => $user->topicMasteries()->with('topic.subject')->orderByDesc('weakness_score')->get(),
            'attempts' => $user->tryoutAttempts()->with('tryout.subject')->where('status', 'submitted')->latest('submitted_at')->get(),
        ]);
    }

    public function recommendations(): View
    {
        $user = request()->user();
        $latestAttempt = TryoutAttempt::with('recommendations.recommendable.topic')
            ->where('user_id', $user->id)
            ->where('status', 'submitted')
            ->latest('submitted_at')
            ->first();

        return view('student.insights.recommendations', [
            'attempt' => $latestAttempt,
            'recommendations' => $latestAttempt?->recommendations ?? collect(),
        ]);
    }
}
