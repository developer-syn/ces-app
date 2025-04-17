<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Students') }}
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
                            <button id="exportCsvButton"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Export CSV
                            </button>
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
                                        {{ $teacher->name }}
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
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="border-t px-6 py-4">
                                        <input type="checkbox" class="studentCheckbox" value="{{ $student->id }}">
                                    </td>
                                    <td class="border-t px-6 py-4">{{ $student->LRN_num }}</td>
                                    <td class="border-t px-6 py-4">{{ ucwords(strtolower($student->lastname)) }},
                                        {{ ucwords(strtolower($student->firstname)) }},
                                        {{ ucwords(strtolower($student->middlename ?? '-')) }},
                                        {{ ucwords(strtolower($student->suffix ?? '-')) }}</td>
                                    <td class="border-t px-6 py-4">{{ $student->age }}</td>
                                    <td class="border-t px-6 py-4">{{ $student->gender }}</td>
                                    <td class="border-t px-6 py-4">{{ $student->birthdate }}</td>
                                    <td class="border-t px-6 py-4">{{ $student->section }}</td>
                                    <td class="border-t px-6 py-4"
                                        data-year-level="{{ $studentEnrollment->year_level_id ?? ($student->yearLevel->id ?? '') }}">
                                        {{ $studentEnrollment->yearLevel->name ?? ($student->yearLevel->name ?? '-') }}
                                    </td>
                                    <td class="border-t px-6 py-4"
                                        data-school-year="{{ $studentEnrollment->school_year_id ?? ($student->schoolYear->id ?? '') }}">
                                        {{ $studentEnrollment->schoolYear->name ?? ($student->schoolYear->name ?? '-') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div x-data="{ open: false }" class=" inline-block text-left overflow-visible">
                                            <!-- Dropdown toggle button -->
                                            <button @click="open = !open"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none">
                                                Actions
                                                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 01.02-1.06z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            <!-- Dropdown menu -->
                                            <div x-show="open" @click.away="open = false"
                                                class="origin-top-right absolute right-0 mt-2 w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                                style="overflow: visible;">
                                                <div class="py-1">

                                                    <a href="{{ route('teacher.students.edit', $studentEnrollment->student_id) }}"
                                                        class="block px-4 py-2 text-sm text-blue-700 hover:bg-blue-100">
                                                        Edit
                                                    </a>

                                                    {{-- @foreach ($student->enrollments->sortBy('school_year_id') as $enrollment)
                                                        @php
                                                            $hasRecords = $enrollment->attendanceCoreValues()->exists();
                                                        @endphp

                                                        <a href="{{ route('teacher.attendance-core-values.create', [
                                                            'enrollment' => $enrollment->id,
                                                            'year_level' => $enrollment->year_level_id,
                                                            'school_year' => $enrollment->school_year_id,
                                                        ]) }}"
                                                            class="flex justify-between items-center px-4 py-2 text-sm hover:bg-gray-50 {{ $hasRecords ? 'text-green-700' : 'text-yellow-700' }}">
                                                            <span>
                                                                Attendance&CoreValues
                                                                ({{ $enrollment->yearLevel->name }} -
                                                                {{ $enrollment->schoolYear->name }})
                                                            </span>
                                                            @if ($hasRecords)
                                                                <svg class="w-4 h-4 ml-2 text-green-500"
                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            @endif
                                                        </a>
                                                    @endforeach --}}
                                                    <select onchange="window.location.href = this.value" class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                        <option value="" selected disabled>Select Attendance&CoreValues</option>
                                                        @foreach ($student->enrollments->sortBy('school_year_id') as $enrollment)
                                                            @php
                                                                $hasRecords = $enrollment->attendanceCoreValues()->exists();
                                                            @endphp

                                                            <option value="{{ route('teacher.attendance-core-values.create', [
                                                                'enrollment' => $enrollment->id,
                                                                'year_level' => $enrollment->year_level_id,
                                                                'school_year' => $enrollment->school_year_id,
                                                            ]) }}" class="{{ $hasRecords ? 'text-green-700' : 'text-yellow-700' }}">
                                                                Attendance&CoreValues ({{ $enrollment->yearLevel->name }} - {{ $enrollment->schoolYear->name }})
                                                                @if ($hasRecords) ✓ @endif
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    {{-- @foreach ($student->enrollments->sortBy('year_level_id') as $enrollment)
                                                        <a href="{{ route('teacher.students.show', [
                                                            $studentEnrollment->student->id,
                                                            'yearLevel' => $enrollment->year_level_id,
                                                            'schoolYear' => $enrollment->school_year_id,
                                                        ]) }}"
                                                            class="block px-4 py-2 text-sm text-yellow-700 hover:bg-yellow-100">
                                                            SF09 - Grade {{ $enrollment->yearLevel->name }}
                                                            ({{ $enrollment->schoolYear->name }})
                                                        </a>
                                                    @endforeach --}}
                                                    <select onchange="window.location.href = this.value" class="block w-full px-4 py-2 text-sm text-white-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 hover:bg-white-100 mt-1">
                                                        <option value="" selected disabled>Select SF9 Record</option>
                                                        @foreach ($student->enrollments->sortBy('year_level_id') as $enrollment)
                                                            <option value="{{ route('teacher.students.show', [
                                                                $studentEnrollment->student->id,
                                                                'yearLevel' => $enrollment->year_level_id,
                                                                'schoolYear' => $enrollment->school_year_id,
                                                            ]) }}">
                                                                SF09 - Grade {{ $enrollment->yearLevel->name }} ({{ $enrollment->schoolYear->name }})
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <a href="{{ route('teacher.school-forms-10.show', ['student_id' => $student->id]) }}"
                                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        SF10
                                                    </a>

                                                    <form
                                                        action="{{ route('teacher.students.promote', $student->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <label for="user_id"
                                                            class="px-4 block text-sm font-medium text-gray-700">Assign
                                                            Teacher:</label>
                                                        <select name="user_id" id="user_id" required
                                                            class="mt-1 px-4 block w-full p-2 border-none rounded-md">
                                                            <option value="">Select a Teacher</option>
                                                            @foreach ($teachers as $teacher)
                                                                <option value="{{ $teacher->id }}">
                                                                    {{ $teacher->name }}
                                                                    ({{ $teacher->yearLevel->name }})
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <button type="submit"
                                                            class="block w-full px-4 py-2 text-sm text-left text-green-700 hover:bg-green-100">
                                                            Promote
                                                        </button>
                                                    </form>

                                                    <form
                                                        action="{{ route('teacher.students.destroy', $student->id) }}"
                                                        method="POST" class="block"
                                                        onsubmit="return confirm('Are you sure you want to delete this student?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                            Delete
                                                        </button>
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
                <div class="mt-6">
                    <form action="{{ route('teacher.students.import') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="flex items-center gap-4">
                            <input type="file" name="file" class="border rounded-lg px-4 py-2 w-full sm:w-64"
                                accept=".csv">
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
