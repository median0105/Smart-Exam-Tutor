<x-app-layout>
    <x-slot name="header"><div><p class="text-sm uppercase tracking-[0.3em] text-blue-200">Personal Tutor</p><h1 class="mt-2 text-3xl font-semibold text-white">Rekomendasi belajar lanjutan</h1></div></x-slot>
    <div class="panel p-6">
        @if ($attempt)
            <p class="text-sm text-slate-500">Sumber rekomendasi: {{ $attempt->tryout->title ?? 'Try out terbaru' }}</p>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div class="rounded-3xl border border-blue-100 bg-blue-50/50 p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-blue-700">Analisis Otomatis</p>
                    <p class="mt-2 text-sm text-slate-700">{{ $attempt->automated_feedback }}</p>
                </div>
                <div class="rounded-3xl border border-emerald-100 bg-emerald-50/50 p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-emerald-700">Prioritas Belajar</p>
                    <p class="mt-2 text-sm text-slate-700">{{ $attempt->study_advice }}</p>
                </div>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-2">@foreach($recommendations as $recommendation)<div class="rounded-3xl border border-slate-200 p-5"><p class="text-xs uppercase tracking-[0.2em] text-blue-600">{{ $recommendation->category }}</p><h2 class="mt-2 text-xl font-semibold">{{ $recommendation->recommendable->title ?? 'Soal latihan' }}</h2><p class="mt-3 text-sm text-slate-600">{{ $recommendation->reason }}</p><p class="mt-3 text-xs text-slate-500">Topik: {{ $recommendation->recommendable->topic->name ?? '-' }}</p></div>@endforeach</div>

            @if(($resourceRecommendations ?? collect())->isNotEmpty())
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-slate-900">Sumber Belajar dari Google</h3>
                    <div class="mt-3 grid gap-4 md:grid-cols-3">
                        @foreach($resourceRecommendations as $resource)
                            <a href="{{ $resource['url'] }}" target="_blank" rel="noopener noreferrer" class="rounded-2xl border border-slate-200 p-4 transition hover:border-blue-300 hover:bg-blue-50/40">
                                <p class="text-xs uppercase tracking-[0.2em] text-blue-600">{{ $resource['source'] }}</p>
                                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $resource['title'] }}</p>
                                <p class="mt-2 text-xs text-slate-500">Topik: {{ $resource['topic'] }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <p class="text-slate-600">Selesaikan satu try out terlebih dahulu agar sistem dapat membuat rekomendasi berbasis kelemahan Anda.</p>
        @endif
    </div>
</x-app-layout>
