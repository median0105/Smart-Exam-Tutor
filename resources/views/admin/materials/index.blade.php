<x-app-layout>
    <x-slot name="header"><div><p class="text-sm uppercase tracking-[0.3em] text-blue-200">Tutoring Model</p><h1 class="mt-2 text-3xl font-semibold text-white">Kelola Materi Pembelajaran</h1></div></x-slot>
    <div class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
        <form method="POST" action="{{ route('admin.materials.store') }}" class="panel p-6">@csrf
            <select name="subject_id" class="input-shell admin-input-shell">@foreach($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach</select>
            <select name="topic_id" class="input-shell admin-input-shell">@foreach($topics as $topic)<option value="{{ $topic->id }}">{{ $topic->subject->name }} - {{ $topic->name }}</option>@endforeach</select>
            <input name="title" class="input-shell admin-input-shell" placeholder="Judul materi">
            <input name="tags" class="input-shell admin-input-shell" placeholder="Tag, dipisah koma">
            <div class="grid gap-4 sm:grid-cols-2"><select name="difficulty" class="input-shell admin-input-shell"><option value="easy">Mudah</option><option value="medium">Sedang</option><option value="hard">Sulit</option></select><input type="number" name="estimated_minutes" class="input-shell admin-input-shell" value="15"></div>
            <textarea name="summary" class="input-shell admin-input-shell" placeholder="Ringkasan"></textarea>
            <textarea name="content" class="input-shell admin-input-shell min-h-48" placeholder="Isi materi"></textarea>
            <button class="mt-4 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Simpan</button>
        </form>
        <div class="panel p-6"><h2 class="text-xl font-semibold">Materi tersedia</h2><div class="mt-4 space-y-4">@foreach($materials as $material)<div class="rounded-3xl border border-slate-200 p-4"><div class="flex items-start justify-between gap-4"><div><p class="font-semibold">{{ $material->title }}</p><p class="text-sm text-slate-500">{{ $material->subject->name }} / {{ $material->topic->name }} / {{ $material->difficulty }}</p><p class="mt-2 text-sm text-slate-600">{{ $material->summary }}</p></div><form method="POST" action="{{ route('admin.materials.destroy', $material) }}">@csrf @method('DELETE')<button class="rounded-full border border-rose-200 px-4 py-2 text-sm text-rose-500">Hapus</button></form></div></div>@endforeach</div></div>
    </div>
</x-app-layout>
