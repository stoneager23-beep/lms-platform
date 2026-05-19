<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">
                    {{ __('Edit Lesson') }}
                </h2>
                <p class="text-slate-400 text-sm mt-1">
                    Modifying:
                    <span class="text-indigo-400 font-bold">
                        {{ $lesson->title }}
                    </span>
                </p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('instructor.lessons.quiz.edit', $lesson) }}"
                   class="px-4 py-2 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 font-bold rounded-lg transition text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Manage Quiz
                </a>

                <a href="{{ route('instructor.courses.lessons.index', $lesson->course) }}"
                   class="text-sm font-bold text-slate-400 hover:text-white transition flex items-center">
                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Curriculum
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        <div class="bg-[#1e293b] border border-slate-800 shadow-2xl rounded-[2.5rem] overflow-hidden">
            <div class="md:flex">

                {{-- LEFT INFO --}}
                <div class="md:w-1/3 bg-gradient-to-br from-purple-600 to-indigo-700 p-10 text-white">
                    <div class="mb-8">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>

                        <h3 class="text-2xl font-black tracking-tight mb-2">
                            Edit Mode
                        </h3>

                        <p class="text-indigo-100 text-sm leading-relaxed opacity-80">
                            Update lesson title, content, video aur attachments.
                            Changes save hote hi students ke liye apply ho jayenge
                            (agar lesson published hai).
                        </p>
                    </div>

                    {{-- STATUS --}}
                    <div class="p-4 bg-white/10 rounded-2xl border border-white/10 backdrop-blur-md">
                        <p class="text-[10px] font-black uppercase tracking-widest text-indigo-200 mb-1">
                            Status
                        </p>
                        <p class="text-sm font-bold flex items-center">
                            @if($lesson->is_published)
                                <span class="w-2 h-2 bg-emerald-400 rounded-full me-2"></span>
                                Published
                            @else
                                <span class="w-2 h-2 bg-amber-400 rounded-full me-2"></span>
                                Draft
                            @endif
                        </p>
                    </div>
                </div>

                {{-- RIGHT FORM --}}
                <div class="md:w-2/3 p-10 bg-slate-900/50">
                    <form method="POST"
                          action="{{ route('instructor.lessons.update', $lesson) }}"
                          enctype="multipart/form-data"
                          class="space-y-8">
                        @csrf
                        @method('PUT')

                        {{-- TITLE --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Lesson Title
                            </label>
                            <input type="text"
                                   name="title"
                                   value="{{ old('title', $lesson->title) }}"
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4
                                          text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50
                                          focus:border-indigo-500"
                                   required>
                        </div>

                        {{-- CONTENT --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Lesson Content
                            </label>
                            <textarea name="content"
                                      rows="6"
                                      class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4
                                             text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50
                                             focus:border-indigo-500"
                                      required>{{ old('content', $lesson->content) }}</textarea>
                        </div>

                        {{-- VIDEO URL --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Video URL (Optional)
                            </label>
                            <input type="url"
                                   name="video_url"
                                   value="{{ old('video_url', $lesson->video_url) }}"
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4
                                          text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                        </div>

                        {{-- VIDEO FILE --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Replace Video (Optional)
                            </label>
                            <input type="file"
                                   name="video"
                                   accept="video/mp4,video/webm"
                                   class="block w-full text-gray-300">

                            @if($lesson->video_path)
                                <p class="text-xs text-slate-400 mt-2">
                                    A video is already uploaded.
                                </p>
                            @endif
                        </div>

                        {{-- ATTACHMENT --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Replace Attachment (Optional)
                            </label>
                            <input type="file"
                                   name="attachment"
                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.zip"
                                   class="block w-full text-gray-300">

                            @if($lesson->attachment)
                                <p class="text-xs text-slate-400 mt-2">
                                    A file is already attached.
                                </p>
                            @endif
                        </div>

                        {{-- POSITION --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                    Position
                                </label>
                                <input type="number"
                                       name="position"
                                       value="{{ old('position', $lesson->position) }}"
                                       min="1"
                                       class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4
                                              text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50
                                              focus:border-indigo-500">
                            </div>

                            {{-- PUBLISH --}}
                            <div class="flex flex-col justify-end">
                                <input type="hidden" name="is_published" value="0">

                                <label class="inline-flex items-center cursor-pointer group p-4
                                              bg-white/5 border border-white/10 rounded-2xl
                                              hover:bg-white/10 transition-all">
                                    <input type="checkbox"
                                           name="is_published"
                                           value="1"
                                           {{ old('is_published', $lesson->is_published) ? 'checked' : '' }}
                                           class="rounded bg-slate-800 border-white/10 text-indigo-600
                                                  focus:ring-0 focus:ring-offset-0">
                                    <span class="ms-3 text-sm font-bold text-gray-300 group-hover:text-white transition">
                                        Published
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-col sm:flex-row gap-4 pt-6">
                            <button type="submit"
                                    class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600
                                           hover:from-indigo-500 hover:to-purple-500
                                           text-white font-black py-5 rounded-2xl
                                           shadow-xl shadow-indigo-500/20
                                           transition-all transform hover:-translate-y-1 active:scale-95">
                                Save Changes
                            </button>

                            <a href="{{ route('instructor.courses.lessons.index', $lesson->course) }}"
                               class="flex-1 text-center py-5 bg-slate-800 hover:bg-slate-700
                                      text-white font-bold rounded-2xl border border-slate-700 transition-all">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
