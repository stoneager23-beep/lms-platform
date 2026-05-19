<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">
                    {{ __('Add New Lesson') }}
                </h2>
                <p class="text-slate-400 text-sm mt-1">
                    Course:
                    <span class="text-indigo-400 font-bold">
                        {{ $course->title }}
                    </span>
                </p>
            </div>

            <a href="{{ route('instructor.courses.lessons.index', $course) }}"
               class="text-sm font-bold text-slate-400 hover:text-white transition flex items-center">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Curriculum
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        <div class="bg-[#1e293b] border border-slate-800 shadow-2xl rounded-[2.5rem] overflow-hidden">
            <div class="md:flex">

                {{-- LEFT INFO --}}
                <div class="md:w-1/3 bg-gradient-to-br from-indigo-600 to-blue-700 p-10 text-white">
                    <div class="mb-8">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>

                        <h3 class="text-2xl font-black tracking-tight mb-2">
                            Lesson Content
                        </h3>

{{--                        <p class="text-indigo-100 text-sm leading-relaxed opacity-80">--}}
{{--                            Lesson ka title, description, video aur files yahan add karein.--}}
{{--                            Aap video upload bhi kar sakte hain ya sirf video URL bhi de sakte hain.--}}
{{--                        </p>--}}
                    </div>

                    <div class="p-4 bg-white/10 rounded-2xl border border-white/10">
                        <p class="text-[10px] font-black uppercase tracking-widest text-indigo-200 mb-1">
                            Tip
                        </p>
{{--                        <p class="text-xs italic text-indigo-50">--}}
{{--                            “Ek lesson = ek clear concept. Video + short notes best rehte hain.”--}}
{{--                        </p>--}}
                    </div>
                </div>

                {{-- RIGHT FORM --}}
                <div class="md:w-2/3 p-10 bg-slate-900/50">
                    <form method="POST"
                          action="{{ route('instructor.courses.lessons.store', $course) }}"
                          enctype="multipart/form-data"
                          class="space-y-8">
                        @csrf

                        {{-- TITLE --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Lesson Title
                            </label>
                            <input type="text"
                                   name="title"
                                   value="{{ old('title') }}"
                                   placeholder="e.g. Introduction to PHP"
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4
                                          text-white placeholder-gray-600 focus:outline-none
                                          focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500"
                                   required>
                            @error('title')
                            <p class="text-red-500 text-xs mt-2 ms-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- CONTENT --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Lesson Content / Description
                            </label>
                            <textarea name="content"
                                      rows="6"
                                      placeholder="Explain lesson topics here..."
                                      class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4
                                             text-white placeholder-gray-600 focus:outline-none
                                             focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500"
                                      required>{{ old('content') }}</textarea>
                            @error('content')
                            <p class="text-red-500 text-xs mt-2 ms-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- VIDEO URL --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Video URL (Optional)
                            </label>
                            <input type="url"
                                   name="video_url"
                                   value="{{ old('video_url') }}"
                                   placeholder="https://youtube.com/..."
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4
                                          text-white placeholder-gray-600 focus:outline-none
                                          focus:ring-2 focus:ring-indigo-500/50">
                        </div>

                        {{-- VIDEO FILE --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Upload Video (MP4 / WebM)
                            </label>
                            <input type="file"
                                   name="video"
                                   accept="video/mp4,video/webm"
                                   class="block w-full text-gray-300">
                        </div>

                        {{-- ATTACHMENT --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Attachment (PDF / DOCX / ZIP)
                            </label>
                            <input type="file"
                                   name="attachment"
                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.zip"
                                   class="block w-full text-gray-300">
                        </div>

                        {{-- POSITION --}}
                        <div class="w-full md:w-1/2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Display Position
                            </label>
                            <input type="number"
                                   name="position"
                                   value="{{ $course->lessons()->count() + 1 }}"
                                   min="1"
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4
                                          text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50
                                          focus:border-indigo-500">
                        </div>

                        {{-- SUBMIT --}}
                        <div class="pt-6">
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-indigo-600 to-blue-600
                                           hover:from-indigo-500 hover:to-blue-500
                                           text-white font-black py-5 rounded-2xl
                                           shadow-xl shadow-indigo-500/20
                                           transition-all transform hover:-translate-y-1 active:scale-[0.98]">
                                Save Lesson →
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
