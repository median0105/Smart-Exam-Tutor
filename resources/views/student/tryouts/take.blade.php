<x-app-layout>
    <x-slot name="header"><div><p class="text-sm uppercase tracking-[0.3em] text-blue-200">Exam Session</p><h1 class="mt-2 text-3xl font-semibold text-white">{{ $attempt->tryout->title }}</h1></div></x-slot>
    <form method="POST" action="{{ route('student.tryouts.submit', $attempt) }}" class="space-y-6">@csrf
        @foreach($attempt->tryout->questions as $index => $question)
            <div class="panel p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-500">Soal {{ $index + 1 }} / {{ $question->topic->name }} / {{ $question->difficulty }}</p>
                        <div class="mt-3 text-lg font-medium text-slate-900">{!! nl2br(e($question->body)) !!}</div>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600">{{ $question->points }} poin</span>
                </div>
                <div class="mt-6 space-y-3">
                    @foreach($question->options as $option)
                        <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 p-4 transition hover:border-blue-300 hover:bg-blue-50">
                            <input type="radio" name="answers[{{ $question->id }}][option_id]" value="{{ $option->id }}" class="mt-1">
                            <div><p class="font-semibold text-slate-700">{{ $option->option_label }}</p><p class="text-slate-600">{{ $option->content }}</p></div>
                        </label>
                    @endforeach
                    <input type="hidden" name="answers[{{ $question->id }}][time_spent_seconds]" value="60">
                </div>
            </div>
        @endforeach
        <button class="w-full rounded-full bg-white px-6 py-4 text-sm font-semibold text-slate-900">Kirim jawaban dan analisis</button>
    </form>
</x-app-layout>
