<x-app-layout>
    <x-slot name="header"><div><p class="text-sm uppercase tracking-[0.3em] text-blue-200">Bank Soal</p><h1 class="mt-2 text-3xl font-semibold text-white">Kelola Soal dan Jawaban</h1></div></x-slot>
    <div class="panel mb-6 p-6">
        <h2 class="text-lg font-semibold">Sinkronisasi API Bank Soal</h2>
        <p class="mt-2 text-sm text-slate-600">Tarik soal dari API eksternal ke database lokal agar bisa langsung dipakai pada try out, KNN, dan rekomendasi.</p>
        <form method="POST" action="{{ route('admin.questions.sync-external') }}" class="mt-4 grid gap-4 sm:grid-cols-3">@csrf
            <input type="number" min="1" max="50" name="max_pages" value="1" class="input-shell admin-input-shell" placeholder="Maks halaman">
            <input type="number" min="1" max="200" name="per_page" value="50" class="input-shell admin-input-shell" placeholder="Soal per halaman">
            <button class="rounded-full bg-blue-600 px-5 py-3 text-sm font-semibold text-white">Sync dari API</button>
        </form>
    </div>
    <div class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
        <form method="POST" action="{{ route('admin.questions.store') }}" class="panel p-6">@csrf
            <select name="subject_id" class="input-shell admin-input-shell">@foreach($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach</select>
            <select name="topic_id" class="input-shell admin-input-shell">@foreach($topics as $topic)<option value="{{ $topic->id }}">{{ $topic->subject->name }} - {{ $topic->name }}</option>@endforeach</select>
            <textarea name="body" class="input-shell admin-input-shell min-h-28" placeholder="Tulis soal"></textarea>
            <textarea name="explanation" class="input-shell admin-input-shell min-h-24" placeholder="Pembahasan soal"></textarea>
            <div class="grid gap-4 sm:grid-cols-2"><select name="difficulty" class="input-shell admin-input-shell"><option value="easy">Mudah</option><option value="medium">Sedang</option><option value="hard">Sulit</option></select><input type="number" name="points" class="input-shell admin-input-shell" value="5"></div>
            <input name="tags" class="input-shell admin-input-shell" placeholder="Tag, dipisah koma">
            <textarea name="options_payload" class="input-shell admin-input-shell min-h-40" placeholder="Format tiap baris: A|Isi opsi|0|feedback&#10;B|Isi opsi benar|1|feedback benar"></textarea>
            <button class="mt-4 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Simpan</button>
        </form>
        <div class="panel p-6"><h2 class="text-xl font-semibold">Daftar soal</h2><div class="mt-4 space-y-4">@foreach($questions as $question)<div class="rounded-3xl border border-slate-200 p-4"><div class="flex items-start justify-between gap-4"><div><p class="font-semibold">{{ \Illuminate\Support\Str::limit(strip_tags($question->body), 120) }}</p><p class="text-sm text-slate-500">{{ $question->subject->name }} / {{ $question->topic->name }} / {{ $question->difficulty }}</p><p class="mt-2 text-xs text-slate-500">Opsi: {{ $question->options->pluck('option_label')->join(', ') }}</p></div><form method="POST" action="{{ route('admin.questions.destroy', $question) }}">@csrf @method('DELETE')<button class="rounded-full border border-rose-200 px-4 py-2 text-sm text-rose-500">Hapus</button></form></div></div>@endforeach</div></div>
    </div>
</x-app-layout>
