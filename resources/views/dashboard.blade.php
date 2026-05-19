<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('General Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-[calc(100vh-64px)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-100 dark:border-gray-700">
                <div class="p-10">
                    <div class="flex items-center space-x-6">
                        <div class="h-20 w-20 rounded-3xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white text-3xl font-black shadow-lg shadow-indigo-500/30">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <h1 class="text-3xl font-black text-gray-900 dark:text-white">Hello, {{ Auth::user()->name }}!</h1>
                            <p class="text-lg text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-widest font-bold text-xs">
                                Role: <span class="text-indigo-600">{{ Auth::user()->role }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-10 border-t border-gray-100 dark:border-gray-700 pt-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="p-6 bg-slate-50 dark:bg-gray-900/50 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                                <h3 class="font-bold text-gray-900 dark:text-white mb-2 underline decoration-indigo-500 underline-offset-4">Quick Access</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Aapka account active hai. Aap apni profile manage kar sakte hain ya platform browse kar sakte hain.</p>
                            </div>

                            <div class="flex items-center justify-center">
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-8 py-4 bg-indigo-600 border border-transparent rounded-2xl font-black text-sm text-white uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-500/20 transition-all active:scale-95">
                                    Edit My Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
