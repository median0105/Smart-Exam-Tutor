<x-app-layout>
    <x-slot name="header">
        <div class="hidden"></div>
    </x-slot>

    @php
        $level = auth()->user()->learning_level ?? 'Pemula';
        $levelBadge = match ($level) {
            'Mahir' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
            'Menengah' => 'bg-violet-100 text-violet-700 ring-violet-200',
            default => 'bg-blue-100 text-blue-700 ring-blue-200',
        };
    @endphp

    <div class="grid gap-6 xl:grid-cols-[280px_minmax(0,1fr)]">
        <aside class="panel-dark hidden p-5 text-white xl:sticky xl:top-24 xl:z-30 xl:block xl:self-start">
            <div class="rounded-[28px] bg-gradient-to-br from-blue-500 via-indigo-500 to-violet-600 p-5 shadow-2xl shadow-blue-500/30">
                <p class="text-xs uppercase tracking-[0.35em] text-blue-100">Student Space</p>
                <h2 class="mt-3 text-2xl font-semibold">Smart Exam Tutor</h2>
                <p class="mt-3 text-sm text-blue-100">Belajar lebih terarah dengan analisis performa, progres, dan rekomendasi adaptif.</p>
            </div>

            <nav class="mt-6 space-y-2">
                <a href="{{ route('student.dashboard') }}" class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3 text-sm font-medium text-white">
                    <span>Dashboard</span>
                    <span class="h-2.5 w-2.5 rounded-full bg-cyan-300"></span>
                </a>
                <a href="{{ route('student.tryouts.index') }}" class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-white/10 hover:text-white">
                    <span>Tryout</span>
                    <span class="ui-pill bg-white/10 text-slate-100">{{ $availableTryouts->count() }}</span>
                </a>
                <a href="{{ route('student.analysis') }}" class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-white/10 hover:text-white">
                    <span>Analisis</span>
                    <span class="ui-pill bg-white/10 text-slate-100">{{ $weakTopics->count() }}</span>
                </a>
                <a href="{{ route('student.recommendations') }}" class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-white/10 hover:text-white">
                    <span>Rekomendasi</span>
                    <span class="ui-pill bg-white/10 text-slate-100">{{ $recommendations->count() }}</span>
                </a>
            </nav>

            <div class="mt-8 rounded-[28px] border border-white/10 bg-white/5 p-5">
                <p class="text-sm font-medium text-slate-200">Topik review utama</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    @forelse($reviewTopics as $topic)
                        <span class="ui-pill rounded-full border border-white/10 bg-white/10 text-slate-100">{{ $topic }}</span>
                    @empty
                        <span class="text-sm text-slate-400">Belum ada topik prioritas.</span>
                    @endforelse
                </div>
            </div>
        </aside>

        <div class="relative z-10 space-y-6">
            <section class="panel overflow-hidden p-0">
                <div class="flex flex-col gap-8 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.25),_transparent_30%),radial-gradient(circle_at_top_right,_rgba(139,92,246,0.25),_transparent_28%),linear-gradient(135deg,_#ffffff,_#eef2ff_55%,_#ede9fe)] px-6 py-6 md:px-8 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-blue-600">Dashboard Student</p>
                        <h1 class="mt-3 break-words text-3xl font-semibold leading-tight text-slate-900 md:text-4xl">Belajar dengan tutor yang membaca kebutuhanmu.</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">Ringkasan progres, kelemahan topik, dan rekomendasi materi serta latihan soal ditampilkan dalam satu ruang belajar yang fokus dan modern.</p>
                    </div>

                    <div class="min-w-[260px] rounded-[28px] bg-slate-950 p-5 text-white shadow-2xl shadow-violet-500/20">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-slate-300">Halo, {{ auth()->user()->name }}</p>
                                <h2 class="mt-1 text-2xl font-semibold">Siap naik level?</h2>
                            </div>
                            <span class="ui-pill ring-1 {{ $levelBadge }}">{{ $level }}</span>
                        </div>
                        <div class="mt-6">
                            <div class="mb-2 flex items-center justify-between text-xs text-slate-300">
                                <span>Progress belajar</span>
                                <span>{{ $progressPercentage }}%</span>
                            </div>
                            <div class="h-3 rounded-full bg-white/10">
                                <div class="h-3 rounded-full bg-gradient-to-r from-cyan-400 via-blue-500 to-violet-500" style="width: {{ $progressPercentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid gap-5 sm:grid-cols-2 2xl:grid-cols-4">
                <div class="stat-card transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-slate-500">Total tryout dikerjakan</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $totalTryouts }}</p>
                    <p class="mt-2 text-xs text-slate-500">Riwayat terbaru yang sudah dinilai sistem.</p>
                </div>
                <div class="stat-card transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-slate-500">Nilai rata-rata</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $averageScore }}</p>
                    <p class="mt-2 text-xs text-slate-500">Menggambarkan kestabilan performa belajar.</p>
                </div>
                <div class="stat-card transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-slate-500">Level kemampuan</p>
                    <span class="badge-level mt-3 ring-1 {{ $levelBadge }}">{{ $level }}</span>
                    <p class="mt-3 text-xs text-slate-500">Hasil klasifikasi tryout berbasis KNN.</p>
                </div>
                <div class="stat-card transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-slate-500">Topik yang perlu dipelajari ulang</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $reviewTopics->count() }}</p>
                    <p class="mt-2 max-h-10 overflow-hidden text-xs text-slate-500">{{ $reviewTopics->join(', ') ?: 'Belum ada topik prioritas.' }}</p>
                </div>
            </section>

            <section class="grid gap-6 2xl:grid-cols-[1.15fr_0.85fr]">
                <div class="space-y-6">
                    <div class="panel p-6">
                        <div class="space-y-4">
                            <div>
                                <h2 class="text-xl font-semibold text-slate-900">Ringkasan performa</h2>
                                <p class="mt-1 text-sm text-slate-500">Grafik sederhana untuk membaca ritme belajar terbaru.</p>
                            </div>
                            <div class="grid w-full grid-cols-3 gap-3">
                                <div class="flex h-[92px] min-w-[120px] flex-col items-center justify-center rounded-2xl bg-slate-50 px-4 py-3 text-center">
                                    <p class="whitespace-nowrap text-[10px] uppercase leading-none tracking-[0.12em] text-slate-400">Best</p>
                                    <p class="mt-3 text-2xl font-semibold leading-none text-slate-900">{{ $performanceSummary['best_score'] }}</p>
                                </div>
                                <div class="flex h-[92px] min-w-[120px] flex-col items-center justify-center rounded-2xl bg-slate-50 px-4 py-3 text-center">
                                    <p class="whitespace-nowrap text-[10px] uppercase leading-none tracking-[0.12em] text-slate-400">Latest</p>
                                    <p class="mt-3 text-2xl font-semibold leading-none text-slate-900">{{ $performanceSummary['latest_score'] }}</p>
                                </div>
                                <div class="flex h-[92px] min-w-[120px] flex-col items-center justify-center rounded-2xl bg-slate-50 px-4 py-3 text-center">
                                    <p class="whitespace-nowrap text-[10px] uppercase leading-none tracking-[0.12em] text-slate-400">Consistency</p>
                                    <p class="mt-3 text-2xl font-semibold leading-none text-slate-900">{{ $performanceSummary['consistency'] }}%</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 grid items-end gap-3 sm:grid-cols-5">
                            @forelse($recentAttempts->reverse()->values() as $attempt)
                                <div class="flex flex-col items-center gap-3">
                                    <div class="flex h-44 w-full items-end rounded-[24px] bg-slate-100 p-2">
                                        <div class="w-full rounded-[18px] bg-gradient-to-t from-blue-500 via-indigo-500 to-violet-500 transition hover:opacity-90" style="height: {{ max(12, min(100, $attempt->score)) }}%"></div>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs font-medium text-slate-900">{{ $attempt->score }}</p>
                                        <p class="text-[11px] text-slate-500">{{ optional($attempt->submitted_at)->format('d M') }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">Grafik akan muncul setelah tryout pertama selesai.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="panel p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-slate-900">Rekomendasi materi</h2>
                                <p class="mt-1 text-sm text-slate-500">Materi dipilih berdasarkan kemiripan topik kelemahan kamu.</p>
                            </div>
                            <a href="{{ route('student.recommendations') }}" class="ui-pill-brand transition hover:bg-blue-100">Lihat detail</a>
                        </div>

                        <div class="mt-5 grid gap-4 md:grid-cols-2">
                            @forelse($materialRecommendations as $recommendation)
                                <div class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <span class="ui-pill-brand">Materi</span>
                                        <span class="text-xs font-medium text-slate-500">Skor {{ $recommendation->similarity_score }}</span>
                                    </div>
                                    <h3 class="mt-4 break-words text-lg font-semibold leading-7 text-slate-900">{{ $recommendation->recommendable->title ?? 'Materi belajar' }}</h3>
                                    <p class="mt-2 break-words text-sm leading-6 text-slate-600">{{ $recommendation->reason }}</p>
                                </div>
                            @empty
                                <div class="rounded-[28px] border border-dashed border-slate-300 bg-slate-50 p-6 text-sm text-slate-500 md:col-span-2">
                                    Materi rekomendasi akan muncul setelah hasil tryout dianalisis.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="panel p-6">
                        <h2 class="text-xl font-semibold text-slate-900">Progress belajar</h2>
                        <p class="mt-1 text-sm text-slate-500">Fokuskan ulang belajar pada area yang paling lemah lebih dulu.</p>
                        <div class="mt-5 space-y-4">
                            @forelse($weakTopics as $mastery)
                                <div>
                                    <div class="mb-2 flex items-center justify-between text-sm">
                                        <span class="font-medium text-slate-700">{{ $mastery->topic->name }}</span>
                                        <span class="text-slate-500">{{ $mastery->weakness_score }}%</span>
                                    </div>
                                    <div class="h-3 rounded-full bg-slate-100">
                                        <div class="h-3 rounded-full bg-gradient-to-r from-blue-500 to-violet-500" style="width: {{ min(100, $mastery->weakness_score) }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">Belum ada data kelemahan topik.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="panel p-6">
                        <h2 class="text-xl font-semibold text-slate-900">Rekomendasi latihan soal</h2>
                        <p class="mt-1 text-sm text-slate-500">Soal lanjutan disesuaikan dengan level dan topik yang masih perlu penguatan.</p>
                        <div class="mt-5 space-y-4">
                            @forelse($questionRecommendations as $recommendation)
                                <div class="rounded-[28px] border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <span class="ui-pill bg-violet-50 text-violet-700 ring-1 ring-violet-200">Latihan soal</span>
                                        <span class="text-xs font-medium text-slate-500">Skor {{ $recommendation->similarity_score }}</span>
                                    </div>
                                    <h3 class="mt-4 break-words text-lg font-semibold leading-7 text-slate-900">{{ $recommendation->recommendable->topic->name ?? 'Topik latihan' }}</h3>
                                    <p class="mt-2 break-words text-sm leading-6 text-slate-600">{{ $recommendation->reason }}</p>
                                </div>
                            @empty
                                <div class="rounded-[28px] border border-dashed border-slate-300 bg-slate-50 p-6 text-sm text-slate-500">
                                    Latihan soal rekomendasi akan muncul setelah sistem menemukan topik prioritas.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="panel p-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-slate-900">Tryout terbaru</h2>
                            <a href="{{ route('student.tryouts.index') }}" class="text-sm font-semibold text-blue-600">Lihat semua</a>
                        </div>
                        <div class="mt-5 space-y-4">
                            @forelse($recentAttempts as $attempt)
                                <div class="rounded-[26px] border border-slate-200 p-4 transition hover:bg-slate-50">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="break-words font-semibold leading-6 text-slate-900">{{ $attempt->tryout->title }}</p>
                                            <p class="mt-1 text-sm text-slate-500">{{ $attempt->tryout->subject->name }} &bull; {{ optional($attempt->submitted_at)->format('d M Y H:i') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-semibold text-slate-900">{{ $attempt->score }}</p>
                                            <p class="text-xs text-slate-500">{{ $attempt->detected_level }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">Belum ada tryout yang selesai dinilai.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
