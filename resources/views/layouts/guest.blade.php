<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="guest-html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'LMS PRO') }}</title>
    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg?v=1">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── THEME TOKENS ── */
        :root {
            --bg-from:   #f8fafc;
            --bg-via:    #f1f5f9;
            --bg-to:     #e2e8f0;
            --card-bg:   rgba(255,255,255,0.85);
            --card-border: rgba(255,255,255,0.9);
            --card-shadow: 0 25px 60px rgba(99,102,241,0.10), 0 8px 24px rgba(0,0,0,0.06);
            --text:      #0f172a;
            --muted:     #64748b;
            --blob1:     rgba(99,102,241,0.12);
            --blob2:     rgba(139,92,246,0.10);
            --blob3:     rgba(59,130,246,0.07);
            --footer:    #94a3b8;
            --toggle-bg: #f1f5f9;
            --toggle-border: #e2e8f0;
            --toggle-color: #64748b;
        }
        html.dark {
            --bg-from:   #0f172a;
            --bg-via:    #0b1120;
            --bg-to:     #020617;
            --card-bg:   rgba(255,255,255,0.05);
            --card-border: rgba(255,255,255,0.10);
            --card-shadow: 0 25px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.04);
            --text:      #f1f5f9;
            --muted:     #94a3b8;
            --blob1:     rgba(99,102,241,0.20);
            --blob2:     rgba(139,92,246,0.18);
            --blob3:     rgba(59,130,246,0.10);
            --footer:    #475569;
            --toggle-bg: rgba(255,255,255,0.06);
            --toggle-border: rgba(255,255,255,0.10);
            --toggle-color: #94a3b8;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--bg-from) 0%, var(--bg-via) 50%, var(--bg-to) 100%);
            color: var(--text);
            transition: background .3s, color .3s;
            -webkit-font-smoothing: antialiased;
        }

        /* ── LAYOUT ── */
        .guest-wrap {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow: hidden;
        }

        /* ── BLOBS ── */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }
        .blob-1 {
            top: -10%; left: -10%;
            width: 40rem; height: 40rem;
            background: var(--blob1);
            animation: pulse 6s ease-in-out infinite;
        }
        .blob-2 {
            top: 20%; right: -10%;
            width: 35rem; height: 35rem;
            background: var(--blob2);
            opacity: .8;
        }
        .blob-3 {
            bottom: -10%; left: 20%;
            width: 45rem; height: 45rem;
            background: var(--blob3);
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: .8; }
            50%       { transform: scale(1.05); opacity: 1; }
        }

        /* ── THEME TOGGLE ── */
        .guest-toggle {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 100;
            width: 38px; height: 38px;
            border-radius: 10px;
            border: 1px solid var(--toggle-border);
            background: var(--toggle-bg);
            color: var(--toggle-color);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            backdrop-filter: blur(12px);
            transition: all .2s;
        }
        .guest-toggle:hover { transform: scale(1.08); }
        .guest-toggle svg { width: 16px; height: 16px; }
        .icon-sun  { display: none; }
        .icon-moon { display: block; }
        html.dark .icon-sun  { display: block; }
        html.dark .icon-moon { display: none; }

        /* ── LOGO ── */
        .guest-logo {
            z-index: 10;
            margin-bottom: 2rem;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            transition: transform .3s;
        }
        .guest-logo:hover { transform: translateY(-3px); }
        .logo-icon {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 22px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 16px 40px rgba(99,102,241,0.35);
            border: 1px solid rgba(255,255,255,0.15);
            transform: rotate(3deg);
            transition: transform .3s;
        }
        .guest-logo:hover .logo-icon { transform: rotate(0deg) scale(1.05); }
        .logo-icon span { color: #fff; font-size: 2rem; font-weight: 900; font-style: italic; }
        .logo-name {
            font-size: 1.5rem; font-weight: 900;
            letter-spacing: -.02em;
            color: var(--text);
            transition: color .3s;
        }
        .logo-name em {
            font-style: normal;
            background: linear-gradient(90deg, #6366f1, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* ── CARD ── */
        .guest-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 28px;
            padding: 2.5rem 2rem;
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            transition: background .3s, border-color .3s, box-shadow .3s;
        }

        /* Inner sheen — light mode only */
        html:not(.dark) .guest-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 28px;
            background: linear-gradient(145deg, rgba(255,255,255,0.6) 0%, transparent 60%);
            pointer-events: none;
        }

        /* ── FOOTER ── */
        .guest-footer {
            z-index: 10;
            margin-top: 2rem;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--footer);
            transition: color .3s;
        }

        /* ── FORM ELEMENT LIGHT MODE FIXES ── */
        html:not(.dark) input[type="text"],
        html:not(.dark) input[type="email"],
        html:not(.dark) input[type="password"] {
            background: #fff !important;
            border-color: #e2e8f0 !important;
            color: #0f172a !important;
        }
        html:not(.dark) input::placeholder { color: #94a3b8 !important; }
        html:not(.dark) label { color: #334155 !important; }
        html:not(.dark) .text-gray-400 { color: #64748b !important; }
        html:not(.dark) .text-gray-600 { color: #475569 !important; }
        html:not(.dark) a.text-gray-600 { color: #6366f1 !important; }
    </style>

    {{-- Prevent theme flash --}}
    <script>
        (function() {
            const t = localStorage.getItem('lms-theme') || 'light';
            if (t === 'dark') document.getElementById('guest-html').classList.add('dark');
        })();
    </script>
</head>

<body>

    {{-- Theme toggle --}}
    <button onclick="toggleTheme()" class="guest-toggle" title="Toggle theme">
        <svg class="icon-moon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
        <svg class="icon-sun" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
        </svg>
    </button>

    <div class="guest-wrap">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>

        {{-- Logo --}}
        <a href="/" class="guest-logo">
            <div class="logo-icon"><span>L</span></div>
            <div class="logo-name">LMS <em>PRO</em></div>
        </a>

        {{-- Card --}}
        <div class="guest-card">
            <div style="position:relative;z-index:1">
                {{ $slot }}
            </div>
        </div>

        {{-- Footer --}}
        <p class="guest-footer">&copy; {{ date('Y') }} LMS PRO — All Rights Reserved</p>
    </div>

    <script>
        function toggleTheme() {
            const html = document.getElementById('guest-html');
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('lms-theme', isDark ? 'dark' : 'light');
        }
    </script>
</body>
</html>