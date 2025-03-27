<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/school-forms/index.css') }}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('School Form 10') }}
        </h2>
    </x-slot>
    <div class="font-family">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm">
                    <p>SF10-ES</p>
                    <div class="px-6">
                        <!-- Header Section -->
                        <div class="flex items-center justify-between">
                            <img src="{{ asset('img/Seal_of_the_Department_of_Education_of_the_Philippines.png') }}"
                                alt="DepEd Logo" class="w-20 h-20">
                            <div class="text-center">
                                <p class="text-sm">Republic of the Philippines</p>
                                <p class="text-sm">Department of Education</p>
                                <h1 class="text-xl font-bold mt-2">Learner Permanent Record for Elementary School
                                    (SF10-ES)
                                </h1>
                                <p class="text-xs italic">(Formerly Form 137)</p>
                            </div>
                            <img src="{{ asset('img/deped.png') }}" alt="DepEd Text" class="w-40 h-auto">
                        </div>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="border-gray-300 mb-2 bg-white">
                        <h2 class="border border-black bg-gray-400 font-bold uppercase text-center">
                            Learner's Personal Information</h2>
                        <div class="student-info px-4 mt-1">
                            <div class="student-info-row">
                                <span class="label">LAST NAME:</span>
                                <div class="line">&nbsp;&nbsp;&nbsp;{{ strtoupper($student->lastname) }}</div>
                                <span class="label">FIRST NAME:</span>
                                <div class="line">&nbsp;&nbsp;&nbsp;{{ strtoupper($student->firstname) }}</div>
                                <span class="label">NAME EXTN. (Jr,I,II):</span>
                                <div class="line">&nbsp;&nbsp;&nbsp{{ strtoupper($student->suffix) }};</div>
                                <span class="label">MIDDLE NAME:</span>
                                <div class="line">&nbsp;&nbsp;&nbsp;{{ strtoupper($student->middlename) }}</div>
                            </div>
                            <div class="student-info-row mt-1">
                                <span class="label">Learner Reference Number (LRN): </span>
                                <div class="line" style="flex: 0.3;">&nbsp;&nbsp;&nbsp;{{ $student->LRN_num }}</div>
                                <span class="short-label">Birthdate (mm/dd/yyyy):</span>
                                <div class="line" style="flex: 0.7;">&nbsp;&nbsp;&nbsp;{{ $student->birthdate }}</div>
                                <span class="short-label">Sex:</span>
                                <div class="line" style="flex: 0.7;">
                                    &nbsp;&nbsp;&nbsp;{{ strtoupper($student->gender) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Eligibility Section -->
                    <div class="border border-gray-300 shadow-sm bg-white">
                        <h2 class="border border-black bg-gray-400 font-bold uppercase text-center">
                            Eligibility for Elementary School Enrollment</h2>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 px-4 py-1">
                            <div class="flex items-center">
                                <label for="credential" class="text-sm font-medium text-gray-700">Credential Presented
                                    for
                                    Grade 1</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="credential" name="credential"
                                    class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked>
                                <label for="credential" class="text-sm font-medium text-gray-700">Kinder Progress
                                    Report</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="kinder" name="kinder"
                                    class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked>
                                <label for="kinder" class="text-sm font-medium text-gray-700">ECCD Checklist</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="eccd" name="eccd"
                                    class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked>
                                <label for="eccd" class="text-sm font-medium text-gray-700">Kindergarten Certificate
                                    of
                                    Completion</label>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 px-4 py-1">
                            <div class="flex items-center" style="width: 50rem;">
                                <label for="credential" class="text-sm font-medium text-gray-700">School Name:</label>
                                <div class="text-sm font-medium text-gray-700 px-4">{{ $schoolInfo->school_name }}
                                </div>
                            </div>
                            <div class="flex items-center" style="width: 24rem;padding-left: 10rem;">
                                <label for="credential" class="text-sm font-medium text-gray-700">School ID:</label>
                                <div class="text-sm font-medium text-gray-700 px-4">{{ $schoolInfo->school_id }}</div>
                            </div>
                            <div class="flex items-center" style="width: 50rem;padding-left: 8rem;">
                                <label for="credential" class="text-sm font-medium text-gray-700">Address of
                                    School:</label>
                                <div class="text-sm font-medium text-gray-700 px-4">
                                    {{ $schoolInfo->address ?? 'Caloc-an' }}, {{ $schoolInfo->district }},
                                    {{ $schoolInfo->division }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scholastic Record -->
                <div class="bg-white border border-gray-300">
                    <h2 class="border border-black bg-gray-400 text-center font-bold">SCHOLASTIC RECORD</h2>
                    <div class="grid grid-cols-2 md:grid-cols-2 gap-x-6">
                        {{-- year level 1 or grade 1 --}}
                        @include('teacher.school-forms-10.grade-level.grade1')

                        {{-- year level 2 or grade 2 --}}
                        @include('teacher.school-forms-10.grade-level.grade2')
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        {{-- year level 3 or grade 3 --}}
                        @include('teacher.school-forms-10.grade-level.grade3')

                        {{-- year level 4 or grade 4 --}}
                        @include('teacher.school-forms-10.grade-level.grade4')
                    </div>
                </div>
                <p class="text-right">SFRT 2017</p>
            </div>
        </div>
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 px-2">
                        <h2 class="text-left">SF10-ES</h2>
                        <h2 class="text-right">Page 2 of ___</h2>
                    </div>
                    <!-- Scholastic Record -->
                    <div class="border border-gray-300">
                        <h2 class="border border-black bg-gray-400 text-center font-bold">SCHOLASTIC RECORD</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- year level 1 or grade 1 --}}
                            @include('teacher.school-forms-10.grade-level.grade5')

                            {{-- year level 2 or grade 2 --}}
                            @include('teacher.school-forms-10.grade-level.grade6')
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            {{-- year level 3 or grade 3 --}}
                            @include('teacher.school-forms-10.grade-level.grade7')

                            {{-- year level 4 or grade 4 --}}
                            @include('teacher.school-forms-10.grade-level.grade8')
                        </div>
                    </div>

                    <!-- For Transfer Out/Elementary School Completer Section -->
                    <div class="border border-black mt-4">
                        <div class="bg-white p-2">
                            <div class="text-left font-bold text-sm">For Transfer Out/Elementary School Completer Only
                            </div>

                            <!-- Certification Boxes -->
                            @for ($i = 1; $i <= 3; $i++)
                                <div class="border-t border-black p-3">
                                    <div class="text-center font-bold mb-2">CERTIFICATION</div>
                                    <div class="text-sm">
                                        I CERTIFY that this is a true record of _________________________ with LRN
                                        ________________ and that he/she is eligible for admission to Grade ______.
                                    </div>
                                    <div class="text-sm mt-1">
                                        School Name: _________________________ School ID _____________ Division:
                                        _____________ Last School Year Attended: _________________
                                    </div>
                                    <div class="flex justify-between mt-4">
                                        <div class="text-center">
                                            <div class="border-t border-black w-48">Date</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="border-t border-black w-64">Name of Principal/School Head over
                                                Printed Name</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="border-t border-black w-48">(Affix School Seal here)</div>
                                        </div>
                                    </div>
                                </div>
                            @endfor

                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 px-2">
                        <p class="text-xs text-left italic p-1">May add Certification Box if needed</p>
                        <p class="text-xs text-right p-1">SFRT Revised 2017</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
