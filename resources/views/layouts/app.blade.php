<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LMS PRO') }}</title>
    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg?v=1">



    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="antialiased font-sans text-slate-100 bg-gradient-to-br from-[#0f172a] via-[#0b1120] to-[#020617] h-full selection:bg-indigo-500 selection:text-white">
<div class="min-h-screen relative overflow-x-hidden">

    {{-- Ambient Glow --}}
    <div class="fixed top-0 left-1/4 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="fixed bottom-0 right-1/4 w-[500px] h-[500px] bg-purple-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    @include('layouts.navigation')

    @isset($header)
        <header class="relative z-10 bg-white/5 backdrop-blur-xl border-b border-white/10 shadow-lg shadow-black/5">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="relative">
                     {{ $header }}
                </div>
            </div>
        </header>
    @endisset

    <main>
        {{ $slot }}
    </main>
</div>
</body>
</html>
