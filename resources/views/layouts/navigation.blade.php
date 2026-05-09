@php
    $user = Auth::user();
    $links = $user?->isAdmin()
        ? [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Mapel', 'route' => 'admin.subjects.index'],
            ['label' => 'Topik', 'route' => 'admin.topics.index'],
            ['label' => 'Materi', 'route' => 'admin.materials.index'],
            ['label' => 'Soal', 'route' => 'admin.questions.index'],
            ['label' => 'Try Out', 'route' => 'admin.tryouts.index'],
            ['label' => 'Performa', 'route' => 'admin.performances.index'],
        ]
        : [
            ['label' => 'Dashboard', 'route' => 'student.dashboard'],
            ['label' => 'Try Out', 'route' => 'student.tryouts.index'],
            ['label' => 'Analisis', 'route' => 'student.analysis'],
            ['label' => 'Rekomendasi', 'route' => 'student.recommendations'],
        ];
@endphp

<nav x-data="{ open: false }" class="fixed inset-x-0 top-0 z-50 border-b border-white/10 bg-slate-950/75 backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-20 items-center justify-between gap-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-[22px] border border-white/20 bg-white/12 shadow-[inset_0_1px_0_rgba(255,255,255,0.18)] backdrop-blur-md">
                    <x-application-logo class="h-8 w-8" />
                </div>
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-blue-200/80">ITS Platform</p>
                    <p class="text-lg font-semibold text-white">Smart Exam Tutor</p>
                </div>
            </a>

            <div class="hidden items-center gap-2 lg:flex">
                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                        class="{{ request()->routeIs($link['route']) ? 'bg-white text-slate-900' : 'text-slate-200 hover:bg-white/10 hover:text-white' }} rounded-full px-4 py-2 text-sm font-medium transition">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="hidden items-center gap-3 lg:flex">
                <a href="{{ route('profile.edit') }}"
                    class="rounded-full border border-white/15 px-4 py-2 text-sm font-medium text-slate-200 transition hover:border-white/30 hover:bg-white/10">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-blue-50">Keluar</button>
                </form>
            </div>

            <button @click="open = ! open"
                class="inline-flex rounded-2xl border border-white/10 p-3 text-slate-100 lg:hidden">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>
        </div>

        <div x-show="open" x-transition class="space-y-3 border-t border-white/10 py-4 lg:hidden">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                    class="block rounded-2xl px-4 py-3 text-sm {{ request()->routeIs($link['route']) ? 'bg-white text-slate-900' : 'bg-white/5 text-slate-200' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a href="{{ route('profile.edit') }}"
                class="block rounded-2xl bg-white/5 px-4 py-3 text-sm text-slate-200">Profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="block w-full rounded-2xl bg-white px-4 py-3 text-left text-sm font-semibold text-slate-900">Keluar</button>
            </form>
        </div>
    </div>
</nav>
