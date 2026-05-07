<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Tryout;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TryoutController extends Controller
{
    public function index(): View
    {
        return view('admin.tryouts.index', [
            'tryouts' => Tryout::with(['subject', 'questions.topic'])->latest()->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'questions' => Question::with('topic')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:10', 'max:300'],
            'question_count' => ['required', 'integer', 'min:5', 'max:200'],
            'difficulty_mix' => ['required', 'in:foundation,balanced,challenge'],
            'question_ids' => ['required', 'string'],
        ]);

        $tryout = Tryout::create([
            ...$data,
            'slug' => Str::slug($data['title'].'-'.Str::random(5)),
            'is_published' => true,
        ]);

        $this->syncQuestions($tryout, $data['question_ids']);

        return back()->with('status', 'Try out berhasil dibuat.');
    }

    public function update(Request $request, Tryout $tryout): RedirectResponse
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:10', 'max:300'],
            'question_count' => ['required', 'integer', 'min:5', 'max:200'],
            'difficulty_mix' => ['required', 'in:foundation,balanced,challenge'],
            'question_ids' => ['required', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $tryout->update([
            ...$data,
            'slug' => Str::slug($data['title'].'-'.$tryout->id),
            'is_published' => (bool) ($data['is_published'] ?? false),
        ]);

        $this->syncQuestions($tryout, $data['question_ids']);

        return back()->with('status', 'Try out berhasil diperbarui.');
    }

    public function destroy(Tryout $tryout): RedirectResponse
    {
        $tryout->delete();

        return back()->with('status', 'Try out berhasil dihapus.');
    }

    private function syncQuestions(Tryout $tryout, string $questionIds): void
    {
        $syncData = collect(explode(',', $questionIds))
            ->map(fn ($id) => (int) trim($id))
            ->filter()
            ->values()
            ->mapWithKeys(fn (int $id, int $index) => [$id => ['position' => $index + 1]])
            ->all();

        $tryout->questions()->sync($syncData);
    }
}
