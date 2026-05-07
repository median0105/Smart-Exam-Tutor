<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smart Exam Tutor') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;outfit:400,500,600,700,800&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Outfit',sans-serif] antialiased">
    <div
        class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.35),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(139,92,246,0.25),_transparent_32%),linear-gradient(180deg,_#020617,_#0f172a_45%,_#111827)]">

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

        @include('layouts.navigation')

        <main class="mx-auto max-w-7xl px-4 pb-12 pt-6 sm:px-6 lg:px-8">
            @isset($header)
                <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        {{ $header }}
                    </div>
                </div>
            @endisset

            @include('partials.flash')

            {{ $slot }}
        </main>
    </div>
</body>

</html>