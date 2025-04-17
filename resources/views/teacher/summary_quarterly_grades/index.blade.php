<x-app-layout>
    <x-slot name="title">Summary Quarterly Grades</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Summary Quarterly Grades') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Card Header -->
                <div class="overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <form method="GET" action="{{ route('teacher.summary_quarterly_grades.index') }}"
                            class="space-y-4 md:space-y-0 md:grid md:grid-cols-5 md:gap-6">

                            @if (auth()->user()->role === 'admin')
                                <!-- Year Level Filter -->
                                <div class="flex flex-col">
                                    <label for="year_level_id" class="block text-sm font-medium text-gray-700 mb-1">Year
                                        Level</label>
                                    <select name="year_level_id" id="year_level_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <option value="">All Year Levels</option>
                                        @foreach ($yearLevels as $yearLevel)
                                            <option value="{{ $yearLevel->id }}"
                                                {{ request('year_level_id') == $yearLevel->id ? 'selected' : '' }}>
                                                {{ $yearLevel->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Teacher Filter -->
                                <div class="flex flex-col">
                                    <label for="user_id"
                                        class="block text-sm font-medium text-gray-700 mb-1">Teacher</label>
                                    <select name="user_id" id="user_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <option value="">All Teachers</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}"
                                                {{ request('user_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }} ({{ $teacher->section }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <!-- School Year Filter -->
                            <div class="flex flex-col">
                                <label for="school_year_id" class="block text-sm font-medium text-gray-700 mb-1">School
                                    Year</label>
                                <select name="school_year_id" id="school_year_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">All School Years</option>
                                    @foreach ($schoolYears as $schoolYear)
                                        <option value="{{ $schoolYear->id }}"
                                            {{ request('school_year_id') == $schoolYear->id ? 'selected' : '' }}>
                                            {{ $schoolYear->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Quarter Filter (Visible to Both Admin and Teacher) -->
                            <div class="flex flex-col">
                                <label for="quarter_id"
                                    class="block text-sm font-medium text-gray-700 mb-1">Quarter</label>
                                <select name="quarter_id" id="quarter_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">All Quarters</option>
                                    @foreach ($quarters as $quarter)
                                        <option value="{{ $quarter->id }}"
                                            {{ request('quarter_id') == $quarter->id ? 'selected' : '' }}>
                                            {{ $quarter->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="md:col-span-1 flex flex-col justify-end mt-4">
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                    Apply Filters
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Table Container with horizontal scroll -->
                <div class="overflow-x-auto relative" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 500)">
                    <!-- Loading Overlay -->
                    <div x-show="loading"
                        class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                    </div>

                    <!-- Main Table -->
                    <table class="min-w-full divide-y divide-gray-200">
                        <!-- Table Header -->
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider sticky left-0 bg-gray-50 shadow-sm z-10">
                                    Student Name
                                </th>
                                @php
                                    $subjectsList = [
                                        'Mother Tongue',
                                        'Filipino',
                                        'English',
                                        'Mathematics',
                                        'Science',
                                        'Araling Panlipunan',
                                        'EEP / TLE',
                                        'MAPEH',
                                        'Music',
                                        'Arts',
                                        'Physical Education',
                                        'Health',
                                        'Eduk. sa Pagpapakatao',
                                        '*Arabic Language',
                                        '*Islamic Values Education',
                                    ];
                                @endphp
                                @foreach ($subjectsList as $subject)
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        {{ $subject }}
                                    </th>
                                @endforeach
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-blue-50">
                                    Average
                                </th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($students as $student)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white z-10">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ ucwords(strtolower($student->lastname)) }},
                                            {{ ucwords(strtolower($student->firstname)) }},
                                            {{ ucwords(strtolower($student->middlename)) }}
                                        </div>
                                    </td>

                                    @php
                                        // Fetch grades and store them for reference
                                        $grades = collect($student->classRecords)->mapWithKeys(function ($record) {
                                            return [$record->subject->name => $record->quarterly_grade];
                                        });

                                        // Subjects related to MAPEH
                                        $mapehSubjects = ['Music', 'Art', 'Physical Education', 'Health'];

                                        // Compute MAPEH average
                                        $mapehGrades = collect($mapehSubjects)
                                            ->map(fn($subj) => $grades->get($subj))
                                            ->filter(fn($grade) => !is_null($grade));

                                        $mapehAverage = $mapehGrades->isNotEmpty() ? $mapehGrades->avg() : null;
                                    @endphp

                                    @foreach ($subjectsList as $subject)
                                        @php
                                            $grade = $grades->get($subject, null);
                                        @endphp

                                        @if ($subject === 'MAPEH')
                                            <td class="px-6 py-4 whitespace-nowrap text-center font-bold bg-gray-100">
                                                <div class="text-sm text-gray-900">
                                                    @if ($mapehAverage !== null)
                                                        <span
                                                            class="@if ($mapehAverage < 75) text-red-600 @elseif($mapehAverage >= 90) text-green-600 @endif">
                                                            {{ number_format($mapehAverage) }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-900">-</span>
                                                    @endif
                                                </div>
                                            </td>
                                        @else
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="text-sm text-gray-900">
                                                    @if ($grade !== null)
                                                        <span
                                                            class="@if ($grade < 75) text-red-600 @elseif($grade >= 90) text-green-600 @endif">
                                                            {{ number_format($grade) }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-900">-</span>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    @endforeach

                                    <!-- Calculate Overall Average -->
                                    <td class="px-6 py-4 whitespace-nowrap bg-blue-50">
                                        <div class="text-sm font-semibold text-gray-900 text-center">
                                            @php
                                                // Get non-MAPEH grades
                                                $filteredGrades = $student->classRecords
                                                    ->filter(
                                                        fn($record) => !in_array(
                                                            $record->subject->name,
                                                            $mapehSubjects,
                                                        ),
                                                    )
                                                    ->pluck('quarterly_grade');

                                                // Merge MAPEH average into overall grades
                                                $allGrades = $filteredGrades->merge(
                                                    $mapehAverage !== null ? [$mapehAverage] : [],
                                                );

                                                // Compute final student average
                                                $average = $allGrades->isNotEmpty() ? $allGrades->avg() : null;
                                            @endphp

                                            @if ($average !== null)
                                                {{ number_format($average) }}
                                            @else
                                                <span class="text-gray-900">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Legend -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center space-x-6 text-sm">
                        <span class="flex items-center">
                            <span class="w-3 h-3 inline-block bg-red-600 rounded-full mr-2"></span>
                            <span class="text-gray-600">Below 75 - Failed</span>
                        </span>
                        <span class="flex items-center">
                            <span class="w-3 h-3 inline-block bg-green-600 rounded-full mr-2"></span>
                            <span class="text-gray-600">90 and above - Excellent</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
