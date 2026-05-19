<x-guest-layout>
    <x-auth-session-status class="mb-6 text-center text-sm text-indigo-400" :status="session('status')" />

    {{-- Heading --}}
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-extrabold text-white tracking-tight">
            Welcome back 
        </h1>
        <p class="text-gray-400 text-sm mt-2 font-medium">
            Sign in to continue to LMS Pro
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-7">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-300 mb-2 ms-1">
                Email address
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                placeholder="name@example.com"
                class="w-full bg-white/10 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500
                       transition-all duration-300 hover:bg-white/15"
            />

            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-400" />
        </div>

        {{-- Password --}}
        <div>
            <div class="flex justify-between items-center mb-2 ms-1">
                <label for="password" class="block text-sm font-semibold text-gray-300">
                    Password
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition">
                        Forgot password?
                    </a>
                @endif
            </div>

            <input
                id="password"
                type="password"
                name="password"
                required
                placeholder="••••••••"
                class="w-full bg-white/10 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500
                       transition-all duration-300 hover:bg-white/15"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-400" />
        </div>

        {{-- Remember me --}}
        <div class="flex items-center justify-between px-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="rounded-md bg-white/10 border-white/10 text-indigo-600
                           focus:ring-0 focus:ring-offset-0 transition cursor-pointer"
                >
                <span class="ms-3 text-sm text-gray-400 group-hover:text-gray-300 transition">
                    Remember me
                </span>
            </label>
        </div>

        {{-- Submit --}}
        <div class="pt-3">
            <button
                type="submit"
                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600
                       hover:from-indigo-500 hover:to-purple-500
                       text-white font-extrabold py-4 rounded-2xl
                       shadow-xl shadow-indigo-500/30
                       transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.97]"
            >
                Sign In
            </button>
        </div>

        {{-- Register --}}
        @if (Route::has('register'))
            <div class="text-center pt-6 border-t border-white/10">
                <p class="text-sm text-gray-400">
                    Don’t have an account?
                    <a href="{{ route('register') }}"
                       class="ms-2 font-extrabold text-white hover:text-indigo-400
                              underline decoration-indigo-500/50 decoration-2 underline-offset-8 transition">
                        Create account
                    </a>
                </p>
            </div>
        @endif
    </form>
</x-guest-layout>
