<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-white leading-tight">
                {{ __('Create New Course') }}
            </h2>
            <a href="{{ route('instructor.courses.index') }}"
               class="text-sm font-bold text-indigo-400 hover:text-indigo-300 transition">
                &larr; Back to My Courses
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-[#1e293b] border border-slate-800 shadow-2xl rounded-[2.5rem] overflow-hidden">
            <div class="md:flex">

                {{-- LEFT PANEL --}}
                <div class="md:w-1/3 bg-gradient-to-br from-indigo-600 to-purple-700 p-10 text-white">
                    <h3 class="text-2xl font-black mb-4 tracking-tight">Course Details</h3>
                    <p class="text-indigo-100 text-sm leading-relaxed opacity-80">
                        Apne course ka catchy title, thumbnail aur description add karein taake students attract hon.
                    </p>

                    <div class="mt-10 space-y-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">1</div>
                            <span class="font-bold text-sm">Basic Info</span>
                        </div>
                        <div class="flex items-center space-x-3 opacity-50">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">2</div>
                            <span class="font-bold text-sm">Add Lessons</span>
                        </div>
                    </div>
                </div>

                {{-- RIGHT PANEL --}}
                <div class="md:w-2/3 p-10 bg-slate-900/50">
                    <form method="POST"
                          action="{{ route('instructor.courses.store') }}"
                          enctype="multipart/form-data"
                          class="space-y-8">
                        @csrf

                        {{-- TITLE --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Course Title
                            </label>
                            <input type="text"
                                   name="title"
                                   value="{{ old('title') }}"
                                   placeholder="e.g. Complete Laravel Mastery"
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                                   required>
                            @error('title')
                            <p class="text-red-500 text-xs mt-2 ms-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- THUMBNAIL --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Course Thumbnail
                            </label>

                            <input type="file"
                                   name="thumbnail"
                                   accept="image/*"
                                   onchange="previewImage(event)"
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                                   required>

                            @error('thumbnail')
                            <p class="text-red-500 text-xs mt-2 ms-1 font-bold">{{ $message }}</p>
                            @enderror

                            {{-- Preview --}}
                            <div class="mt-4">
                                <img id="thumbnail-preview"
                                     class="hidden w-full h-48 object-cover rounded-2xl border border-white/10">
                            </div>
                        </div>

                        {{-- DESCRIPTION --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Description
                            </label>
                            <textarea name="description"
                                      rows="5"
                                      placeholder="Explain what students will learn..."
                                      class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                            <p class="text-red-500 text-xs mt-2 ms-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- STATUS --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Initial Status
                            </label>
                            <select name="status"
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                                <option value="draft" class="bg-slate-900">Draft (Private)</option>
                                <option value="published" class="bg-slate-900">Published (Live)</option>
                            </select>
                        </div>

                        {{-- BUTTON --}}
                        <div class="pt-6">
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-black py-5 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98]">
                                Create & Continue →
                            </button>

                            <p class="text-center text-gray-500 text-xs mt-4">
                                You can add lessons and content in the next step.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Image Preview Script --}}
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('thumbnail-preview');
                output.src = reader.result;
                output.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</x-app-layout>
