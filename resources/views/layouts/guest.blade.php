<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LMS System') }}</title>
    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg?v=1">



    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="antialiased font-sans text-slate-100 bg-gradient-to-br from-[#0f172a] via-[#0b1120] to-[#020617] h-full selection:bg-indigo-500 selection:text-white">
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">

    {{-- Floating Gradient Blobs --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40rem] h-[40rem] bg-indigo-600/20 rounded-full mix-blend-screen filter blur-[100px] animate-pulse"></div>
        <div class="absolute top-[20%] -right-[10%] w-[35rem] h-[35rem] bg-purple-600/20 rounded-full mix-blend-screen filter blur-[100px] opacity-70"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[45rem] h-[45rem] bg-blue-600/10 rounded-full mix-blend-screen filter blur-[120px]"></div>
    </div>

    <div class="z-10 mb-8 transform hover:scale-105 transition-transform duration-500">
        <a href="/" class="flex flex-col items-center gap-4">
             <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl shadow-2xl shadow-indigo-500/30 flex items-center justify-center rotate-3 border border-white/10 group">
                <span class="text-white text-4xl font-black italic group-hover:rotate-3 transition-transform duration-300">L</span>
            </div>
            <span class="text-2xl font-black tracking-tight text-white drop-shadow-lg">
                LMS <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">PRO</span>
            </span>
        </a>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white/5 backdrop-blur-2xl border border-white/10 shadow-2xl relative z-10 sm:rounded-[2rem] hover:shadow-indigo-500/10 transition-shadow duration-500 group">
        {{-- Subtle inner sheen --}}
        <div class="absolute inset-0 bg-gradient-to-b from-white/5 to-transparent rounded-[2rem] pointer-events-none"></div>

        <div class="relative">
            {{ $slot }}
        </div>
    </div>

    <div class="mt-8 text-center relative z-10 opacity-60">
        <p class="text-xs font-semibold tracking-widest uppercase text-slate-400">
            &copy; {{ date('Y') }} LMS PRO — All Rights Reserved
        </p>
    </div>
</div>
</body>
</html>
