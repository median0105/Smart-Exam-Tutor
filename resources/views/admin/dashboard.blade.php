<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-blue-200">Admin Control</p>
            <h1 class="mt-2 text-3xl font-semibold text-white">Dashboard Smart Exam Tutor</h1>
        </div>
    </x-slot>

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div class="stat-card"><p class="text-sm text-slate-500">Mata pelajaran</p><p class="mt-3 text-3xl font-semibold">{{ $stats['subjects'] }}</p></div>
        <div class="stat-card"><p class="text-sm text-slate-500">Siswa aktif</p><p class="mt-3 text-3xl font-semibold">{{ $stats['students'] }}</p></div>
        <div class="stat-card"><p class="text-sm text-slate-500">Bank soal</p><p class="mt-3 text-3xl font-semibold">{{ $stats['questions'] }}</p></div>
        <div class="stat-card"><p class="text-sm text-slate-500">Rata-rata skor</p><p class="mt-3 text-3xl font-semibold">{{ $averageScore }}</p></div>
    </div>

    <div class="mt-6 panel p-6">
        <h2 class="text-xl font-semibold">Aktivitas try out terbaru</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="text-slate-500">
                    <tr><th class="py-3">Siswa</th><th class="py-3">Try Out</th><th class="py-3">Skor</th><th class="py-3">Level</th></tr>
                </thead>
                <tbody>
                    @foreach ($recentAttempts as $attempt)
                        <tr class="border-t border-slate-100">
                            <td class="py-4">{{ $attempt->user->name }}</td>
                            <td class="py-4">{{ $attempt->tryout->title }}</td>
                            <td class="py-4">{{ $attempt->score }}</td>
                            <td class="py-4">{{ $attempt->detected_level }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
