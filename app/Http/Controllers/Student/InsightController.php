<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TryoutAttempt;
use App\Models\Topic;
use App\Services\GoogleLearningResourceService;
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

    public function recommendations(GoogleLearningResourceService $googleLearningResourceService): View
    {
        $user = request()->user();
        $latestAttempt = TryoutAttempt::with('recommendations.recommendable.topic')
            ->where('user_id', $user->id)
            ->where('status', 'submitted')
            ->latest('submitted_at')
            ->first();

        $resourceRecommendations = collect();

        if ($latestAttempt) {
            $weakTopicIds = array_keys($latestAttempt->weakness_vector ?? []);
            $weakTopics = Topic::query()
                ->whereIn('id', $weakTopicIds)
                ->get()
                ->sortByDesc(fn (Topic $topic) => (float) ($latestAttempt->weakness_vector[$topic->id] ?? 0))
                ->take(3)
                ->pluck('name')
                ->values()
                ->all();

            $resourceRecommendations = collect($googleLearningResourceService->recommendVideos(
                $latestAttempt->tryout->subject->name ?? 'Materi',
                $weakTopics
            ));
        }

        return view('student.insights.recommendations', [
            'attempt' => $latestAttempt,
            'recommendations' => $latestAttempt?->recommendations ?? collect(),
            'resourceRecommendations' => $resourceRecommendations,
        ]);
    }
}
