<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-white leading-tight">
                My Lessons
            </h2>
            <p class="text-slate-400 text-sm mt-1">
                Lessons from courses you are enrolled in
            </p>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-6">

        @forelse($courses as $course)

            <div class="bg-[#1e293b] border border-slate-800 rounded-2xl overflow-hidden mb-6
                hover:shadow-xl hover:shadow-indigo-500/10 transition-all">

                {{-- THUMBNAIL --}}
                @if($course->thumbnail)
                    <div class="w-full h-48 overflow-hidden">
                        <img src="{{ asset('storage/' . $course->thumbnail) }}"
                             class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                    {{-- LEFT --}}
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2">
                            {{ $course->title }}
                        </h3>

                        @if($course->instructor)
                            <p class="text-xs text-slate-500 mb-2">
                                Instructor:
                                <span class="text-indigo-300 font-semibold">
                            {{ $course->instructor->name }}
                        </span>
                            </p>
                        @endif

                        <p class="text-slate-400 text-sm mb-4">
                            {{ Str::limit($course->description, 150) }}
                        </p>

                        @php $progress = $course->progress(auth()->user()); @endphp

                        <div class="max-w-sm">
                            <div class="flex justify-between text-xs font-bold text-indigo-300 mb-1">
                                <span>Progress</span>
                                <span>{{ $progress }}%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full transition-all duration-700"
                                     style="width: {{ $progress }}%">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT --}}
                    <div>
                        <a href="{{ route('student.lessons.index') }}"
                           class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500
                          text-white text-sm font-bold rounded-xl transition">
                            View Lessons →
                        </a>
                    </div>

                </div>
            </div>

        @empty


        {{-- EMPTY STATE --}}
            <div class="bg-[#1e293b] border border-dashed border-slate-700
                        rounded-2xl p-12 text-center">

                <h3 class="text-xl font-bold text-white mb-2">
                    No Lessons Available
                </h3>

                <p class="text-slate-400 mb-6">
                    You don’t have any published lessons yet.
                </p>

                <a href="{{ route('student.courses.index') }}"
                   class="inline-block px-6 py-3 bg-indigo-600 hover:bg-indigo-500
                          text-white font-bold rounded-xl transition">
                    View My Courses
                </a>
            </div>

        @endforelse

    </div>
</x-app-layout>
