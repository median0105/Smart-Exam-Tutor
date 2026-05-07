<x-app-layout>
    <x-slot name="header"><div><p class="text-sm uppercase tracking-[0.3em] text-blue-200">Assessment Engine</p><h1 class="mt-2 text-3xl font-semibold text-white">Kelola Try Out</h1></div></x-slot>
    <div class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
        <form method="POST" action="{{ route('admin.tryouts.store') }}" class="panel p-6">@csrf
            <select name="subject_id" class="input-shell">@foreach($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach</select>
            <input name="title" class="input-shell" placeholder="Judul try out">
            <textarea name="description" class="input-shell" placeholder="Deskripsi try out"></textarea>
            <div class="grid gap-4 sm:grid-cols-3"><input type="number" name="duration_minutes" class="input-shell" value="60"><input type="number" name="question_count" class="input-shell" value="20"><select name="difficulty_mix" class="input-shell"><option value="foundation">Foundation</option><option value="balanced">Balanced</option><option value="challenge">Challenge</option></select></div>
            <textarea name="question_ids" class="input-shell min-h-28" placeholder="Masukkan ID soal dipisah koma, contoh: 1,2,3,4"></textarea>
            <div class="mt-3 text-xs text-slate-500">Soal tersedia: @foreach($questions as $question)<span class="mr-2 inline-flex rounded-full bg-slate-100 px-2 py-1">{{ $question->id }}-{{ $question->topic->name }}</span>@endforeach</div>
            <button class="mt-4 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Simpan</button>
        </form>
        <div class="panel p-6"><h2 class="text-xl font-semibold">Try out tersedia</h2><div class="mt-4 space-y-4">@foreach($tryouts as $tryout)<div class="rounded-3xl border border-slate-200 p-4"><div class="flex items-start justify-between gap-4"><div><p class="font-semibold">{{ $tryout->title }}</p><p class="text-sm text-slate-500">{{ $tryout->subject->name }} / {{ $tryout->question_count }} soal / {{ $tryout->duration_minutes }} menit</p><p class="mt-2 text-sm text-slate-600">{{ $tryout->description }}</p></div><form method="POST" action="{{ route('admin.tryouts.destroy', $tryout) }}">@csrf @method('DELETE')<button class="rounded-full border border-rose-200 px-4 py-2 text-sm text-rose-500">Hapus</button></form></div></div>@endforeach</div></div>
    </div>
</x-app-layout>
