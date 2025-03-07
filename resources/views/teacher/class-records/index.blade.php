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
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Search and Filters -->
                <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <form action="{{ route('teacher.class-records.index') }}" method="GET" class="flex">
                            <input type="text" name="search" placeholder="Search records..."
                                value="{{ request('search') }}"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full">
                        </form>
                    </div>

                    <div>
                        <select name="subject_id" onchange="this.form.submit()"
                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full">
                            <option value="">Filter by Subject</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject }}"
                                    {{ request('subject') == $subject ? 'selected' : '' }}>
                                    {{ $subject }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="grade_section" onchange="this.form.submit()"
                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full">
                            <option value="">Filter by Grade & Section</option>
                            @foreach ($yearLevel as $yearLevel)
                                <option value="{{ $yearLevel }}"
                                    {{ request('year_level_id') == $yearLevel ? 'selected' : '' }}>
                                    {{ $yearLevel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="quarter" onchange="this.form.submit()"
                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full">
                            <option value="">Filter by Quarter</option>
                            @foreach ($quarters as $quarter)
                                <option value="{{ $quarter }}"
                                    {{ request('quarter') == $quarter ? 'selected' : '' }}>
                                    Quarter {{ $quarter }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Records Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subject</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Grade & Section</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quarter</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    School Year</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($classRecords as $record)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $record->subject_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $record->grade_section }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $record->quarter }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $record->school_year }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('teacher.class-records.edit', $record) }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>

                                        <form action="{{ route('teacher.class-records.destroy', $record) }}"
                                            method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900"
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
