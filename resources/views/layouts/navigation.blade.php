<nav x-data="{ open: false }" class="lms-nav">

    @php
        $user = auth()->user();
        $dashboardRoute = $user->isAdmin()
            ? 'admin.dashboard'
            : ($user->isInstructor() ? 'instructor.dashboard' : 'student.dashboard');
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            {{-- ── LEFT ── --}}
            <div class="flex items-center gap-10">

                {{-- LOGO --}}
                <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl
                                flex items-center justify-center shadow-lg shadow-indigo-500/20
                                group-hover:scale-105 transition-transform duration-200">
                        <span class="text-white font-black text-lg italic">L</span>
                    </div>
                    <span class="hidden md:block font-extrabold text-lg tracking-tight" style="color:var(--text)">
                        LMS <span style="color:var(--accent)">PRO</span>
                    </span>
                </a>

                {{-- DESKTOP LINKS --}}
                <div class="hidden sm:flex items-center gap-1">
                    @if($user->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('admin.instructors.index')" :active="request()->routeIs('admin.instructors.*')">
                            Instructors
                        </x-nav-link>
                    @elseif($user->isInstructor())
                        <x-nav-link :href="route('instructor.dashboard')" :active="request()->routeIs('instructor.dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('instructor.courses.index')" :active="request()->routeIs('instructor.courses.*')">
                            My Courses
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('student.courses.index')" :active="request()->routeIs('student.courses.*')">
                            Courses
                        </x-nav-link>
                        <x-nav-link :href="route('student.lessons.index')" :active="request()->routeIs('student.lessons.*')">
                            Lessons
                        </x-nav-link>
                    @endif
                </div>
            </div>

            {{-- ── RIGHT ── --}}
            <div class="hidden sm:flex items-center gap-3">

                {{-- THEME TOGGLE --}}
                <button onclick="toggleTheme()" class="theme-toggle" title="Toggle theme">
                    {{-- Moon (shown in light mode) --}}
                    <svg class="icon-moon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    {{-- Sun (shown in dark mode) --}}
                    <svg class="icon-sun" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                </button>

                {{-- ROLE BADGE --}}
                <span class="role-badge px-3 py-1 text-[10px] font-black uppercase tracking-widest
                             rounded-full border transition-colors"
                      style="background:var(--surf2);color:var(--muted);border-color:var(--border)">
                    {{ $user->role }}
                </span>

                {{-- USER DROPDOWN --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl transition-colors hover:bg-black/5 dark:hover:bg-white/5">
                            <span class="text-sm font-semibold" style="color:var(--text2)">{{ $user->name }}</span>
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 p-[2px]">
                                <div class="h-full w-full rounded-full flex items-center justify-center
                                            text-white font-black text-xs"
                                     style="background:var(--surf)">
                                    <span style="background:linear-gradient(135deg,#818cf8,#a78bfa);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); this.closest('form').submit();"
                                             class="text-red-500">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- MOBILE TOGGLE --}}
            <div class="flex items-center gap-2 sm:hidden">
                <button onclick="toggleTheme()" class="theme-toggle">
                    <svg class="icon-moon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg class="icon-sun" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                </button>
                <button @click="open = !open"
                        class="p-2 rounded-xl transition-colors"
                        style="color:var(--muted)">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div x-show="open" x-transition
         class="sm:hidden border-t" style="background:var(--surf);border-color:var(--border)">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route($dashboardRoute)">Dashboard</x-responsive-nav-link>
            @if($user->isAdmin())
                <x-responsive-nav-link :href="route('admin.instructors.index')">Instructors</x-responsive-nav-link>
            @elseif($user->isInstructor())
                <x-responsive-nav-link :href="route('instructor.courses.index')">My Courses</x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('student.courses.index')">Courses</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.lessons.index')">Lessons</x-responsive-nav-link>
            @endif
        </div>
        <div class="border-t px-4 py-4" style="border-color:var(--border);background:var(--surf2)">
            <div class="font-bold" style="color:var(--text)">{{ $user->name }}</div>
            <div class="text-sm" style="color:var(--muted)">{{ $user->email }}</div>
        </div>
    </div>
</nav>