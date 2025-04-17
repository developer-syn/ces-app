<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Class Records') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-white overflow-hidden sm:rounded-lg shadow-xl mx-8 h-auto">
            <div class="p-6 text-gray-900 h-full">
                <div class="mb-4">
                    <a href="{{ route('teacher.class-records.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        + Create New Class Record
                    </a>
                </div>

                <!-- Search and Filters -->
                <div class="mb-4 grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Search Input -->
                    <div>
                        <form action="{{ route('teacher.class-records.index') }}" method="GET" class="flex">
                            <!-- Keep the other filters in hidden inputs -->
                            <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                            <input type="hidden" name="grade_section" value="{{ request('grade_section') }}">
                            <input type="hidden" name="quarter" value="{{ request('quarter') }}">

                            <input type="text" name="search" placeholder="Search records..."
                                value="{{ request('search') }}"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full">
                        </form>
                    </div>

                    <!-- Subject Filter -->
                    <div>
                        <form action="{{ route('teacher.class-records.index') }}" method="GET">
                            <!-- Keep the other filters -->
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="grade_section" value="{{ request('grade_section') }}">
                            <input type="hidden" name="quarter_id" value="{{ request('quarter_id') }}">

                            <select name="subject_id" onchange="this.form.submit()"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full">
                                <option value="">Filter by Subject</option>
                                @foreach ($subjects as $subj)
                                    <option value="{{ $subj->id }}"
                                        {{ request('subject_id') == $subj->id ? 'selected' : '' }}>
                                        {{ $subj->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <!-- Grade & Section Filter -->
                    <div>
                        <form action="{{ route('teacher.class-records.index') }}" method="GET">
                            <!-- Keep the other filters -->
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                            <input type="hidden" name="quarter_id" value="{{ request('quarter_id') }}">

                            <select name="grade_section" onchange="this.form.submit()"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full"
                                @if (auth()->user()->role === 'teacher') disabled @endif>
                                <option value="">Filter by Grade & Section</option>

                                @if (auth()->user()->role === 'admin')
                                    @foreach ($gradeSections as $gs)
                                        <option value="{{ $gs->grade_section }}"
                                            {{ request('grade_section') == $gs->grade_section ? 'selected' : '' }}>
                                            {{ $gs->grade_section }} ({{ $gs->user->name }})
                                        </option>
                                    @endforeach
                                @else
                                    <option value="{{ auth()->user()->id }}" selected>
                                        {{ auth()->user()->name }} ({{ auth()->user()->yearLevel->name }} -
                                        {{ auth()->user()->section }})
                                    </option>
                                @endif
                            </select>
                        </form>
                    </div>



                    <!-- Quarter Filter -->
                    <div>
                        <form action="{{ route('teacher.class-records.index') }}" method="GET">
                            <!-- Keep the other filters -->
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                            <input type="hidden" name="grade_section" value="{{ request('grade_section') }}">

                            <select name="quarter_id" onchange="this.form.submit()"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full">
                                <option value="">Filter by Quarter</option>
                                @foreach ($quarters as $q)
                                    <option value="{{ $q->id }}"
                                        {{ request('quarter_id') == $q->id ? 'selected' : '' }}>
                                        {{ $q->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <!-- school Year Filter -->
                    <div>
                        <form action="{{ route('teacher.class-records.index') }}" method="GET">
                            <!-- Keep the other filters -->
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                            <input type="hidden" name="grade_section" value="{{ request('grade_section') }}">

                            <select name="school_year_id" onchange="this.form.submit()"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full">
                                <option value="">Filter by School Year</option>
                                @foreach ($schoolYear as $sy)
                                    <option value="{{ $sy->id }}"
                                        {{ request('school_year_id') == $sy->id ? 'selected' : '' }}>
                                        {{ $sy->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Records Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subject
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Grade & Section
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quarter
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    School Year
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                // Group the records so that each unique combination of subject, quarter, grade_section, school_year
                                // becomes a single group. We'll display only the first record from each group.
                                $groupedRecords = $classRecords->groupBy(function ($rec) {
                                    return $rec->subject_id .
                                        '|' .
                                        $rec->quarter .
                                        '|' .
                                        $rec->grade_section .
                                        '|' .
                                    $rec->school_year_id;
                                });
                            @endphp

                            @forelse($groupedRecords as $groupKey => $group)
                                @php
                                    // From each group, just take the first record to display
                                    $record = $group->first();
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $record->subject->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $record->grade_section }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $record->quarter->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $record->schoolYear->name }}
                                    </td>
                                    <td width="24" class="px-6 py-4 whitespace-nowrap text-sm">
                                        <!-- Alpine.js dropdown -->
                                        <div x-data="{ open: false }" class="relative inline-block text-left">
                                            <!-- Dropdown toggle button -->
                                            <button @click="open = !open"
                                                class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none">
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
                                                    <!-- Edit link -->
                                                    <a href="{{ route('teacher.class-records.edit', $record) }}"
                                                        class="block px-4 py-2 text-sm text-blue-700 hover:bg-blue-100">
                                                        Update Record
                                                    </a>

                                                    <!-- Delete form -->
                                                    <form
                                                        action="{{ route('teacher.class-records.destroy', $record) }}"
                                                        method="POST" class="block"
                                                        onsubmit="return confirm('Are you sure you want to delete this record?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                            Delete Record
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No class records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div> <!-- end overflow-x-auto -->
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $classRecords->links() }}
                </div>
            </div> <!-- end p-6 -->
        </div> <!-- end bg-white -->
    </div> <!-- end py-4 -->
</x-app-layout>
