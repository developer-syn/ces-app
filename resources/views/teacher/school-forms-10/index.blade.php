<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/school-forms/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/school-forms/print.css') }}">

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('School Form 10') }}
            </h2>
            <!-- Print Button -->
            <button id="printButton" class="print-button bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Form
            </button>
        </div>
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
                                <div class="line">&nbsp;&nbsp;&nbsp;</div>
                                <span class="label">FIRST NAME:</span>
                                <div class="line">&nbsp;&nbsp;&nbsp;</div>
                                <span class="label">NAME EXTN. (Jr,I,II):</span>
                                <div class="line">&nbsp;&nbsp;&nbsp;</div>
                                <span class="label">MIDDLE NAME:</span>
                                <div class="line">&nbsp;&nbsp;&nbsp;</div>
                            </div>
                            <div class="student-info-row mt-1">
                                <span class="label">Learner Reference Number (LRN): </span>
                                <div class="line" style="flex: 0.3;">&nbsp;&nbsp;&nbsp;</div>
                                <span class="short-label">Birthdate (mm/dd/yyyy):</span>
                                <div class="line" style="flex: 0.7;">&nbsp;&nbsp;&nbsp;</div>
                                <span class="short-label">Sex:</span>
                                <div class="line" style="flex: 0.7;">&nbsp;&nbsp;&nbsp;</div>
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
                                    class="h-5 w-5 rounded border-gray-800 text-blue-600 focus:ring-blue-500">
                                <label for="credential" class="text-sm font-medium text-gray-700">Kinder Progress
                                    Report</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="kinder" name="kinder"
                                    class="h-5 w-5 rounded border-gray-800 text-blue-600 focus:ring-blue-500">
                                <label for="kinder" class="text-sm font-medium text-gray-800">ECCD Checklist</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="eccd" name="eccd"
                                    class="h-5 w-5 rounded border-gray-800 text-blue-600 focus:ring-blue-500">
                                <label for="eccd" class="text-sm font-medium text-gray-800">Kindergarten Certificate
                                    of
                                    Completion</label>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 px-4 py-1">
                            <div class="flex items-center" style="width: 50rem;">
                                <label for="credential" class="text-sm font-medium text-gray-700">School Name:</label>
                                <div class="text-sm font-medium text-gray-700 px-4">{{ $schoolInfo->school_name }}</div>
                            </div>
                            <div class="flex items-center" style="width: 24rem;padding-left: 10rem;">
                                <label for="credential" class="text-sm font-medium text-gray-700">School ID:</label>
                                <div class="text-sm font-medium text-gray-700 px-4">{{ $schoolInfo->school_id }}</div>
                            </div>
                            <div class="flex items-center" style="width: 50rem;padding-left: 8rem;">
                                <label for="credential" class="text-sm font-medium text-gray-700">Address of
                                    School:</label>
                                <div class="text-sm font-medium text-gray-700 px-4">{{ $schoolInfo->address ?? "Caloc-an" }}, {{ $schoolInfo->district }}, {{ $schoolInfo->division }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Scholastic Record -->
                    <div class="border border-gray-300">
                        <h2 class="border border-black bg-gray-400 text-center font-bold">SCHOLASTIC RECORD</h2>
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-x-6">
                            {{-- year level 1 or grade 1 --}}
                            <!-- Grades Table -->
                            <div class="overflow-x-auto">
                                <!-- School Information Section -->
                                <div class="p-2  border border-gray-800">
                                    <div class="grid grid-cols-1 gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">School:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold w-24 ml-4">School ID:</span>
                                            <span
                                                class="border-b border-gray-800 w-32">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">District:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                            <span class="font-semibold ml-1">Division:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1 w-48">&nbsp;</span>
                                            <span class="font-semibold ml-4">Region:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Classified as Grade:</span>
                                            <span
                                                class="border-b border-gray-800 w-16 text-center">&nbsp;</span>
                                            <span class="font-semibold ml-4">Section:</span>
                                            <span
                                                class="border-b border-gray-800 w-40">&nbsp;</span>
                                            <span class="font-semibold ml-4">School Year:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Name of Adviser/Teacher:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold ml-4">Signature:</span>
                                            <span class="border-b border-gray-800 w-48">&nbsp;</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Grades Table -->
                                <div class="border-1 border-gray-800 overflow-hidden">
                                    <table class="w-full">
                                        <thead class="">
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-left" rowspan="2">
                                                    Learning
                                                    Areas</th>
                                                <th class="border border-gray-800 p-2 text-center" colspan="4">
                                                    Quarter</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Final
                                                    Rating</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Remarks</th>
                                            </tr>
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-center">1</th>
                                                <th class="border border-gray-800 p-2 text-center">2</th>
                                                <th class="border border-gray-800 p-2 text-center">3</th>
                                                <th class="border border-gray-800 p-2 text-center">4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (['Mother Tongue', 'Filipino', 'English', 'Mathematics', 'Science', 'Araling Panlipunan', 'EEP / TLE', 'MAPEH', 'Music', 'Arts', 'Physical Education', 'Health', 'Eduk. sa Pagpapakatao', '*Arabic Language', '*Islamic Values Education'] as $subject)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">{{ $subject }}</td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-bold">
                                                <td class="border border-gray-800 p-2 text-center">
                                                    General Average</td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Remedial Classes -->
                                <div class="mt-4 border-1 border-gray-800 overflow-hidden">
                                    <div class="p-2 border-b-2 border-gray-800">
                                        <span class="font-bold">Remedial Classes</span>
                                        <span class="ml-4">Date Conducted: _____________ to _____________</span>
                                    </div>
                                    <table class="w-full">
                                        <thead>
                                            <tr>
                                                <th class="border border-gray-800 p-2">Learning Areas</th>
                                                <th class="border border-gray-800 p-2">Final Rating</th>
                                                <th class="border border-gray-800 p-2">Remedial Class Mark</th>
                                                <th class="border border-gray-800 p-2">Recomputed Final Grade</th>
                                                <th class="border border-gray-800 p-2">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 3; $i++)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- year level 2 or grade 2 --}}
                            <!-- Grades Table -->
                            <div class="overflow-x-auto">
                                <!-- School Information Section -->
                                <div class="p-2  border border-gray-800">
                                    <div class="grid grid-cols-1 gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">School:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold w-24 ml-4">School ID:</span>
                                            <span
                                                class="border-b border-gray-800 w-32">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">District:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                            <span class="font-semibold ml-1">Division:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1 w-48">&nbsp;</span>
                                            <span class="font-semibold ml-4">Region:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Classified as Grade:</span>
                                            <span
                                                class="border-b border-gray-800 w-16 text-center">&nbsp;</span>
                                            <span class="font-semibold ml-4">Section:</span>
                                            <span
                                                class="border-b border-gray-800 w-40">&nbsp;</span>
                                            <span class="font-semibold ml-4">School Year:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Name of Adviser/Teacher:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold ml-4">Signature:</span>
                                            <span class="border-b border-gray-800 w-48">&nbsp;</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Grades Table -->
                                <div class="border-1 border-gray-800 overflow-hidden">
                                    <table class="w-full">
                                        <thead class="">
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-left" rowspan="2">
                                                    Learning
                                                    Areas</th>
                                                <th class="border border-gray-800 p-2 text-center" colspan="4">
                                                    Quarter</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Final
                                                    Rating</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Remarks</th>
                                            </tr>
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-center">1</th>
                                                <th class="border border-gray-800 p-2 text-center">2</th>
                                                <th class="border border-gray-800 p-2 text-center">3</th>
                                                <th class="border border-gray-800 p-2 text-center">4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (['Mother Tongue', 'Filipino', 'English', 'Mathematics', 'Science', 'Araling Panlipunan', 'EEP / TLE', 'MAPEH', 'Music', 'Arts', 'Physical Education', 'Health', 'Eduk. sa Pagpapakatao', '*Arabic Language', '*Islamic Values Education'] as $subject)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">{{ $subject }}</td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-bold">
                                                <td class="border border-gray-800 p-2 text-center">
                                                    General Average</td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Remedial Classes -->
                                <div class="mt-4 border-1 border-gray-800 overflow-hidden">
                                    <div class="p-2 border-b-2 border-gray-800">
                                        <span class="font-bold">Remedial Classes</span>
                                        <span class="ml-4">Date Conducted: _____________ to _____________</span>
                                    </div>
                                    <table class="w-full">
                                        <thead>
                                            <tr>
                                                <th class="border border-gray-800 p-2">Learning Areas</th>
                                                <th class="border border-gray-800 p-2">Final Rating</th>
                                                <th class="border border-gray-800 p-2">Remedial Class Mark</th>
                                                <th class="border border-gray-800 p-2">Recomputed Final Grade</th>
                                                <th class="border border-gray-800 p-2">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 3; $i++)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- year level 3 or grade 3 --}}
                            <!-- Grades Table -->
                            <div class="overflow-x-auto mt-4">
                                <!-- School Information Section -->
                                <div class="p-2  border border-gray-800">
                                    <div class="grid grid-cols-1 gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">School:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold w-24 ml-4">School ID:</span>
                                            <span
                                                class="border-b border-gray-800 w-32">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">District:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                            <span class="font-semibold ml-1">Division:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1 w-48">&nbsp;</span>
                                            <span class="font-semibold ml-4">Region:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Classified as Grade:</span>
                                            <span
                                                class="border-b border-gray-800 w-16 text-center">&nbsp;</span>
                                            <span class="font-semibold ml-4">Section:</span>
                                            <span
                                                class="border-b border-gray-800 w-40">&nbsp;</span>
                                            <span class="font-semibold ml-4">School Year:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Name of Adviser/Teacher:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold ml-4">Signature:</span>
                                            <span class="border-b border-gray-800 w-48">&nbsp;</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Grades Table -->
                                <div class="border-1 border-gray-800 overflow-hidden">
                                    <table class="w-full">
                                        <thead class="">
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-left" rowspan="2">
                                                    Learning
                                                    Areas</th>
                                                <th class="border border-gray-800 p-2 text-center" colspan="4">
                                                    Quarter</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Final
                                                    Rating</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Remarks</th>
                                            </tr>
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-center">1</th>
                                                <th class="border border-gray-800 p-2 text-center">2</th>
                                                <th class="border border-gray-800 p-2 text-center">3</th>
                                                <th class="border border-gray-800 p-2 text-center">4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (['Mother Tongue', 'Filipino', 'English', 'Mathematics', 'Science', 'Araling Panlipunan', 'EEP / TLE', 'MAPEH', 'Music', 'Arts', 'Physical Education', 'Health', 'Eduk. sa Pagpapakatao', '*Arabic Language', '*Islamic Values Education'] as $subject)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">{{ $subject }}</td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-bold">
                                                <td class="border border-gray-800 p-2 text-center">
                                                    General Average</td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Remedial Classes -->
                                <div class="mt-4 border-1 border-gray-800 overflow-hidden">
                                    <div class="p-2 border-b-2 border-gray-800">
                                        <span class="font-bold">Remedial Classes</span>
                                        <span class="ml-4">Date Conducted: _____________ to _____________</span>
                                    </div>
                                    <table class="w-full">
                                        <thead>
                                            <tr>
                                                <th class="border border-gray-800 p-2">Learning Areas</th>
                                                <th class="border border-gray-800 p-2">Final Rating</th>
                                                <th class="border border-gray-800 p-2">Remedial Class Mark</th>
                                                <th class="border border-gray-800 p-2">Recomputed Final Grade</th>
                                                <th class="border border-gray-800 p-2">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 3; $i++)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            {{-- year level 4 or grade 4 --}}
                            <!-- Grades Table -->
                            <div class="overflow-x-auto mt-4">
                                <!-- School Information Section -->
                                <div class="p-2  border border-gray-800">
                                    <div class="grid grid-cols-1 gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">School:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold w-24 ml-4">School ID:</span>
                                            <span
                                                class="border-b border-gray-800 w-32">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">District:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                            <span class="font-semibold ml-1">Division:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1 w-48">&nbsp;</span>
                                            <span class="font-semibold ml-4">Region:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Classified as Grade:</span>
                                            <span
                                                class="border-b border-gray-800 w-16 text-center">&nbsp;</span>
                                            <span class="font-semibold ml-4">Section:</span>
                                            <span
                                                class="border-b border-gray-800 w-40">&nbsp;</span>
                                            <span class="font-semibold ml-4">School Year:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Name of Adviser/Teacher:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold ml-4">Signature:</span>
                                            <span class="border-b border-gray-800 w-48">&nbsp;</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Grades Table -->
                                <div class="border-1 border-gray-800 overflow-hidden">
                                    <table class="w-full">
                                        <thead class="">
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-left" rowspan="2">
                                                    Learning
                                                    Areas</th>
                                                <th class="border border-gray-800 p-2 text-center" colspan="4">
                                                    Quarter</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Final
                                                    Rating</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Remarks</th>
                                            </tr>
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-center">1</th>
                                                <th class="border border-gray-800 p-2 text-center">2</th>
                                                <th class="border border-gray-800 p-2 text-center">3</th>
                                                <th class="border border-gray-800 p-2 text-center">4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (['Mother Tongue', 'Filipino', 'English', 'Mathematics', 'Science', 'Araling Panlipunan', 'EEP / TLE', 'MAPEH', 'Music', 'Arts', 'Physical Education', 'Health', 'Eduk. sa Pagpapakatao', '*Arabic Language', '*Islamic Values Education'] as $subject)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">{{ $subject }}</td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-bold">
                                                <td class="border border-gray-800 p-2 text-center">
                                                    General Average</td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Remedial Classes -->
                                <div class="mt-4 border-1 border-gray-800 overflow-hidden">
                                    <div class="p-2 border-b-2 border-gray-800">
                                        <span class="font-bold">Remedial Classes</span>
                                        <span class="ml-4">Date Conducted: _____________ to _____________</span>
                                    </div>
                                    <table class="w-full">
                                        <thead>
                                            <tr>
                                                <th class="border border-gray-800 p-2">Learning Areas</th>
                                                <th class="border border-gray-800 p-2">Final Rating</th>
                                                <th class="border border-gray-800 p-2">Remedial Class Mark</th>
                                                <th class="border border-gray-800 p-2">Recomputed Final Grade</th>
                                                <th class="border border-gray-800 p-2">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 3; $i++)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <p class="text-right">SFRT 2017</p>
                </div>
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
                            {{-- year level 5 or grade 5 --}}
                            <!-- Grades Table -->
                            <div class="overflow-x-auto">
                                <!-- School Information Section -->
                                <div class="p-2  border border-gray-800">
                                    <div class="grid grid-cols-1 gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">School:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold w-24 ml-4">School ID:</span>
                                            <span
                                                class="border-b border-gray-800 w-32">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">District:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                            <span class="font-semibold ml-1">Division:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1 w-48">&nbsp;</span>
                                            <span class="font-semibold ml-4">Region:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Classified as Grade:</span>
                                            <span
                                                class="border-b border-gray-800 w-16 text-center">&nbsp;</span>
                                            <span class="font-semibold ml-4">Section:</span>
                                            <span
                                                class="border-b border-gray-800 w-40">&nbsp;</span>
                                            <span class="font-semibold ml-4">School Year:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Name of Adviser/Teacher:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold ml-4">Signature:</span>
                                            <span class="border-b border-gray-800 w-48">&nbsp;</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Grades Table -->
                                <div class="border-1 border-gray-800 overflow-hidden">
                                    <table class="w-full">
                                        <thead class="">
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-left" rowspan="2">
                                                    Learning
                                                    Areas</th>
                                                <th class="border border-gray-800 p-2 text-center" colspan="4">
                                                    Quarter</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Final
                                                    Rating</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Remarks</th>
                                            </tr>
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-center">1</th>
                                                <th class="border border-gray-800 p-2 text-center">2</th>
                                                <th class="border border-gray-800 p-2 text-center">3</th>
                                                <th class="border border-gray-800 p-2 text-center">4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (['Mother Tongue', 'Filipino', 'English', 'Mathematics', 'Science', 'Araling Panlipunan', 'EEP / TLE', 'MAPEH', 'Music', 'Arts', 'Physical Education', 'Health', 'Eduk. sa Pagpapakatao', '*Arabic Language', '*Islamic Values Education'] as $subject)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">{{ $subject }}</td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-bold">
                                                <td class="border border-gray-800 p-2 text-center">
                                                    General Average</td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Remedial Classes -->
                                <div class="mt-4 border-1 border-gray-800 overflow-hidden">
                                    <div class="p-2 border-b-2 border-gray-800">
                                        <span class="font-bold">Remedial Classes</span>
                                        <span class="ml-4">Date Conducted: _____________ to _____________</span>
                                    </div>
                                    <table class="w-full">
                                        <thead>
                                            <tr>
                                                <th class="border border-gray-800 p-2">Learning Areas</th>
                                                <th class="border border-gray-800 p-2">Final Rating</th>
                                                <th class="border border-gray-800 p-2">Remedial Class Mark</th>
                                                <th class="border border-gray-800 p-2">Recomputed Final Grade</th>
                                                <th class="border border-gray-800 p-2">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 3; $i++)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- year level 6 or grade 6 --}}
                            <!-- Grades Table -->
                            <div class="overflow-x-auto">
                                <!-- School Information Section -->
                                <div class="p-2  border border-gray-800">
                                    <div class="grid grid-cols-1 gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">School:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold w-24 ml-4">School ID:</span>
                                            <span
                                                class="border-b border-gray-800 w-32">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">District:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                            <span class="font-semibold ml-1">Division:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1 w-48">&nbsp;</span>
                                            <span class="font-semibold ml-4">Region:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Classified as Grade:</span>
                                            <span
                                                class="border-b border-gray-800 w-16 text-center">&nbsp;</span>
                                            <span class="font-semibold ml-4">Section:</span>
                                            <span
                                                class="border-b border-gray-800 w-40">&nbsp;</span>
                                            <span class="font-semibold ml-4">School Year:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Name of Adviser/Teacher:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold ml-4">Signature:</span>
                                            <span class="border-b border-gray-800 w-48">&nbsp;</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Grades Table -->
                                <div class="border-1 border-gray-800 overflow-hidden">
                                    <table class="w-full">
                                        <thead class="">
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-left" rowspan="2">
                                                    Learning
                                                    Areas</th>
                                                <th class="border border-gray-800 p-2 text-center" colspan="4">
                                                    Quarter</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Final
                                                    Rating</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Remarks</th>
                                            </tr>
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-center">1</th>
                                                <th class="border border-gray-800 p-2 text-center">2</th>
                                                <th class="border border-gray-800 p-2 text-center">3</th>
                                                <th class="border border-gray-800 p-2 text-center">4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (['Mother Tongue', 'Filipino', 'English', 'Mathematics', 'Science', 'Araling Panlipunan', 'EEP / TLE', 'MAPEH', 'Music', 'Arts', 'Physical Education', 'Health', 'Eduk. sa Pagpapakatao', '*Arabic Language', '*Islamic Values Education'] as $subject)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">{{ $subject }}</td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-bold">
                                                <td class="border border-gray-800 p-2 text-center">
                                                    General Average</td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Remedial Classes -->
                                <div class="mt-4 border-1 border-gray-800 overflow-hidden">
                                    <div class="p-2 border-b-2 border-gray-800">
                                        <span class="font-bold">Remedial Classes</span>
                                        <span class="ml-4">Date Conducted: _____________ to _____________</span>
                                    </div>
                                    <table class="w-full">
                                        <thead>
                                            <tr>
                                                <th class="border border-gray-800 p-2">Learning Areas</th>
                                                <th class="border border-gray-800 p-2">Final Rating</th>
                                                <th class="border border-gray-800 p-2">Remedial Class Mark</th>
                                                <th class="border border-gray-800 p-2">Recomputed Final Grade</th>
                                                <th class="border border-gray-800 p-2">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 3; $i++)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- year level 7 or grade 7 --}}
                            <!-- Grades Table -->
                            <div class="overflow-x-auto mt-4">
                                <!-- School Information Section -->
                                <div class="p-2  border border-gray-800">
                                    <div class="grid grid-cols-1 gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">School:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold w-24 ml-4">School ID:</span>
                                            <span
                                                class="border-b border-gray-800 w-32">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">District:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                            <span class="font-semibold ml-1">Division:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1 w-48">&nbsp;</span>
                                            <span class="font-semibold ml-4">Region:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Classified as Grade:</span>
                                            <span
                                                class="border-b border-gray-800 w-16 text-center">&nbsp;</span>
                                            <span class="font-semibold ml-4">Section:</span>
                                            <span
                                                class="border-b border-gray-800 w-40">&nbsp;</span>
                                            <span class="font-semibold ml-4">School Year:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Name of Adviser/Teacher:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold ml-4">Signature:</span>
                                            <span class="border-b border-gray-800 w-48">&nbsp;</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Grades Table -->
                                <div class="border-1 border-gray-800 overflow-hidden">
                                    <table class="w-full">
                                        <thead class="">
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-left" rowspan="2">
                                                    Learning
                                                    Areas</th>
                                                <th class="border border-gray-800 p-2 text-center" colspan="4">
                                                    Quarter</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Final
                                                    Rating</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Remarks</th>
                                            </tr>
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-center">1</th>
                                                <th class="border border-gray-800 p-2 text-center">2</th>
                                                <th class="border border-gray-800 p-2 text-center">3</th>
                                                <th class="border border-gray-800 p-2 text-center">4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (['Mother Tongue', 'Filipino', 'English', 'Mathematics', 'Science', 'Araling Panlipunan', 'EEP / TLE', 'MAPEH', 'Music', 'Arts', 'Physical Education', 'Health', 'Eduk. sa Pagpapakatao', '*Arabic Language', '*Islamic Values Education'] as $subject)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">{{ $subject }}</td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-bold">
                                                <td class="border border-gray-800 p-2 text-center">
                                                    General Average</td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Remedial Classes -->
                                <div class="mt-4 border-1 border-gray-800 overflow-hidden">
                                    <div class="p-2 border-b-2 border-gray-800">
                                        <span class="font-bold">Remedial Classes</span>
                                        <span class="ml-4">Date Conducted: _____________ to _____________</span>
                                    </div>
                                    <table class="w-full">
                                        <thead>
                                            <tr>
                                                <th class="border border-gray-800 p-2">Learning Areas</th>
                                                <th class="border border-gray-800 p-2">Final Rating</th>
                                                <th class="border border-gray-800 p-2">Remedial Class Mark</th>
                                                <th class="border border-gray-800 p-2">Recomputed Final Grade</th>
                                                <th class="border border-gray-800 p-2">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 3; $i++)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- year level 8 or grade 8 --}}
                            <!-- Grades Table -->
                            <div class="overflow-x-auto mt-4">
                                <!-- School Information Section -->
                                <div class="p-2  border border-gray-800">
                                    <div class="grid grid-cols-1 gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">School:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold w-24 ml-4">School ID:</span>
                                            <span
                                                class="border-b border-gray-800 w-32">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-12">District:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                            <span class="font-semibold ml-1">Division:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1 w-48">&nbsp;</span>
                                            <span class="font-semibold ml-4">Region:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Classified as Grade:</span>
                                            <span
                                                class="border-b border-gray-800 w-16 text-center">&nbsp;</span>
                                            <span class="font-semibold ml-4">Section:</span>
                                            <span
                                                class="border-b border-gray-800 w-40">&nbsp;</span>
                                            <span class="font-semibold ml-4">School Year:</span>
                                            <span
                                                class="border-b border-gray-800">&nbsp;</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">Name of Adviser/Teacher:</span>
                                            <span
                                                class="border-b border-gray-800 flex-1">&nbsp;</span>
                                            <span class="font-semibold ml-4">Signature:</span>
                                            <span class="border-b border-gray-800 w-48">&nbsp;</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Grades Table -->
                                <div class="border-1 border-gray-800 overflow-hidden">
                                    <table class="w-full">
                                        <thead class="">
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-left" rowspan="2">
                                                    Learning
                                                    Areas</th>
                                                <th class="border border-gray-800 p-2 text-center" colspan="4">
                                                    Quarter</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Final
                                                    Rating</th>
                                                <th class="border border-gray-800 p-2 text-center" rowspan="2">
                                                    Remarks</th>
                                            </tr>
                                            <tr>
                                                <th class="border border-gray-800 p-2 text-center">1</th>
                                                <th class="border border-gray-800 p-2 text-center">2</th>
                                                <th class="border border-gray-800 p-2 text-center">3</th>
                                                <th class="border border-gray-800 p-2 text-center">4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (['Mother Tongue', 'Filipino', 'English', 'Mathematics', 'Science', 'Araling Panlipunan', 'EEP / TLE', 'MAPEH', 'Music', 'Arts', 'Physical Education', 'Health', 'Eduk. sa Pagpapakatao', '*Arabic Language', '*Islamic Values Education'] as $subject)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">{{ $subject }}</td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                    <td class="border border-gray-800 p-2 text-center"></td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-bold">
                                                <td class="border border-gray-800 p-2 text-center">
                                                    General Average</td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                                <td class="border border-gray-800 p-2 text-center"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Remedial Classes -->
                                <div class="mt-4 border-1 border-gray-800 overflow-hidden">
                                    <div class="p-2 border-b-2 border-gray-800">
                                        <span class="font-bold">Remedial Classes</span>
                                        <span class="ml-4">Date Conducted: _____________ to _____________</span>
                                    </div>
                                    <table class="w-full">
                                        <thead>
                                            <tr>
                                                <th class="border border-gray-800 p-2">Learning Areas</th>
                                                <th class="border border-gray-800 p-2">Final Rating</th>
                                                <th class="border border-gray-800 p-2">Remedial Class Mark</th>
                                                <th class="border border-gray-800 p-2">Recomputed Final Grade</th>
                                                <th class="border border-gray-800 p-2">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 3; $i++)
                                                <tr>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                    <td class="border border-gray-800 p-2">&nbsp;</td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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

    <script src="{{ asset('js/school-forms/print.js') }}"></script>

</x-app-layout>
