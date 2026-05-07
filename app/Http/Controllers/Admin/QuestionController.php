<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(): View
    {
        return view('admin.questions.index', [
            'questions' => Question::with(['subject', 'topic', 'options'])->latest()->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'topics' => Topic::with('subject')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'topic_id' => ['required', 'exists:topics,id'],
            'body' => ['required', 'string'],
            'explanation' => ['required', 'string'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'tags' => ['nullable', 'string'],
            'options_payload' => ['required', 'string'],
        ]);

        $question = Question::create([
            'subject_id' => $data['subject_id'],
            'topic_id' => $data['topic_id'],
            'body' => $data['body'],
            'explanation' => $data['explanation'],
            'difficulty' => $data['difficulty'],
            'points' => $data['points'],
            'tags' => $this->parseTags($data['tags'] ?? ''),
            'is_active' => true,
        ]);

        foreach ($this->parseOptions($data['options_payload']) as $option) {
            $question->options()->create($option);
        }

        return back()->with('status', 'Soal berhasil ditambahkan.');
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'topic_id' => ['required', 'exists:topics,id'],
            'body' => ['required', 'string'],
            'explanation' => ['required', 'string'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'tags' => ['nullable', 'string'],
            'options_payload' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $question->update([
            'subject_id' => $data['subject_id'],
            'topic_id' => $data['topic_id'],
            'body' => $data['body'],
            'explanation' => $data['explanation'],
            'difficulty' => $data['difficulty'],
            'points' => $data['points'],
            'tags' => $this->parseTags($data['tags'] ?? ''),
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        $question->options()->delete();
        foreach ($this->parseOptions($data['options_payload']) as $option) {
            $question->options()->create($option);
        }

        return back()->with('status', 'Soal berhasil diperbarui.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $question->delete();

        return back()->with('status', 'Soal berhasil dihapus.');
    }

    private function parseOptions(string $payload): array
    {
        return collect(preg_split('/\r\n|\r|\n/', trim($payload)))
            ->map(function (string $line) {
                [$label, $content, $isCorrect, $feedback] = array_pad(explode('|', $line), 4, null);

                return [
                    'option_label' => trim((string) $label),
                    'content' => trim((string) $content),
                    'is_correct' => trim((string) $isCorrect) === '1',
                    'feedback' => $feedback ? trim($feedback) : null,
                ];
            })
            ->filter(fn (array $option) => $option['option_label'] !== '' && $option['content'] !== '')
            ->values()
            ->all();
    }

    private function parseTags(string $tags): array
    {
        return collect(explode(',', $tags))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->values()
            ->all();
    }
}
