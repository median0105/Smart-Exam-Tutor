<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    public function index(): View
    {
        return view('admin.topics.index', [
            'topics' => Topic::with('subject')->latest()->get(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target_mastery_percentage' => ['required', 'integer', 'min:50', 'max:100'],
        ]);

        Topic::create([
            ...$data,
            'slug' => Str::slug($data['name']),
        ]);

        return back()->with('status', 'Topik berhasil ditambahkan.');
    }

    public function update(Request $request, Topic $topic): RedirectResponse
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target_mastery_percentage' => ['required', 'integer', 'min:50', 'max:100'],
        ]);

        $topic->update([
            ...$data,
            'slug' => Str::slug($data['name']),
        ]);

        return back()->with('status', 'Topik berhasil diperbarui.');
    }

    public function destroy(Topic $topic): RedirectResponse
    {
        $topic->delete();

        return back()->with('status', 'Topik berhasil dihapus.');
    }
}
