<x-app-layout>
    <x-slot name="header"><div><p class="text-sm uppercase tracking-[0.3em] text-blue-200">Domain Model</p><h1 class="mt-2 text-3xl font-semibold text-white">Kelola Topik</h1></div></x-slot>
    <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <form method="POST" action="{{ route('admin.topics.store') }}" class="panel p-6">@csrf
            <h2 class="text-xl font-semibold">Tambah topik</h2>
            <select name="subject_id" class="input-shell">@foreach($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach</select>
            <input name="name" class="input-shell" placeholder="Nama topik">
            <input name="target_mastery_percentage" class="input-shell" type="number" value="75">
            <textarea name="description" class="input-shell" placeholder="Deskripsi topik"></textarea>
            <button class="mt-4 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Simpan</button>
        </form>
        <div class="panel p-6"><h2 class="text-xl font-semibold">Daftar topik</h2><div class="mt-4 space-y-4">@foreach($topics as $topic)<div class="rounded-3xl border border-slate-200 p-4"><div class="flex items-start justify-between gap-4"><div><p class="font-semibold">{{ $topic->name }}</p><p class="text-sm text-slate-500">{{ $topic->subject->name }} / Target mastery {{ $topic->target_mastery_percentage }}%</p></div><form method="POST" action="{{ route('admin.topics.destroy', $topic) }}">@csrf @method('DELETE')<button class="rounded-full border border-rose-200 px-4 py-2 text-sm text-rose-500">Hapus</button></form></div></div>@endforeach</div></div>
    </div>
</x-app-layout>
