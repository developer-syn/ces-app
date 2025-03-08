<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Class Records') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-4">
                    <a href="{{ route('teacher.class-records.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        + Create New Class Record
                    </a>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Search and Filters -->
                <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
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
                            <input type="hidden" name="quarter" value="{{ request('quarter') }}">

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
                            <input type="hidden" name="quarter" value="{{ request('quarter') }}">

                            <select name="grade_section" onchange="this.form.submit()"
                                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full">
                                <option value="">Filter by Grade & Section</option>
                                @foreach ($gradeSections as $gs)
                                    <option value="{{ $gs }}"
                                        {{ request('grade_section') == $gs ? 'selected' : '' }}>
                                        {{ $gs }}
                                    </option>
                                @endforeach
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

                            <select name="quarter" onchange="this.form.submit()"
                                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full">
                                <option value="">Filter by Quarter</option>
                                @foreach ($quarters as $q)
                                    <option value="{{ $q }}"
                                        {{ request('quarter') == $q ? 'selected' : '' }}>
                                        {{ $q }}
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subject
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Grade & Section
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quarter
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    School Year
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                // Group the records so that each unique combination of subject, quarter, grade_section, school_year
                                // becomes a single group. We'll display only the first record from each group.
                                $groupedRecords = $classRecords->groupBy(function ($rec) {
                                    return $rec->subject_id
                                        .'|'.$rec->quarter
                                        .'|'.$rec->grade_section
                                        .'|'.$rec->school_year;
                                });
                            @endphp

                            @forelse($groupedRecords as $groupKey => $group)
                                @php
                                    // From each group, just take the first record to display
                                    $record = $group->first();
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $record->subject->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $record->grade_section }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $record->quarter }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $record->schoolYear->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{-- <a href="{{ route('teacher.class-records.show', $record) }}"
                                           class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Show
                                        </a> --}}
                                        <a href="{{ route('teacher.class-records.edit', $record) }}"
                                           class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </a>
                                        <form action="{{ route('teacher.class-records.destroy', $record) }}"
                                              method="POST"
                                              class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                Delete
                                            </button>
                                        </form>
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
            </div> <!-- end p-6 -->
        </div> <!-- end bg-white -->
    </div> <!-- end py-4 -->
</x-app-layout>
