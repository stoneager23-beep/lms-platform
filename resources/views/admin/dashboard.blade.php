<x-app-layout>
    <div class="py-12 bg-gray-50 dark:bg-gray-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-black text-gray-900 dark:text-white">Admin Overview</h1>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.instructors.active') }}" class="bg-purple-600 text-white px-6 py-2 rounded-xl font-bold shadow-lg hover:bg-purple-500 transition">
                        Active Instructors
                    </a>
                    <a href="{{ route('admin.instructors.index') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold shadow-lg">
                        Refresh Stats
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Students</p>
                    <h3 class="text-3xl font-black text-blue-600 mt-2">{{ $stats['totalStudents'] }}</h3>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Instructors</p>
                    <h3 class="text-3xl font-black text-purple-600 mt-2">{{ $stats['totalInstructors'] }}</h3>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Active Courses</p>
                    <h3 class="text-3xl font-black text-emerald-600 mt-2">{{ $stats['activeCourses'] }}</h3>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border-2 border-red-100 dark:border-red-900/30">
                    <p class="text-xs font-bold text-red-400 uppercase tracking-widest font-black">Pending Approval</p>
                    <h3 class="text-3xl font-black text-red-600 mt-2">{{ $stats['pendingCount'] }}</h3>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 overflow-hidden">
                <h3 class="text-xl font-bold mb-4 dark:text-white">Recent Instructor Requests</h3>
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($pendingInstructors->take(5) as $instructor)
                        <tr>
                            <td class="px-4 py-4 text-sm dark:text-gray-300">{{ $instructor->name }}</td>
                            <td class="px-4 py-4 text-sm dark:text-gray-300">{{ $instructor->email }}</td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('admin.instructors.index') }}" class="text-indigo-600 font-bold text-sm">Review</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-400">No pending requests</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
