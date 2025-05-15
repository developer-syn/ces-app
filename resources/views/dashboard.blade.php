<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome,') }} {{ auth()->user()->name }}!
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Students Per Grade Level & Section -->
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-lg shadow-sm mb-6">
                <h3 class="text-lg font-semibold mb-4">Students Per Grade Level & Section</h3>

                @if (auth()->user()->role === 'admin')
                    <p class="text-sm text-gray-700 mb-2">Viewing all grade levels (Admin)</p>
                @else
                    <p class="text-sm text-gray-700 mb-2">Viewing only your assigned students (Teacher)</p>
                @endif

                @foreach ($studentsPerGrade as $gradeLevel => $sections)
                    <div class="mb-3">
                        <p class="text-lg font-bold text-gray-900">Grade {{ $gradeLevel }}:</p>

                        <div class="grid grid-cols-2 gap-4 ml-4">
                            @foreach ($sections as $section)
                                <div class="flex items-center bg-white p-3 rounded-lg shadow">
                                    <p class="text-sm font-medium text-gray-700">
                                        Section {{ $section->section }} -
                                        <span class="font-bold text-gray-900">{{ $section->student_count }}</span>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>


            @if (auth()->user()->role === 'admin')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Total Teachers -->
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @include('components.svg.multi-user-icon')
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Total Teachers</h3>
                                <p class="text-2xl font-bold">{{ $totalTeachers ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Class Records -->
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @include('components.svg.subject-icon')
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Total Class Records</h3>
                                <p class="text-2xl font-bold">{{ $totalClasses ?? 0 }}</p>

                                @if (auth()->user()->role === 'admin')
                                    <p class="text-sm text-gray-600">You are viewing all class records.</p>
                                @else
                                    <p class="text-sm text-gray-600">You are viewing only your assigned class records.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>


                    <!-- Total Subjects -->
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @include('components.svg.subject-icon')
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Total Subjects</h3>
                                <p class="text-2xl font-bold">{{ $totalSubjects ?? 0 }}</p>
                                <p class="text-sm text-gray-600">You are viewing all the subjects added.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Total Class Records -->
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @include('components.svg.subject-icon')
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Total Class Records</h3>
                                <p class="text-2xl font-bold">{{ $totalClasses ?? 0 }}</p>

                                @if (auth()->user()->role === 'admin')
                                    <p class="text-sm text-gray-600">You are viewing all class records.</p>
                                @else
                                    <p class="text-sm text-gray-600">You are viewing only your assigned class records.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>


                    <!-- Total Subjects -->
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @include('components.svg.subject-icon')
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Total Subjects</h3>
                                <p class="text-2xl font-bold">{{ $totalSubjects ?? 0 }}</p>
                                <p class="text-sm text-gray-600">You are viewing all the subjects added.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Activities Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
                    <ul class="list-disc pl-5 space-y-2">
                        @forelse ($recentActivities as $activity)
                            <li class="text-gray-700">
                                <strong>{{ $activity->user->name }}</strong> {{ $activity->action }} -
                                {{ $activity->description }}
                                <span class="text-gray-500 text-sm">{{ $activity->created_at->diffForHumans() }}</span>
                            </li>
                        @empty
                            <li class="text-gray-500">No recent activities to display.</li>
                        @endforelse
                    </ul>
                </div>
            </div>


            <!-- Quick Links Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    @if (auth()->user()->role == 'admin')
                        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 gap-4">
                            <a href="{{ route('admin.teachers.index') }}"
                                class="bg-green-500 text-white p-4 rounded-lg shadow hover:bg-green-600">
                                Manage Teachers
                            </a>
                            <a href="{{ route('admin.school-infos.index') }}"
                                class="bg-gray-500 text-white p-4 rounded-lg shadow hover:bg-gray-600">
                                Manage School Info
                            </a>
                            <a href="{{ route('teacher.students.index') }}"
                                class="bg-blue-500 text-white p-4 rounded-lg shadow hover:bg-blue-600">
                                Manage Students
                            </a>
                            <a href="{{ route('teacher.class-records.index') }}"
                                class="bg-yellow-500 text-white p-4 rounded-lg shadow hover:bg-yellow-600">
                                Manage Class Records
                            </a>
                            <a href="{{ route('teacher.summary_quarterly_grades.index') }}"
                                class="bg-purple-500 text-white p-4 rounded-lg shadow hover:bg-purple-600">
                                View Quarterly Grades
                            </a>
                            <a href="{{ route('teacher.reports.students-report') }}"
                                class="bg-red-500 text-white p-4 rounded-lg shadow hover:bg-red-600">
                                View Reports
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-4 gap-4">
                            <a href="{{ route('teacher.students.index') }}"
                                class="bg-blue-500 text-white p-4 rounded-lg shadow hover:bg-blue-600">
                                Manage Students
                            </a>
                            <a href="{{ route('teacher.class-records.index') }}"
                                class="bg-yellow-500 text-white p-4 rounded-lg shadow hover:bg-yellow-600">
                                Manage Class Records
                            </a>
                            <a href="{{ route('teacher.summary_quarterly_grades.index') }}"
                                class="bg-purple-500 text-white p-4 rounded-lg shadow hover:bg-purple-600">
                                View Quarterly Grades
                            </a>
                            <a href="{{ route('teacher.reports.students-report') }}"
                                class="bg-red-500 text-white p-4 rounded-lg shadow hover:bg-red-600">
                                View Reports
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
