<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LMS PRO') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#0f172a] antialiased text-slate-900 dark:text-white">

<header class="fixed top-0 w-full z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800">
    <nav class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                <span class="text-white font-black text-xl">L</span>
            </div>
            <span class="font-extrabold text-xl tracking-tighter">LMS<span class="text-indigo-600">PRO</span></span>
        </div>

        <div class="flex items-center space-x-4">
            @auth
                <span class="hidden md:block text-sm font-medium text-gray-500 me-2">
                            Logged in as: <span class="text-indigo-600">{{ auth()->user()->name }}</span>
                        </span>

                @php
                    $dashboardRoute = auth()->user()->isAdmin() ? 'admin.dashboard' :
                                     (auth()->user()->isInstructor() ? 'instructor.dashboard' : 'student.dashboard');
                @endphp

                <a href="{{ route($dashboardRoute) }}" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/20">
                    Dashboard
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-600 px-4">
                        Log out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="font-bold text-gray-600 dark:text-gray-400 hover:text-gray-900 px-4">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition">Get Started</a>
                @endif
            @endauth
        </div>
    </nav>
</header>

<main class="relative pt-32 pb-20 px-6">
    <div class="max-w-5xl mx-auto text-center">
        <h1 class="text-5xl md:text-7xl font-black tracking-tight mb-8">
            Smart Way to <span class="text-indigo-600">Learn</span> & <span class="text-purple-600">Grow</span>
        </h1>
        <p class="text-xl text-gray-500 dark:text-gray-400 mb-10 max-w-2xl mx-auto leading-relaxed">
            Welcome to LMS PRO. A comprehensive platform designed for students to learn, and for instructors to share their knowledge with the world.
        </p>

        @auth
            <div class="inline-flex items-center p-1 px-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-full border border-indigo-100 dark:border-indigo-800">
                <span class="px-3 py-1 bg-indigo-600 text-white text-xs font-black uppercase rounded-full tracking-widest">Active Session</span>
                <span class="px-3 text-sm font-bold text-indigo-600 italic">Ready to continue your journey?</span>
            </div>
        @else
            <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto bg-indigo-600 text-white px-10 py-4 rounded-2xl font-black text-lg hover:bg-indigo-700 transition shadow-2xl shadow-indigo-500/40 transform hover:-translate-y-1">
                    Join Now
                </a>
                <a href="#features" class="w-full sm:w-auto border border-gray-200 dark:border-gray-800 px-10 py-4 rounded-2xl font-black text-lg hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                    Learn More
                </a>
            </div>
        @endauth
    </div>

    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[600px] -z-10 opacity-30 blur-3xl overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-500 rounded-full"></div>
        <div class="absolute top-20 right-1/4 w-96 h-96 bg-purple-500 rounded-full"></div>
    </div>
</main>

</body>
</html>
