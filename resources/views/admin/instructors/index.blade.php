<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">Instructor Management</h2>
                <p class="text-sm text-slate-400 mt-1">Review pending applications and manage active instructors</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="text-sm text-slate-400 hover:text-slate-300 transition font-semibold">
                ← Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="px-5 py-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium backdrop-blur-sm flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="px-5 py-4 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 text-sm font-medium backdrop-blur-sm flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('info') }}
                </div>
            @endif
            @if(session('error'))
                <div class="px-5 py-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-medium backdrop-blur-sm flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- ═══════════════════════════════
                 SECTION 1 — PENDING APPROVALS
            ════════════════════════════════ --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    @if($pendingInstructors->count() > 0)
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-400"></span>
                        </span>
                    @endif
                    <h3 class="text-lg font-bold text-white">Pending Approvals</h3>
                    @if($pendingInstructors->count() > 0)
                        <span class="bg-amber-500/20 text-amber-400 border border-amber-500/30 text-xs font-black px-2 py-0.5 rounded-full">
                            {{ $pendingInstructors->count() }}
                        </span>
                    @endif
                </div>

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
                                    <tr class="hover:bg-white/5 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-orange-600
                                                            flex items-center justify-center text-white font-bold text-sm
                                                            shadow-lg shadow-amber-500/20 flex-shrink-0">
                                                    {{ strtoupper(substr($instructor->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-slate-200">{{ $instructor->name }}</div>
                                                    <div class="text-xs text-amber-400/70 font-medium">Pending Review</div>
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
                                                <form method="POST" action="{{ route('admin.instructors.approve', $instructor) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold
                                                                   bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                                                   hover:bg-emerald-500/20 hover:text-emerald-300
                                                                   transition-all duration-200 cursor-pointer">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Approve
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.instructors.reject', $instructor) }}"
                                                      onsubmit="return confirm('Reject and remove this application?')">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold
                                                                   bg-red-500/10 text-red-400 border border-red-500/20
                                                                   hover:bg-red-500/20 hover:text-red-300
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
                                        <td colspan="4" class="px-6 py-14 text-center">
                                            <div class="flex flex-col items-center gap-3">
                                                <div class="w-14 h-14 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-2xl">✅</div>
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

            {{-- ═══════════════════════════════
                 SECTION 2 — APPROVED INSTRUCTORS
            ════════════════════════════════ --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <h3 class="text-lg font-bold text-white">Approved Instructors</h3>
                    <span class="bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 text-xs font-black px-2 py-0.5 rounded-full">
                        {{ $approvedInstructors->count() }}
                    </span>
                </div>

                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/10">
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Instructor</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Email</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Courses</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Joined</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($approvedInstructors as $instructor)
                                    <tr class="hover:bg-white/5 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600
                                                            flex items-center justify-center text-white font-bold text-sm
                                                            shadow-lg shadow-indigo-500/20 flex-shrink-0">
                                                    {{ strtoupper(substr($instructor->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-slate-200">{{ $instructor->name }}</div>
                                                    <div class="text-xs text-emerald-400/70 font-medium">Active</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-400">{{ $instructor->email }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center justify-center min-w-[28px] px-2 py-0.5 rounded-full text-xs font-bold
                                                {{ $instructor->courses_count > 0 ? 'bg-indigo-500/20 text-indigo-300' : 'bg-white/5 text-slate-500' }}">
                                                {{ $instructor->courses_count }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-slate-400">{{ $instructor->created_at->format('M d, Y') }}</div>
                                            <div class="text-xs text-slate-500">{{ $instructor->created_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button type="button"
                                                    onclick="openTerminateModal({{ $instructor->id }}, '{{ addslashes($instructor->name) }}', {{ $instructor->courses_count }})"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold
                                                           bg-red-500/10 text-red-400 border border-red-500/20
                                                           hover:bg-red-500/20 hover:text-red-300 hover:border-red-500/30
                                                           transition-all duration-200 cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                                Remove Access
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-14 text-center">
                                            <div class="text-slate-500 text-sm">No approved instructors yet</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Terminate Modal --}}
    <div id="terminateModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeTerminateModal()"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="relative bg-[#1e293b] border border-white/10 rounded-2xl shadow-2xl w-full max-w-md p-6 space-y-5">
                <div class="mx-auto w-14 h-14 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-bold text-white">Remove Instructor Access</h3>
                    <p class="text-sm text-slate-400 mt-2">
                        You are about to terminate <span id="modalInstructorName" class="text-red-400 font-semibold"></span>.
                    </p>
                </div>
                <div class="bg-red-500/5 border border-red-500/15 rounded-xl p-4 space-y-2">
                    <p class="font-semibold text-red-400 text-sm">⚠ This action will:</p>
                    <ul class="list-disc list-inside space-y-1 text-slate-400 text-xs">
                        <li>Soft-delete the instructor's account</li>
                        <li>Unpublish all their courses (<span id="modalCourseCount" class="text-white font-semibold"></span> courses affected)</li>
                        <li>Permanently remove all student enrollments in those courses</li>
                    </ul>
                    <p class="text-red-400 font-semibold text-xs mt-2">This action cannot be undone.</p>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" onclick="closeTerminateModal()"
                            class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold
                                   bg-white/5 text-slate-300 border border-white/10
                                   hover:bg-white/10 transition-all duration-200 cursor-pointer">
                        Cancel
                    </button>
                    <form id="terminateForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-4 py-2.5 rounded-xl text-sm font-bold
                                       bg-red-600 text-white shadow-lg shadow-red-600/20
                                       hover:bg-red-500 transition-all duration-200 cursor-pointer">
                            Confirm Remove Access
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openTerminateModal(instructorId, instructorName, courseCount) {
            document.getElementById('modalInstructorName').textContent = instructorName;
            document.getElementById('modalCourseCount').textContent = courseCount;
            document.getElementById('terminateForm').action = '/admin/instructors/' + instructorId + '/terminate';
            document.getElementById('terminateModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeTerminateModal() {
            document.getElementById('terminateModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeTerminateModal(); });
    </script>

</x-app-layout>