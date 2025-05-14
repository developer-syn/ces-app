<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Students') }}
        </h2>
    </x-slot>
    <div class="py-4">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Top Bar with Actions and Filters -->
                <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <!-- Left side - Add Button and Bulk Actions -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('teacher.students.create') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Register student
                        </a>
                        <!-- Bulk Actions -->
                        <div class="flex gap-2">
                            <div class="hidden">
                                <button id="exportCsvButton"
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Export CSV
                                </button>
                            </div>
                            <button id="deleteSelectedButton"
                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete Selected
                            </button>
                        </div>
                    </div>

                    <!-- Right side - Search and Filters -->
                    <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                        <!-- Search -->
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search students..."
                                class="pl-10 pr-4 py-2 border rounded-lg w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <!-- School Year Filter -->
                        <select id="schoolYearFilter" name="school_year_id"
                            class="border rounded-lg px-4 py-2 w-full sm:w-48 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All School Years</option>
                            @foreach ($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->id }}"
                                    {{ request('school_year_id') == $schoolYear->id ? 'selected' : '' }}>
                                    {{ $schoolYear->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Year Level Filter -->
                        <select id="yearLevelFilter" name="year_level_id"
                            class="border rounded-lg px-4 py-2 w-full sm:w-48 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Year Levels</option>
                            @foreach ($yearLevels as $yearLevel)
                                <option value="{{ $yearLevel->id }}"
                                    {{ request('year_level_id') == $yearLevel->id ? 'selected' : '' }}>
                                    {{ $yearLevel->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Teacher Filter -->
                        <select id="teacherFilter" name="user_id"
                            class="border rounded-lg px-4 py-2 w-full sm:w-48 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            @if (auth()->user()->role === 'teacher') disabled @endif>
                            @if (auth()->user()->role === 'admin')
                                <option value="">All Teachers</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"
                                        {{ request('user_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }} (Grade {{ $teacher->yearLevel->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            @else
                                <option value="{{ auth()->user()->id }}" selected>
                                    {{ auth()->user()->name }}
                                </option>
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative" style="height: 400px;">
                    <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                        <thead>
                            <tr class="text-left">
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    <input type="checkbox" id="selectAllCheckbox">
                                </th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    #
                                </th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    LRN Number
                                </th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Lastname, Firstname, Middlename, Suffix
                                </th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Age
                                </th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Gender
                                </th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Birthdate
                                </th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Section
                                </th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Year Level
                                </th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    School Year
                                </th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($students as $student)
                                @php
                                    $studentEnrollment = $student->enrollments->sortByDesc('created_at')->first();
                                    $currentEnrollment = $student->latestEnrollment;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="border-t px-6 py-4">
                                        <input type="checkbox" class="studentCheckbox" value="{{ $student->id }}">
                                    </td>
                                    <td class="border-t px-6 py-4">{{ $loop->iteration }}</td>
                                    <td class="border-t px-6 py-4">{{ $student->LRN_num }}</td>
                                    <td class="border-t px-6 py-4">{{ ucwords(strtolower($student->lastname)) }},
                                        {{ ucwords(strtolower($student->firstname)) }},
                                        {{ ucwords(strtolower($student->middlename ?? '-')) }},
                                        {{ ucwords(strtolower($student->suffix ?? '-')) }}</td>
                                    <td class="border-t px-6 py-4">{{ $student->age }}</td>
                                    <td class="border-t px-6 py-4">{{ ucwords(strtolower($student->gender)) }}</td>
                                    <td class="border-t px-6 py-4">{{ $student->birthdate }}</td>
                                    <td class="border-t px-6 py-4">{{ $student->section }}</td>
                                    <td class="border-t px-6 py-4"
                                        data-year-level="{{ $currentEnrollment->yearLevel->name ?? 'N/A' }}">
                                        {{ $currentEnrollment->yearLevel->name ?? 'N/A' }}
                                    </td>
                                    <td class="border-t px-6 py-4"
                                        data-school-year="{{ $currentEnrollment->yearLevel->name ?? 'N/A' }}">
                                        {{ $currentEnrollment->schoolYear->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div x-data="{ open: false, showPromoteModal: false }" class="inline-block text-left">
                                            <!-- Dropdown trigger -->
                                            <button @click="open = !open"
                                                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition-colors duration-200"
                                                aria-label="Student actions">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                </svg>
                                                Actions
                                            </button>

                                            <!-- Dropdown menu -->
                                            <div x-show="open" @click.away="open = false"
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="opacity-100 scale-100"
                                                x-transition:leave-end="opacity-0 scale-95"
                                                class="origin-top-right absolute right-0 mt-2 w-64 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 divide-y divide-gray-100">

                                                <!-- Basic Actions -->
                                                <div class="py-2 px-1 space-y-1">
                                                    <a href="{{ route('teacher.students.edit', $student->id) }}"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-md group">
                                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                        Edit Student
                                                    </a>
                                                </div>

                                                <!-- Attendance & Core Values -->
                                                <div class="py-2 px-1 space-y-1">
                                                    <div
                                                        class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wide">
                                                        Attendance & Core Values
                                                    </div>
                                                    @foreach ($student->enrollments->sortBy('school_year_id') as $enrollment)
                                                        <a href="{{ route('teacher.attendance-core-values.create', [
                                                            'enrollment' => $enrollment->id,
                                                            'year_level' => $enrollment->year_level_id,
                                                            'school_year' => $enrollment->school_year_id,
                                                        ]) }}"
                                                            class="flex items-center justify-between px-4 py-2 text-sm hover:bg-blue-50 rounded-md group">
                                                            <span
                                                                class="{{ $enrollment->attendanceCoreValues()->exists() ? 'text-green-700' : 'text-gray-700' }}">
                                                                {{ $enrollment->yearLevel->name }}
                                                                ({{ $enrollment->schoolYear->name }})
                                                            </span>
                                                            @if ($enrollment->attendanceCoreValues()->exists())
                                                                <svg class="w-4 h-4 text-green-500" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            @endif
                                                        </a>
                                                    @endforeach
                                                </div>

                                                <!-- Academic Records -->
                                                <div class="py-2 px-1 space-y-1">
                                                    <div
                                                        class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wide">
                                                        Academic Records
                                                    </div>
                                                    @foreach ($student->enrollments->sortBy('year_level_id') as $enrollment)
                                                        <a href="{{ route('teacher.students.show', [
                                                            $studentEnrollment->student->id,
                                                            'yearLevel' => $enrollment->year_level_id,
                                                            'schoolYear' => $enrollment->school_year_id,
                                                        ]) }}"
                                                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-md group">
                                                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            SF9 - Grade {{ $enrollment->yearLevel->name }}
                                                        </a>
                                                    @endforeach
                                                    <a href="{{ route('teacher.school-forms-10.show', ['student_id' => $student->id]) }}"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-md group">
                                                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        SF10 Records
                                                    </a>
                                                </div>

                                                <!-- Administrative Actions -->
                                                <div class="py-2 px-1 space-y-1">
                                                    <button @click="showPromoteModal = true"
                                                        class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-md group">
                                                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                        </svg>
                                                        Promote Student
                                                    </button>

                                                    <form
                                                        action="{{ route('teacher.students.destroy', $student->id) }}"
                                                        method="POST"
                                                        @submit.prevent="if(confirm('Are you sure you want to delete this student?')) $el.submit()"
                                                        class="w-full">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md group">
                                                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Delete Student
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Promote Modal -->
                                            <div x-show="showPromoteModal"
                                                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
                                                x-transition:enter="transition ease-out duration-150"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0">
                                                <div @click.away="showPromoteModal = false"
                                                    class="bg-white rounded-xl shadow-2xl max-w-md w-full">
                                                    <form
                                                        action="{{ route('teacher.students.promote', $student->id) }}"
                                                        method="POST" class="p-6">
                                                        @csrf
                                                        <div class="flex justify-between items-center mb-6">
                                                            <h3 class="text-lg font-semibold">Promote Student</h3>
                                                            <button type="button" @click="showPromoteModal = false"
                                                                class="text-gray-500 hover:text-gray-700">
                                                                ✕
                                                            </button>
                                                        </div>

                                                        <div class="space-y-4">
                                                            <div>
                                                                <label
                                                                    class="block text-sm font-medium text-gray-700 mb-2">
                                                                    Assign Teacher
                                                                </label>
                                                                <select name="user_id" id="user_id" required
                                                                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                    <option value="">Select Teacher</option>
                                                                    @php
                                                                        $schoolInfoId = optional(
                                                                            $student->enrollments->last(),
                                                                        )->school_info_id;
                                                                    @endphp

                                                                    @forelse ($teachers->where('school_info_id', $schoolInfoId) as $teacher)
                                                                        <option value="{{ $teacher->id }}">
                                                                            {{ $teacher->name }}
                                                                            ({{ $teacher->yearLevel->name ?? 'N/A' }})
                                                                        </option>
                                                                    @empty
                                                                        <option disabled>
                                                                            @if (!$schoolInfoId)
                                                                                School information missing
                                                                            @else
                                                                                No teachers available in this school
                                                                            @endif
                                                                        </option>
                                                                    @endforelse
                                                                </select>

                                                                <div class="flex justify-end gap-3 mt-6">
                                                                    <button type="button"
                                                                        @click="showPromoteModal = false"
                                                                        class="px-5 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                                                        Cancel
                                                                    </button>
                                                                    <button type="submit"
                                                                        class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                                                        Promote Student
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    @if ($students->hasPages())
                        <nav role="navigation" aria-label="Pagination Navigation"
                            class="flex items-center justify-between">
                            <div class="flex justify-between flex-1 sm:hidden">
                                @if ($students->onFirstPage())
                                    <span
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                        {!! __('pagination.previous') !!}
                                    </span>
                                @else
                                    <a href="{{ $students->previousPageUrl() }}"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                        {!! __('pagination.previous') !!}
                                    </a>
                                @endif

                                @if ($students->hasMorePages())
                                    <a href="{{ $students->nextPageUrl() }}"
                                        class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                        {!! __('pagination.next') !!}
                                    </a>
                                @else
                                    <span
                                        class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                        {!! __('pagination.next') !!}
                                    </span>
                                @endif
                            </div>

                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700 leading-5">
                                        {!! __('Showing') !!}
                                        <span class="font-medium">{{ $students->firstItem() }}</span>
                                        {!! __('to') !!}
                                        <span class="font-medium">{{ $students->lastItem() }}</span>
                                        {!! __('of') !!}
                                        <span class="font-medium">{{ $students->total() }}</span>
                                        {!! __('results') !!}
                                    </p>
                                </div>

                                <div>
                                    <span class="relative z-0 inline-flex shadow-sm rounded-md">
                                        {{-- Previous Page Link --}}
                                        @if ($students->onFirstPage())
                                            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                                <span
                                                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md leading-5"
                                                    aria-hidden="true">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </span>
                                        @else
                                            <a href="{{ $students->previousPageUrl() }}" rel="prev"
                                                class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150"
                                                aria-label="{{ __('pagination.previous') }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($students->onEachSide(1) as $page => $url)
                                            @if (is_string($page))
                                                <span aria-disabled="true">
                                                    <span
                                                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">{{ $page }}</span>
                                                </span>
                                            @endif

                                            @if (is_array($url))
                                                <a href="{{ $url }}"
                                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                                    {{ $page }}
                                                </a>
                                            @endif

                                            @if ($page === $students->currentPage())
                                                <span aria-current="page">
                                                    <span
                                                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-blue-600 border border-blue-600 cursor-default leading-5">{{ $page }}</span>
                                                </span>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($students->hasMorePages())
                                            <a href="{{ $students->nextPageUrl() }}" rel="next"
                                                class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150"
                                                aria-label="{{ __('pagination.next') }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @else
                                            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                                <span
                                                    class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md leading-5"
                                                    aria-hidden="true">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </nav>
                    @endif
                </div>

                <!-- CSV Import Form -->
                <div class="hidden mt-6">
                    <form action="{{ route('teacher.students.import') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="flex items-center gap-4">
                            <input type="file" name="file" class="border rounded-lg px-4 py-2 w-full sm:w-64"
                                accept=".csv" required>
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Import CSV
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/students/index.js') }}"></script>
</x-app-layout>
