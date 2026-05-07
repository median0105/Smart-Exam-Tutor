<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
use App\Models\TryoutAttempt;
use App\Services\TryoutEvaluationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TryoutController extends Controller
{
    public function index(): View
    {
        return view('student.tryouts.index', [
            'tryouts' => Tryout::with('subject')->where('is_published', true)->latest()->get(),
        ]);
    }

    public function show(Tryout $tryout): View
    {
        $tryout->load(['subject', 'questions.topic']);

        return view('student.tryouts.show', compact('tryout'));
    }

    public function start(Tryout $tryout): RedirectResponse
    {
        $attempt = TryoutAttempt::create([
            'user_id' => request()->user()->id,
            'tryout_id' => $tryout->id,
            'started_at' => now(),
            'status' => 'draft',
        ]);

        return redirect()->route('student.tryouts.take', $attempt);
    }

    public function take(TryoutAttempt $attempt): View
    {
        abort_unless($attempt->user_id === request()->user()->id, 403);

        $attempt->load(['tryout.subject', 'tryout.questions.options', 'tryout.questions.topic']);

        return view('student.tryouts.take', compact('attempt'));
    }

    public function submit(Request $request, TryoutAttempt $attempt, TryoutEvaluationService $service): RedirectResponse
    {
        abort_unless($attempt->user_id === request()->user()->id, 403);

        $payload = $request->validate([
            'answers' => ['array'],
            'answers.*.option_id' => ['nullable', 'integer'],
            'answers.*.time_spent_seconds' => ['nullable', 'integer', 'min:0'],
        ]);

        $service->evaluate($attempt, $payload['answers'] ?? []);

        return redirect()->route('student.tryouts.result', $attempt);
    }

    public function result(TryoutAttempt $attempt): View
    {
        abort_unless($attempt->user_id === request()->user()->id, 403);

        $attempt->load([
            'tryout.subject',
            'answers.question.topic',
            'answers.selectedOption',
            'recommendations.recommendable',
        ]);

        return view('student.tryouts.result', compact('attempt'));
    }
}
