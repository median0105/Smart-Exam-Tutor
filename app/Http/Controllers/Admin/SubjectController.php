<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    public function index(): View
    {
        return view('admin.subjects.index', [
            'subjects' => Subject::latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:subjects,code'],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'max:255'],
        ]);

        Subject::create([
            ...$data,
            'slug' => Str::slug($data['name']),
            'color' => $data['color'] ?: 'from-blue-500 to-violet-500',
        ]);

        return back()->with('status', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:subjects,code,'.$subject->id],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $subject->update([
            ...$data,
            'slug' => Str::slug($data['name']),
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return back()->with('status', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return back()->with('status', 'Mata pelajaran berhasil dihapus.');
    }
}
