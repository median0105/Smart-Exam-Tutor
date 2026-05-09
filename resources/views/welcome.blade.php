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

<body class="h-screen overflow-hidden font-['Outfit',sans-serif]">
    <div
        class="relative h-screen overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.4),_transparent_24%),radial-gradient(circle_at_85%_15%,_rgba(139,92,246,0.33),_transparent_24%),linear-gradient(180deg,_#020617,_#0f172a_45%,_#111827)] text-white">

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

        <div class="relative z-10 mx-auto flex h-full max-w-7xl flex-col px-4 py-4 sm:px-6 sm:py-6 lg:px-8 lg:py-8">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/20 bg-white/12 shadow-[inset_0_1px_0_rgba(255,255,255,0.18)] backdrop-blur-md sm:h-14 sm:w-14 lg:h-16 lg:w-16 lg:rounded-[22px]">
                        <x-application-logo class="h-8 w-8 sm:h-9 sm:w-9 lg:h-10 lg:w-10" />
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-blue-200/80 sm:text-xs lg:text-sm lg:tracking-[0.3em]">Intelligent Tutoring System</p>
                        <p class="text-base font-semibold sm:text-lg lg:text-xl">Smart Exam Tutor</p>
                    </div>
                </div>
                <div class="flex shrink-0 gap-2 sm:gap-3">
                    <a href="{{ route('login') }}"
                        class="rounded-full border border-white/15 px-3 py-2 text-xs font-medium text-white/90 transition duration-200 hover:border-blue-300/60 hover:bg-blue-400/15 hover:text-white sm:px-4 sm:text-sm lg:px-5 lg:py-3">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="rounded-full bg-white px-3 py-2 text-xs font-semibold text-slate-900 transition duration-200 hover:bg-gradient-to-r hover:from-blue-200 hover:to-violet-200 hover:text-slate-950 sm:px-4 sm:text-sm lg:px-5 lg:py-3">Mulai Belajar</a>
                </div>
            </div>

            <section class="grid flex-1 items-center gap-5 py-4 sm:py-6 lg:grid-cols-[1.15fr_0.85fr] lg:gap-8 lg:py-8">
                <div>
                    <span
                        class="inline-flex rounded-full border border-blue-300/30 bg-blue-400/10 px-3 py-1.5 text-xs text-blue-100 sm:px-4 sm:py-2 sm:text-sm">Adaptive
                        try out, feedback otomatis, rekomendasi personal</span>
                    <h1 class="mt-4 max-w-3xl text-3xl font-semibold leading-tight sm:mt-5 sm:text-4xl lg:text-5xl xl:text-6xl">
                        Platform try out cerdas yang membaca
                        <span
                            class="bg-gradient-to-r from-blue-300 via-white to-violet-300 bg-clip-text text-transparent">kekuatan
                            dan kelemahan siswa</span>
                        secara real-time.
                    </h1>
                    <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-300 sm:text-base sm:leading-7 lg:text-lg lg:leading-8">
                        Smart Exam Tutor menggabungkan bank soal, analisis topik, klasifikasi level dengan KNN, dan
                        rekomendasi materi berbasis cosine similarity untuk membentuk pengalaman belajar yang
                        benar-benar adaptif.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-3 sm:mt-7 sm:gap-4">
                        <a href="{{ route('register') }}"
                            class="rounded-full bg-white px-4 py-2.5 text-xs font-semibold text-slate-900 shadow-xl shadow-blue-500/20 transition duration-200 hover:bg-gradient-to-r hover:from-blue-200 hover:to-violet-200 hover:text-slate-950 hover:shadow-blue-400/30 sm:px-5 sm:py-3 sm:text-sm lg:px-6 lg:py-4">Daftar
                            sebagai siswa</a>
                        <a href="{{ route('login') }}"
                            class="rounded-full border border-white/15 px-4 py-2.5 text-xs font-medium text-white transition duration-200 hover:border-blue-300/60 hover:bg-blue-400/15 hover:text-white sm:px-5 sm:py-3 sm:text-sm lg:px-6 lg:py-4">Masuk
                            ke dashboard</a>
                    </div>
                </div>

                <div class="panel-dark hidden p-4 md:block lg:p-6">
                    <div class="rounded-3xl bg-gradient-to-br from-blue-500/15 to-violet-500/15 p-4 lg:rounded-[28px] lg:p-6">
                        <div class="grid gap-3 sm:grid-cols-2 lg:gap-4">
                            <div class="rounded-3xl bg-white/10 p-4 lg:p-5">
                                <p class="text-sm text-blue-100">Level siswa</p>
                                <p class="mt-2 text-2xl font-semibold lg:mt-3 lg:text-3xl">Menengah</p>
                                <span
                                    class="mt-3 inline-flex rounded-full bg-emerald-400/20 px-3 py-1 text-xs text-emerald-200 lg:mt-4">Naik
                                    12%</span>
                            </div>
                            <div class="rounded-3xl bg-white/10 p-4 lg:p-5">
                                <p class="text-sm text-blue-100">Topik lemah</p>
                                <p class="mt-2 text-2xl font-semibold lg:mt-3 lg:text-3xl">2</p>
                                <p class="mt-3 text-xs text-slate-300 lg:mt-4">Persamaan Linear, Peluang</p>
                            </div>
                        </div>
                        <div class="mt-3 rounded-3xl bg-slate-950/40 p-4 lg:mt-4 lg:p-5">
                            <p class="text-sm text-slate-300">Feedback tutor</p>
                            <p class="mt-2 text-base font-medium lg:mt-3 lg:text-lg">Fokuskan 2 sesi belajar berikutnya pada Persamaan Linear
                                sebelum lanjut ke soal campuran sulit.</p>
                            <div class="mt-4 lg:mt-5">
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
