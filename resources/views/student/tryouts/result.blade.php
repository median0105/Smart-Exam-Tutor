<x-app-layout>
    <x-slot name="header"><div><p class="text-sm uppercase tracking-[0.3em] text-blue-200">Hasil & Feedback</p><h1 class="mt-2 text-3xl font-semibold text-white">{{ $attempt->tryout->title }}</h1></div></x-slot>
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div class="stat-card"><p class="text-sm text-slate-500">Skor</p><p class="mt-3 text-3xl font-semibold">{{ $attempt->score }}</p></div>
        <div class="stat-card"><p class="text-sm text-slate-500">Level</p><span class="badge-level mt-3 bg-violet-100 text-violet-700">{{ $attempt->detected_level }}</span></div>
        <div class="stat-card"><p class="text-sm text-slate-500">Benar</p><p class="mt-3 text-3xl font-semibold">{{ $attempt->correct_answers }}</p></div>
        <div class="stat-card"><p class="text-sm text-slate-500">Salah / kosong</p><p class="mt-3 text-3xl font-semibold">{{ $attempt->wrong_answers + $attempt->unanswered_answers }}</p></div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
        <div class="space-y-6">
            <div class="panel p-6"><h2 class="text-xl font-semibold">Feedback otomatis</h2><p class="mt-4 text-slate-600">{{ $attempt->automated_feedback }}</p><p class="mt-4 rounded-3xl bg-blue-50 p-4 text-sm text-blue-800">{{ $attempt->study_advice }}</p></div>
            <div class="panel p-6"><h2 class="text-xl font-semibold">Pembahasan jawaban</h2><div class="mt-4 space-y-4">@foreach($attempt->answers as $answer)<div class="rounded-3xl border border-slate-200 p-4"><div class="flex items-start justify-between gap-4"><div><p class="font-semibold">{{ $answer->question->topic->name }}</p><p class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($answer->question->body), 180) }}</p><p class="mt-3 text-sm text-slate-500">Pilihan Anda: {{ $answer->selectedOption->option_label ?? '-' }}</p></div><span class="{{ $answer->is_correct ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }} rounded-full px-3 py-2 text-xs font-semibold">{{ $answer->is_correct ? 'Benar' : 'Perlu review' }}</span></div><div class="mt-3 rounded-2xl bg-slate-50 p-4 text-sm text-slate-700">{{ $answer->question->explanation }}</div></div>@endforeach</div></div>
        </div>
        <div class="space-y-6">
            <div class="panel p-6"><h2 class="text-xl font-semibold">Kelemahan topik</h2><div class="mt-4 space-y-4">@foreach(($attempt->weakness_vector ?? []) as $topicId => $weakness)<div><div class="mb-2 flex items-center justify-between text-sm"><span>{{ optional($attempt->answers->firstWhere('topic_id', (int) $topicId)?->topic)->name ?? 'Topik' }}</span><span>{{ round($weakness * 100, 1) }}%</span></div><div class="h-3 rounded-full bg-slate-100"><div class="h-3 rounded-full bg-gradient-to-r from-blue-500 to-violet-500" style="width: {{ min(100, $weakness * 100) }}%"></div></div></div>@endforeach</div></div>
            <div class="panel p-6"><h2 class="text-xl font-semibold">Rekomendasi materi & soal</h2><div class="mt-4 space-y-4">@foreach($attempt->recommendations as $recommendation)<div class="rounded-3xl border border-slate-200 p-4"><p class="text-xs uppercase tracking-[0.2em] text-blue-600">{{ $recommendation->category }}</p><p class="mt-2 font-semibold">{{ $recommendation->recommendable->title ?? 'Soal latihan baru' }}</p><p class="mt-2 text-sm text-slate-600">{{ $recommendation->reason }}</p><p class="mt-2 text-xs text-slate-500">Cosine similarity: {{ $recommendation->similarity_score }}</p></div>@endforeach</div></div>
        </div>
    </div>
</x-app-layout>
