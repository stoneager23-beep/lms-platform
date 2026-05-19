<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">
                    Course Catalog
                </h2>
                <p class="text-slate-400 text-sm mt-1">
                    Explore and enroll in new courses
                </p>
            </div>
            <a href="{{ route('student.courses.index') }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-semibold transition">
                Go to My Learning &rarr;
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-10">
        
        <div class="mb-10 text-center max-w-2xl mx-auto">
            <h2 class="text-4xl font-black text-white tracking-tight mb-4">
                Explore Our <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Courses</span>
            </h2>
            <p class="text-slate-400 text-lg">
                Discover new skills and advance your career with our expert-led courses.
            </p>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse($courses as $course)
                <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl overflow-hidden flex flex-col h-full hover:shadow-2xl hover:shadow-indigo-500/20 transition-all duration-300 group transform hover:-translate-y-1">

                    {{-- Thumbnail --}}
                    <div class="h-52 w-full relative overflow-hidden">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 group-hover:scale-110 transition-transform duration-700"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif

                         {{-- Overlay Gradient --}}
                         <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-transparent to-transparent opacity-90"></div>
                         
                         <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 bg-black/50 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider rounded-full border border-white/10 shadow-lg">
                                New
                            </span>
                        </div>
                    </div>

                    <div class="p-7 flex-1 flex flex-col relative z-10">
                        <div class="flex-1 space-y-4">
                            {{-- Instructor --}}
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 p-[1px]">
                                    <div class="w-full h-full rounded-full bg-[#0f172a] flex items-center justify-center text-xs text-white font-bold uppercase">
                                         {{ substr($course->instructor->name ?? '?', 0, 1) }}
                                    </div>
                                </div>
                                <span class="text-xs text-slate-300 font-bold uppercase tracking-wider">
                                    {{ $course->instructor->name ?? 'Unknown Instructor' }}
                                </span>
                            </div>

                            <h3 class="text-2xl font-bold text-white leading-tight group-hover:text-indigo-400 transition-colors">
                                {{ $course->title }}
                            </h3>

                            <p class="text-slate-400 text-sm leading-relaxed line-clamp-3">
                                {{ $course->description }}
                            </p>
                        </div>

                        <div class="mt-8 pt-6 border-t border-white/5">
                            <a href="{{ route('courses.show', $course) }}"
                               class="block w-full py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:scale-105 text-white text-center text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-300">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white/5 border border-dashed border-white/10 rounded-2xl p-16 text-center backdrop-blur-lg">
                        <h3 class="text-2xl font-bold text-white mb-2">
                            No Courses Available
                        </h3>
                        <p class="text-slate-400">
                            Check back later for new courses.
                        </p>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
