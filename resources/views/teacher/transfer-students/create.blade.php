<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Register Transferred Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-12">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-blue-600 text-white px-6 py-4">
                    <h2 class="text-xl font-semibold flex items-center">
                        <?xml version="1.0" encoding="utf-8"?>
                        <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <rect x="0" fill="none" width="24" height="24" />
                            <g>
                                <path
                                    d="M24 14.6c0 .6-1.2 1-2.6 1.2-.9-1.7-2.7-3-4.8-3.9.2-.3.4-.5.6-.8h.8c3.1-.1 6 1.8 6 3.5zM6.8 11H6c-3.1 0-6 1.9-6 3.6 0 .6 1.2 1 2.6 1.2.9-1.7 2.7-3 4.8-3.9l-.6-.9zm5.2 1c2.2 0 4-1.8 4-4s-1.8-4-4-4-4 1.8-4 4 1.8 4 4 4zm0 1c-4.1 0-8 2.6-8 5 0 2 8 2 8 2s8 0 8-2c0-2.4-3.9-5-8-5zm5.7-3h.3c1.7 0 3-1.3 3-3s-1.3-3-3-3c-.5 0-.9.1-1.3.3.8 1 1.3 2.3 1.3 3.7 0 .7-.1 1.4-.3 2zM6 10h.3C6.1 9.4 6 8.7 6 8c0-1.4.5-2.7 1.3-3.7C6.9 4.1 6.5 4 6 4 4.3 4 3 5.3 3 7s1.3 3 3 3z" />
                            </g>
                        </svg>&nbsp;
                        Register Transferred Student
                    </h2>
                </div>

                <!-- Form Body -->
                <div class="p-8">
                    <form method="POST" action="{{ route('teacher.transfer-students.store') }}" id="studentForm"
                        novalidate>
                        @csrf

                        <!-- Student Information Section -->
                        <div class="mb-8">
                            <h5
                                class="text-blue-600 text-lg font-semibold border-b-2 border-blue-600 pb-2 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Student Information
                            </h5>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 mb-2">
                                        Student <span class="text-red-500">*</span>
                                    </label>
                                    <select name="student_id"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out"
                                        required>
                                        <option value="">-- Select Student --</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}"
                                                {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->lastname }} ({{ $student->LRN_num ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700 mb-2">
                                        Year Level <span class="text-red-500">*</span>
                                    </label>
                                    <select name="year_level_id"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out"
                                        required>
                                        <option value="">-- Select Year Level --</option>
                                        @foreach ($yearLevels as $level)
                                            <option value="{{ $level->id }}"
                                                {{ old('year_level_id') == $level->id ? 'selected' : '' }}>
                                                {{ $level->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('year_level_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information Section -->
                        <div class="mb-8">
                            <h5
                                class="text-blue-600 text-lg font-semibold border-b-2 border-blue-600 pb-2 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                                Academic Information
                            </h5>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 mb-2">
                                        School Year <span class="text-red-500">*</span>
                                    </label>
                                    <select name="school_year_id"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out"
                                        required>
                                        <option value="">-- Select School Year --</option>
                                        @foreach ($schoolYears as $year)
                                            <option value="{{ $year->id }}"
                                                {{ old('school_year_id') == $year->id ? 'selected' : '' }}>
                                                {{ $year->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('school_year_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>



                                <div>
                                    <label class="block font-medium text-sm text-gray-700 mb-2">
                                        Previous School <span class="text-gray-500">(Optional)</span>
                                    </label>
                                    <select name="school_info_id"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">
                                        <option value="">-- None --</option>
                                        @foreach ($schoolInfos as $school)
                                            <option value="{{ $school->id }}"
                                                {{ old('school_info_id') == $school->id ? 'selected' : '' }}>
                                                {{ $school->school_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700 mb-2">Section</label>
                                    <input type="text" name="section" value="{{ old('section') }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out"
                                        placeholder="e.g., Section A, Room 101">
                                </div>
                            </div>
                        </div>

                        <!-- Quarterly Grades Section -->
                        <!-- Subjects and Grades Section -->
                        <div class="mb-8">
                            <h5
                                class="text-blue-600 text-lg font-semibold border-b-2 border-blue-600 pb-2 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Subject Grades
                            </h5>

                            <table class="w-full text-sm text-left border border-gray-300 mb-4" id="gradesTable">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2">Subject</th>
                                        <th class="px-3 py-2">Q1</th>
                                        <th class="px-3 py-2">Q2</th>
                                        <th class="px-3 py-2">Q3</th>
                                        <th class="px-3 py-2">Q4</th>
                                        <th class="px-3 py-2">Final Rating</th>
                                        <th class="px-3 py-2">Remarks</th>
                                        <th class="px-3 py-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="subjectRows">
                                    <!-- Rows will be dynamically inserted -->
                                </tbody>
                            </table>

                            <button type="button" onclick="addSubjectRow()"
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                + Add Subject
                            </button>

                            <!-- Embed subject options once (Blade-rendered) -->
                            <template id="subjectOptionsTemplate">
                                <option value="">-- Select Subject --</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </template>

                            <div class="mt-6">
                                <label class="block text-gray-700 font-medium mb-2">General Average:</label>
                                <input type="text" id="generalAverage" name="general_average" readonly
                                    class="w-40 text-center bg-gray-100 rounded-md border border-gray-300 py-2 px-3 text-gray-700">
                            </div>
                        </div>


                        <!-- Teacher Information Section -->
                        <div class="mb-8">
                            <h5
                                class="text-blue-600 text-lg font-semibold border-b-2 border-blue-600 pb-2 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Teacher Information
                            </h5>

                            <div class="md:w-1/2">
                                <label class="block font-medium text-sm text-gray-700 mb-2">Registering Teacher</label>
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                <input type="text"
                                    class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm text-gray-600"
                                    value="{{ auth()->user()->name }}" readonly>
                                <p class="text-gray-500 text-xs mt-1">This is the currently logged-in teacher.</p>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="border-t pt-6">
                            <div class="flex flex-col sm:flex-row justify-end gap-3">
                                <a href="{{ route('teacher.students.index') }}"
                                    class="inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel
                                </a>
                                <button type="submit" id="submitBtn"
                                    class="inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                        </path>
                                    </svg>
                                    Register Student
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('studentForm');
            const submitBtn = document.getElementById('submitBtn');

            // Add loading state to submit button
            form.addEventListener('submit', function() {
                submitBtn.innerHTML = `
                    <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                `;
                submitBtn.disabled = true;
            });
        });
    </script>
    <script>
        const subjects = @json($subjects);
        let subjectIndex = 0;

        function addSubjectRow() {
            const tableBody = document.getElementById('subjectRows');
            const row = document.createElement('tr');

            // Build subject dropdown
            let subjectOptions = `<option value="">-- Select Subject --</option>`;
            subjects.forEach(subject => {
                subjectOptions += `<option value="${subject.id}">${subject.name}</option>`;
            });

            row.innerHTML = `
            <td class="border px-2 py-1">
                <select name="subjects[${subjectIndex}][subject_id]" class="w-full rounded-md border-gray-300" required>
                    ${subjectOptions}
                </select>
            </td>
            <td class="border px-2 py-1">
                <input type="number" step="0.01" min-length="60" max="100" name="subjects[${subjectIndex}][q1]" class="grade w-full text-center border-gray-300 rounded" oninput="updateRow(this)">
            </td>
            <td class="border px-2 py-1">
                <input type="number" step="0.01" min-length="60" max="100" name="subjects[${subjectIndex}][q2]" class="grade w-full text-center border-gray-300 rounded" oninput="updateRow(this)">
            </td>
            <td class="border px-2 py-1">
                <input type="number" step="0.01" min-length="60" max="100" name="subjects[${subjectIndex}][q3]" class="grade w-full text-center border-gray-300 rounded" oninput="updateRow(this)">
            </td>
            <td class="border px-2 py-1">
                <input type="number" step="0.01" min-length="60" max="100" name="subjects[${subjectIndex}][q4]" class="grade w-full text-center border-gray-300 rounded" oninput="updateRow(this)">
            </td>
            <td class="border px-2 py-1 text-center">
                <input type="text" name="subjects[${subjectIndex}][final]" readonly class="final w-full text-center bg-gray-100 rounded">
            </td>
            <td class="border px-2 py-1 text-center">
                <input type="text" name="subjects[${subjectIndex}][remarks]" readonly class="remarks w-full text-center bg-gray-100 rounded">
            </td>
            <td class="border px-2 py-1 text-center">
                <button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800">Delete</button>
            </td>
        `;
            tableBody.appendChild(row);
            subjectIndex++;
        }

        function removeRow(button) {
            const row = button.closest('tr');
            row.remove();
            updateGeneralAverage();
        }

        function updateRow(input) {
            const row = input.closest('tr');
            const grades = row.querySelectorAll('.grade');
            let total = 0,
                count = 0;

            grades.forEach(g => {
                const val = parseFloat(g.value);
                if (!isNaN(val)) {
                    total += val;
                    count++;
                }
            });

            const rawAverage = count > 0 ? (total / count) : '';
            const remarks = rawAverage ? (rawAverage >= 75 ? 'Passed' : 'Failed') : '';

            row.querySelector('.final').value = rawAverage;
            row.querySelector('.remarks').value = remarks;

            updateGeneralAverage();
        }

        function updateGeneralAverage() {
            const finalInputs = document.querySelectorAll('.final');
            let total = 0,
                count = 0;

            finalInputs.forEach(input => {
                const val = parseFloat(input.value);
                if (!isNaN(val)) {
                    total += val;
                    count++;
                }
            });

            const average = count > 0 ? (total / count) : '';
            const generalAverageInput = document.getElementById('generalAverage');
            if (generalAverageInput) {
                generalAverageInput.value = average;
            }
        }
    </script>


</x-app-layout>
