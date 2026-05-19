<nav x-data="{ open: false }"
     class="bg-white/5 backdrop-blur-xl border-b border-white/10 sticky top-0 z-50">

    @php
        $user = auth()->user();
        $dashboardRoute = $user->isAdmin()
            ? 'admin.dashboard'
            : ($user->isInstructor()
                ? 'instructor.dashboard'
                : 'student.dashboard');
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20"> {{-- Increased height --}}

            {{-- LEFT --}}
            <div class="flex items-center">

                {{-- LOGO --}}
                <a href="{{ route($dashboardRoute) }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600
                                rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-300">
                        <span class="text-white font-black text-xl italic">L</span>
                    </div>
                    <span class="hidden md:block font-extrabold text-xl tracking-tight text-white">
                        LMS <span class="text-indigo-400">PRO</span>
                    </span>
                </a>

                {{-- DESKTOP LINKS --}}
                <div class="hidden sm:flex space-x-8 sm:ms-12">

                    {{-- ADMIN --}}
                    @if($user->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            Dashboard
                        </x-nav-link>

                        <x-nav-link :href="route('admin.instructors.index')" :active="request()->routeIs('admin.instructors.*')">
                            Instructors
                        </x-nav-link>

                        {{-- INSTRUCTOR --}}
                    @elseif($user->isInstructor())
                        <x-nav-link :href="route('instructor.dashboard')" :active="request()->routeIs('instructor.dashboard')">
                            Dashboard
                        </x-nav-link>

                        <x-nav-link :href="route('instructor.courses.index')" :active="request()->routeIs('instructor.courses.*')">
                            My Courses
                        </x-nav-link>

                        {{-- STUDENT --}}
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

            {{-- RIGHT --}}
            <div class="hidden sm:flex sm:items-center sm:space-x-6">

                {{-- ROLE BADGE --}}
                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest
                             rounded-full bg-white/5 text-slate-400 border border-white/10">
                    {{ $user->role }}
                </span>

                {{-- USER DROPDOWN --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center px-3 py-2 text-sm font-bold text-slate-300 hover:text-white transition rounded-xl group gap-2">
                             {{ $user->name }}
                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 p-[2px]">
                                <div class="h-full w-full rounded-full bg-[#0f172a] flex items-center justify-center text-indigo-400 font-black text-xs group-hover:bg-transparent group-hover:text-white transition-colors">
                                     {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-slate-300 hover:text-white hover:bg-white/5">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); this.closest('form').submit();"
                                             class="text-red-400 hover:text-red-300 hover:bg-white/5">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- MOBILE TOGGLE --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = !open"
                        class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/10 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
         class="sm:hidden bg-[#0f172a]/95 backdrop-blur-xl border-t border-white/10">

        <div class="pt-2 pb-3 space-y-1 px-4">

            <x-responsive-nav-link :href="route($dashboardRoute)">
                Dashboard
            </x-responsive-nav-link>

            @if($user->isAdmin())
                <x-responsive-nav-link :href="route('admin.instructors.index')">
                    Instructors
                </x-responsive-nav-link>

            @elseif($user->isInstructor())
                <x-responsive-nav-link :href="route('instructor.courses.index')">
                    My Courses
                </x-responsive-nav-link>

            @else
                <x-responsive-nav-link :href="route('student.courses.index')">
                    Courses
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('student.lessons.index')">
                    Lessons
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="border-t border-white/10 px-4 py-4 bg-white/5">
            <div class="font-bold text-white">
                {{ $user->name }}
            </div>
            <div class="text-sm text-slate-400">
                {{ $user->email }}
            </div>
        </div>
    </div>
</nav>
