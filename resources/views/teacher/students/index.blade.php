<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Students') }}
        </h2>
    </x-slot>
    <div class="py-4">
        {{-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> --}}
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
                            Add student
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
                        <select id="schoolYearFilter"
                            class="border rounded-lg px-4 py-2 w-full sm:w-48 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All School Years</option>
                            @foreach ($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->id }}"
                                    {{ $schoolYear->id == ($currentSchoolYear->id ?? '') ? 'selected' : '' }}>
                                    {{ $schoolYear->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Year Level Filter -->
                        <select id="yearLevelFilter"
                            class="border rounded-lg px-4 py-2 w-full sm:w-48 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Year Levels</option>
                            @foreach ($yearLevels as $yearLevel)
                                <option value="{{ $yearLevel->id }}">{{ $yearLevel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Table -->
                <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                    <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                        <thead>
                            <tr class="text-left">
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    <input type="checkbox" id="selectAllCheckbox">
                                </th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Name</th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Birthdate</th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Section</th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Year Level</th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    School Year</th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($students as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="border-t px-6 py-4">
                                        <input type="checkbox" class="studentCheckbox" value="{{ $student->id }}">
                                    </td>
                                    <td class="border-t px-6 py-4">{{ $student->name }}</td>
                                    <td class="border-t px-6 py-4">{{ $student->birthdate }}</td>
                                    <td class="border-t px-6 py-4">{{ $student->section }}</td>
                                    <td class="border-t px-6 py-4" data-year-level="{{ $student->year_level_id }}">
                                        {{ $student->yearLevel->name ?? 'N/A' }}
                                    </td>
                                    <td class="border-t px-6 py-4" data-school-year="{{ $student->school_year_id }}">
                                        {{ $student->schoolYear->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div x-data="{ open: false }"
                                            class="relative inline-block text-left overflow-visible">
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
                                                    <!-- Edit link -->
                                                    <a href="{{ route('teacher.students.edit', $student->id) }}"
                                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Edit
                                                    </a>

                                                    <!-- SF09 link -->
                                                    <a href="{{ route('teacher.students.show', $student->id) }}"
                                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        SF09
                                                    </a>

                                                    <!-- SF10 link -->
                                                    <a href="{{ route('teacher.students.sf10', $student->id) }}"
                                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        SF10
                                                    </a>

                                                    <!-- Delete form -->
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const yearLevelFilter = document.getElementById('yearLevelFilter');
            const schoolYearFilter = document.getElementById('schoolYearFilter');
            const tableRows = document.querySelectorAll('tbody tr');
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const studentCheckboxes = document.querySelectorAll('.studentCheckbox');
            const exportCsvButton = document.getElementById('exportCsvButton');
            const deleteSelectedButton = document.getElementById('deleteSelectedButton');

            // Filter Table Function
            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedYearLevel = yearLevelFilter.value;
                const selectedSchoolYear = schoolYearFilter.value;

                tableRows.forEach(row => {
                    const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const yearLevel = row.querySelector('td:nth-child(5)').dataset.yearLevel;
                    const schoolYear = row.querySelector('td:nth-child(6)').dataset.schoolYear;

                    const matchesSearch = name.includes(searchTerm);
                    const matchesYearLevel = !selectedYearLevel || yearLevel === selectedYearLevel;
                    const matchesSchoolYear = !selectedSchoolYear || schoolYear === selectedSchoolYear;

                    row.style.display = (matchesSearch && matchesYearLevel && matchesSchoolYear) ? '' :
                        'none';
                });
            }

            // Select All Checkbox Functionality
            selectAllCheckbox.addEventListener('change', function() {
                studentCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });

            // Export CSV Functionality
            exportCsvButton.addEventListener('click', function() {
                const selectedStudents = Array.from(studentCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => {
                        const row = checkbox.closest('tr');
                        return {
                            id: checkbox.value,
                            name: row.cells[1].textContent,
                            birthdate: row.cells[2].textContent,
                            section: row.cells[3].textContent,
                            // Use getAttribute to retrieve the ID from the data attribute.
                            year_level_id: row.cells[4].getAttribute('data-year-level'),
                            school_year_id: row.cells[5].getAttribute('data-school-year')
                        };
                    });

                if (selectedStudents.length > 0) {
                    // Create CSV content
                    const csvContent = [
                        ['ID', 'Name', 'Birthdate', 'Section', 'Year Level', 'School Year'].join(','),
                        ...selectedStudents.map(student => [
                            `"${student.id}"`,
                            `"${student.name}"`,
                            `"${student.birthdate}"`,
                            `"${student.section}"`,
                            `"${student.year_level_id}"`,
                            `"${student.school_year_id}"`
                        ].join(','))
                    ].join('\n');

                    // Create blob and download
                    const blob = new Blob([csvContent], {
                        type: 'text/csv;charset=utf-8;'
                    });
                    const link = document.createElement('a');
                    const url = URL.createObjectURL(blob);

                    link.setAttribute('href', url);
                    link.setAttribute('download', 'students_export.csv');
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } else {
                    alert('Please select at least one student to export.');
                }
            });

            // Delete Selected Functionality
            deleteSelectedButton.addEventListener('click', function() {
                const selectedStudents = Array.from(studentCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                if (selectedStudents.length > 0) {
                    if (confirm('Are you sure you want to delete the selected students?')) {
                        // Example AJAX request using fetch:
                        fetch('/teacher/students/delete-selected', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content,
                                },
                                body: JSON.stringify({
                                    students: selectedStudents
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Optionally refresh the page or remove deleted rows from the DOM
                                    location.reload();
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                } else {
                    alert('Please select at least one student to delete.');
                }
            });


            // Event listeners for search and filters
            searchInput.addEventListener('input', filterTable);
            yearLevelFilter.addEventListener('change', filterTable);
            schoolYearFilter.addEventListener('change', filterTable);

            // Initial filter to show current school year
            filterTable();
        });
    </script>
</x-app-layout>
