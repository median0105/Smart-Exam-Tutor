<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Exam Tutor</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;outfit:400,500,600,700,800&display=swap"
        rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Outfit',sans-serif]">
    <div
        class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.4),_transparent_24%),radial-gradient(circle_at_85%_15%,_rgba(139,92,246,0.33),_transparent_24%),linear-gradient(180deg,_#020617,_#0f172a_45%,_#111827)] text-white">

        <!-- Decorative elements -->
        <div class="pointer-events-none absolute inset-0">
            <div class="deco-ring left-[9%] top-[14%] hidden h-20 w-20 lg:block"></div>
            <div class="deco-ring bottom-[12%] right-[8%] hidden h-28 w-28 lg:block"></div>
            <div class="deco-pill left-[11%] top-[33%] hidden h-10 w-24 -rotate-45 lg:block"></div>
            <div class="deco-pill left-[15%] top-[41%] hidden h-7 w-16 -rotate-45 lg:block"></div>
            <div class="deco-pill bottom-[16%] left-[18%] hidden h-12 w-32 rotate-[140deg] lg:block"></div>
            <div class="deco-pill bottom-[10%] left-[25%] hidden h-10 w-28 rotate-[178deg] lg:block"></div>
            <div class="deco-pill right-[15%] bottom-[20%] hidden h-7 w-16 -rotate-12 lg:block"></div>
            <div class="deco-pill right-[11%] bottom-[14%] hidden h-7 w-16 rotate-[8deg] lg:block"></div>

            <svg class="deco-wave left-[53%] top-[16%] hidden h-64 w-64 lg:block" viewBox="0 0 220 220" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M165 24C120 40 140 91 107 111C84 125 54 112 51 86C49 67 60 48 79 39" stroke="currentColor"
                    stroke-linecap="round" stroke-width="28" />
            </svg>

            <svg class="deco-wave right-[8%] top-[24%] hidden h-72 w-56 lg:block" viewBox="0 0 180 240" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M72 26C117 30 145 58 142 94C138 140 90 142 88 178C87 200 98 214 118 224" stroke="currentColor"
                    stroke-linecap="round" stroke-width="30" />
            </svg>

            <svg class="absolute left-[-4%] bottom-[2%] hidden h-48 w-48 rotate-12 text-blue-950/20 lg:block"
                viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M110 18C62 44 24 92 26 152" stroke="currentColor" stroke-linecap="round" stroke-width="28" />
            </svg>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-[22px] border border-white/20 bg-white/12 shadow-[inset_0_1px_0_rgba(255,255,255,0.18)] backdrop-blur-md">
                        <x-application-logo class="h-10 w-10" />
                    </div>
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-blue-200/80">Intelligent Tutoring System</p>
                        <p class="text-xl font-semibold">Smart Exam Tutor</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('login') }}"
                        class="rounded-full border border-white/15 px-5 py-3 text-sm font-medium text-white/90">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-900">Mulai Belajar</a>
                </div>
            </div>

            <section class="grid gap-10 py-20 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
                <div>
                    <span
                        class="rounded-full border border-blue-300/30 bg-blue-400/10 px-4 py-2 text-sm text-blue-100">Adaptive
                        try out, feedback otomatis, rekomendasi personal</span>
                    <h1 class="mt-6 max-w-3xl text-5xl font-semibold leading-tight md:text-6xl">
                        Platform try out cerdas yang membaca
                        <span
                            class="bg-gradient-to-r from-blue-300 via-white to-violet-300 bg-clip-text text-transparent">kekuatan
                            dan kelemahan siswa</span>
                        secara real-time.
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                        Smart Exam Tutor menggabungkan bank soal, analisis topik, klasifikasi level dengan KNN, dan
                        rekomendasi materi berbasis cosine similarity untuk membentuk pengalaman belajar yang
                        benar-benar adaptif.
                    </p>
                    <div class="mt-10 flex flex-wrap gap-4">
                        <a href="{{ route('register') }}"
                            class="rounded-full bg-white px-6 py-4 text-sm font-semibold text-slate-900 shadow-xl shadow-blue-500/20">Daftar
                            sebagai siswa</a>
                        <a href="{{ route('login') }}"
                            class="rounded-full border border-white/15 px-6 py-4 text-sm font-medium text-white">Masuk
                            ke dashboard</a>
                    </div>
                </div>

                <div class="panel-dark p-6">
                    <div class="rounded-[28px] bg-gradient-to-br from-blue-500/15 to-violet-500/15 p-6">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl bg-white/10 p-5">
                                <p class="text-sm text-blue-100">Level siswa</p>
                                <p class="mt-3 text-3xl font-semibold">Menengah</p>
                                <span
                                    class="mt-4 inline-flex rounded-full bg-emerald-400/20 px-3 py-1 text-xs text-emerald-200">Naik
                                    12%</span>
                            </div>
                            <div class="rounded-3xl bg-white/10 p-5">
                                <p class="text-sm text-blue-100">Topik lemah</p>
                                <p class="mt-3 text-3xl font-semibold">2</p>
                                <p class="mt-4 text-xs text-slate-300">Persamaan Linear, Peluang</p>
                            </div>
                        </div>
                        <div class="mt-4 rounded-3xl bg-slate-950/40 p-5">
                            <p class="text-sm text-slate-300">Feedback tutor</p>
                            <p class="mt-3 text-lg font-medium">Fokuskan 2 sesi belajar berikutnya pada Persamaan Linear
                                sebelum lanjut ke soal campuran sulit.</p>
                            <div class="mt-5">
                                <div class="mb-2 flex items-center justify-between text-xs text-slate-300">
                                    <span>Progress Mastery</span>
                                    <span>72%</span>
                                </div>
                                <div class="h-3 rounded-full bg-white/10">
                                    <div class="h-3 w-[72%] rounded-full bg-gradient-to-r from-blue-400 to-violet-400">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>

</html>