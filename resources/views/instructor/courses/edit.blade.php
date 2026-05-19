<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">
                    Edit Course
                </h2>
                <p class="text-slate-400 text-sm mt-1">
                    Updating:
                    <span class="text-indigo-400 font-bold">
                        {{ $course->title }}
                    </span>
                </p>
            </div>
            <a href="{{ route('instructor.courses.index') }}"
               class="text-sm font-bold text-slate-400 hover:text-white transition flex items-center">
                ← Back to List
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-[#1e293b] border border-slate-800 shadow-2xl rounded-[2.5rem] overflow-hidden">
            <div class="md:flex">

                {{-- LEFT PANEL --}}
                <div class="md:w-1/3 bg-gradient-to-br from-purple-600 to-indigo-700 p-10 text-white">
                    <h3 class="text-2xl font-black mb-4 tracking-tight">
                        Update Content
                    </h3>

                    {{-- STATUS --}}
                    <div class="mt-10 p-4 bg-white/10 rounded-2xl border border-white/10">
                        <p class="text-[10px] font-black uppercase tracking-widest text-indigo-200">
                            Current Status
                        </p>
                        <p class="text-lg font-bold capitalize">
                            {{ $course->status }}
                        </p>
                    </div>

                    {{-- CURRENT THUMBNAIL --}}
                    @if($course->thumbnail)
                        <div class="mt-8">
                            <p class="text-[10px] font-black uppercase tracking-widest text-indigo-200 mb-2">
                                Current Thumbnail
                            </p>

                            <div class="w-full h-32 overflow-hidden rounded-xl border border-white/20">
                                <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                     class="w-full h-full object-cover">
                            </div>
                        </div>
                    @endif
                </div>

                {{-- RIGHT PANEL --}}
                <div class="md:w-2/3 p-10 bg-slate-900/50">
                    <form method="POST"
                          action="{{ route('instructor.courses.update', $course) }}"
                          enctype="multipart/form-data"
                          class="space-y-8">
                        @csrf
                        @method('PUT')

                        {{-- TITLE --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Course Title
                            </label>
                            <input type="text"
                                   name="title"
                                   value="{{ old('title', $course->title) }}"
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                                   required>
                            @error('title')
                            <p class="text-red-500 text-xs mt-2 ms-1 font-bold">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- CHANGE THUMBNAIL --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Change Thumbnail (Optional)
                            </label>

                            <input type="file"
                                   name="thumbnail"
                                   accept="image/*"
                                   onchange="previewImage(event)"
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">

                            @error('thumbnail')
                            <p class="text-red-500 text-xs mt-2 ms-1 font-bold">
                                {{ $message }}
                            </p>
                            @enderror

                            {{-- Preview New Thumbnail --}}
                            <div class="mt-4">
                                <img id="thumbnail-preview"
                                     class="hidden w-full h-32 object-cover rounded-xl border border-white/20">
                            </div>

                            {{-- REMOVE OPTION --}}
                            @if($course->thumbnail)
                                <div class="mt-4 flex items-center gap-2">
                                    <input type="checkbox"
                                           name="remove_thumbnail"
                                           value="1"
                                           class="rounded bg-slate-800 border-slate-600 text-red-500 focus:ring-red-500">
                                    <label class="text-xs text-red-300 font-semibold">
                                        Remove current thumbnail
                                    </label>
                                </div>
                            @endif
                        </div>

                        {{-- DESCRIPTION --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Description
                            </label>
                            <textarea name="description"
                                      rows="5"
                                      class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                                      required>{{ old('description', $course->description) }}</textarea>
                            @error('description')
                            <p class="text-red-500 text-xs mt-2 ms-1 font-bold">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- STATUS --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3 ms-1">
                                Course Status
                            </label>

                            <select name="status"
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                                <option value="draft" {{ $course->status === 'draft' ? 'selected' : '' }} class="bg-slate-900">
                                    Draft (Private)
                                </option>
                                <option value="published" {{ $course->status === 'published' ? 'selected' : '' }} class="bg-slate-900">
                                    Published (Live)
                                </option>
                            </select>
                        </div>

                        {{-- BUTTONS --}}
                        <div class="flex flex-col sm:flex-row gap-4 pt-6">
                            <button type="submit"
                                    class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1 active:scale-95">
                                Update Changes
                            </button>

                            <a href="{{ route('instructor.courses.index') }}"
                               class="flex-1 text-center py-4 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-2xl border border-slate-700 transition-all">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Preview Script --}}
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('thumbnail-preview');
                output.src = reader.result;
                output.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</x-app-layout>
