<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" id="html-root">
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
        /* ═══════════════════════════════════════════════
           THEME TOKENS
        ════════════════════════════════════════════════ */
        :root {
            --bg:           #f8fafc;
            --bg2:          #f1f5f9;
            --surf:         #ffffff;
            --surf2:        #f8fafc;
            --surf3:        #f1f5f9;
            --border:       #e2e8f0;
            --border2:      #cbd5e1;
            --text:         #0f172a;
            --text2:        #334155;
            --muted:        #64748b;
            --muted2:       #94a3b8;
            --nav-bg:       rgba(255,255,255,0.92);
            --nav-border:   #e2e8f0;
            --header-bg:    rgba(255,255,255,0.85);
            --accent:       #6366f1;
            --accent-hover: #4f46e5;
            --glow1:        rgba(99,102,241,0.06);
            --glow2:        rgba(139,92,246,0.06);
            --shadow:       0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:    0 4px 16px rgba(0,0,0,0.08);
        }

        html.dark {
            --bg:           #080c14;
            --bg2:          #0d1220;
            --surf:         #0d1220;
            --surf2:        #111827;
            --surf3:        #1a2235;
            --border:       rgba(255,255,255,0.07);
            --border2:      rgba(255,255,255,0.12);
            --text:         #f1f5f9;
            --text2:        #cbd5e1;
            --muted:        #64748b;
            --muted2:       #94a3b8;
            --nav-bg:       rgba(8,12,20,0.92);
            --nav-border:   rgba(255,255,255,0.07);
            --header-bg:    rgba(13,18,32,0.85);
            --accent:       #818cf8;
            --accent-hover: #6366f1;
            --glow1:        rgba(99,102,241,0.10);
            --glow2:        rgba(139,92,246,0.10);
            --shadow:       0 1px 3px rgba(0,0,0,0.4);
            --shadow-md:    0 4px 24px rgba(0,0,0,0.4);
        }

        /* ── BASE ── */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            transition: background .25s, color .25s;
            min-height: 100vh;
            margin: 0;
        }

        /* ── AMBIENT GLOWS (theme-aware) ── */
        .glow-tl {
            position: fixed; top: 0; left: 25%;
            width: 500px; height: 500px;
            background: var(--glow1);
            border-radius: 50%; filter: blur(100px);
            pointer-events: none; z-index: 0;
        }
        .glow-br {
            position: fixed; bottom: 0; right: 25%;
            width: 500px; height: 500px;
            background: var(--glow2);
            border-radius: 50%; filter: blur(100px);
            pointer-events: none; z-index: 0;
        }

        /* ── NAV ── */
        .lms-nav {
            background: var(--nav-bg);
            border-bottom: 1px solid var(--nav-border);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: sticky; top: 0; z-index: 50;
            transition: background .25s, border-color .25s;
        }

        /* ── HEADER SLOT ── */
        .lms-header {
            background: var(--header-bg);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            transition: background .25s, border-color .25s;
        }

        /* ── THEME TOGGLE ── */
        .theme-toggle {
            display: flex; align-items: center; justify-content: center;
            width: 36px; height: 36px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--surf2);
            color: var(--muted);
            cursor: pointer;
            transition: all .2s;
            flex-shrink: 0;
        }
        .theme-toggle:hover {
            background: var(--surf3);
            color: var(--text);
            border-color: var(--border2);
        }
        .theme-toggle svg { width: 16px; height: 16px; }

        /* Sun shows in dark mode, moon in light mode */
        .icon-sun  { display: none; }
        .icon-moon { display: block; }
        html.dark .icon-sun  { display: block; }
        html.dark .icon-moon { display: none; }

        /* ── CARD & SURFACE HELPERS ── */
        .card {
            background: var(--surf);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);
            transition: background .25s, border-color .25s;
        }

        /* ── LIGHT MODE NAV LINK OVERRIDES ── */
        html:not(.dark) .lms-nav a,
        html:not(.dark) .lms-nav button { color: var(--text2); }
        html:not(.dark) .lms-nav a:hover { color: var(--text); }

        /* Role badge light mode */
        html:not(.dark) .role-badge {
            background: #f1f5f9;
            color: #475569;
            border-color: #e2e8f0;
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 99px; }
    </style>

    {{-- Prevent flash of wrong theme --}}
    <script>
        (function() {
            const theme = localStorage.getItem('lms-theme') || 'light';
            if (theme === 'dark') document.getElementById('html-root').classList.add('dark');
        })();
    </script>
</head>

<body class="antialiased">
<div class="min-h-screen relative overflow-x-hidden">

    <div class="glow-tl"></div>
    <div class="glow-br"></div>

    @include('layouts.navigation')

    @isset($header)
        <header class="lms-header relative z-10 shadow-sm">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main class="relative z-10">
        {{ $slot }}
    </main>
</div>

<script>
    function toggleTheme() {
        const html = document.getElementById('html-root');
        const isDark = html.classList.toggle('dark');
        localStorage.setItem('lms-theme', isDark ? 'dark' : 'light');
    }
</script>
</body>
</html>