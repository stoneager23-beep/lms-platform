<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">Pending Instructor Approvals</h2>
                <p class="text-sm text-slate-400 mt-1">Review and approve new instructor applications</p>
            </div>
            <a href="{{ route('admin.instructors.active') }}"
               class="inline-flex items-center gap-2 text-sm text-purple-400 hover:text-purple-300 transition font-semibold">
                Active Instructors →
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 px-5 py-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium backdrop-blur-sm flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="mb-6 px-5 py-4 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 text-sm font-medium backdrop-blur-sm flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('info') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 px-5 py-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-medium backdrop-blur-sm flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Pending Count Badge --}}
            @if($pendingInstructors->count() > 0)
                <div class="mb-6 flex items-center gap-3">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-400"></span>
                    </span>
                    <span class="text-sm font-semibold text-slate-300">
                        {{ $pendingInstructors->count() }} {{ Str::plural('application', $pendingInstructors->count()) }} awaiting review
                    </span>
                </div>
            @endif

            {{-- Instructors Table --}}
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/10">
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Applicant</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Email</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Applied On</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($pendingInstructors as $instructor)
                                <tr class="hover:bg-white/5 transition-colors duration-200 group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-amber-500/20">
                                                {{ strtoupper(substr($instructor->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <span class="text-sm font-semibold text-slate-200 block">{{ $instructor->name }}</span>
                                                <span class="text-xs text-amber-400/70 font-medium">Pending Review</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-400">{{ $instructor->email }}</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-400">{{ $instructor->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-slate-500">{{ $instructor->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Approve Button --}}
                                            <form method="POST" action="{{ route('admin.instructors.approve', $instructor) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold
                                                               bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                                               hover:bg-emerald-500/20 hover:text-emerald-300 hover:border-emerald-500/30
                                                               transition-all duration-200 cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Approve
                                                </button>
                                            </form>

                                            {{-- Reject Button --}}
                                            <form method="POST" action="{{ route('admin.instructors.reject', $instructor) }}"
                                                  onsubmit="return confirm('Are you sure you want to reject this application? This will remove the account.')">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold
                                                               bg-red-500/10 text-red-400 border border-red-500/20
                                                               hover:bg-red-500/20 hover:text-red-300 hover:border-red-500/30
                                                               transition-all duration-200 cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-16 h-16 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div class="text-slate-400 text-sm font-medium">No pending applications</div>
                                            <div class="text-slate-500 text-xs">All instructor requests have been reviewed 🎉</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
