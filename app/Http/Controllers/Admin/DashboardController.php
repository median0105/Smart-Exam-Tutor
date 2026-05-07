<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use App\Models\TryoutAttempt;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $recentAttempts = TryoutAttempt::with(['user', 'tryout.subject'])
            ->latest('submitted_at')
            ->where('status', 'submitted')
            ->take(8)
            ->get();

        return view('admin.dashboard', [
            'stats' => [
                'subjects' => Subject::count(),
                'students' => User::where('role', User::ROLE_STUDENT)->count(),
                'questions' => Question::count(),
                'attempts' => TryoutAttempt::where('status', 'submitted')->count(),
            ],
            'averageScore' => round((float) TryoutAttempt::where('status', 'submitted')->avg('score'), 2),
            'recentAttempts' => $recentAttempts,
        ]);
    }
}
