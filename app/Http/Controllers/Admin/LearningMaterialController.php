<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LearningMaterial;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LearningMaterialController extends Controller
{
    public function index(): View
    {
        return view('admin.materials.index', [
            'materials' => LearningMaterial::with(['subject', 'topic'])->latest()->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'topics' => Topic::with('subject')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'topic_id' => ['required', 'exists:topics,id'],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'estimated_minutes' => ['required', 'integer', 'min:5', 'max:240'],
            'tags' => ['nullable', 'string'],
        ]);

        LearningMaterial::create([
            ...$data,
            'slug' => Str::slug($data['title'].'-'.Str::random(5)),
            'tags' => $this->parseTags($data['tags'] ?? ''),
            'is_published' => true,
        ]);

        return back()->with('status', 'Materi berhasil ditambahkan.');
    }

    public function update(Request $request, LearningMaterial $material): RedirectResponse
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'topic_id' => ['required', 'exists:topics,id'],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'estimated_minutes' => ['required', 'integer', 'min:5', 'max:240'],
            'tags' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $material->update([
            ...$data,
            'slug' => Str::slug($data['title'].'-'.$material->id),
            'tags' => $this->parseTags($data['tags'] ?? ''),
            'is_published' => (bool) ($data['is_published'] ?? false),
        ]);

        return back()->with('status', 'Materi berhasil diperbarui.');
    }

    public function destroy(LearningMaterial $material): RedirectResponse
    {
        $material->delete();

        return back()->with('status', 'Materi berhasil dihapus.');
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
