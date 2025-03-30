{{-- teacher.school-forms-10.grade6.blade.php --}}
@if (isset($student->year_level_id) && $student->year_level_id === 6)
    <div class="overflow-x-auto">
        <!-- School Information Section -->
        <div class="p-2 border border-gray-800">
            <div class="grid grid-cols-1 gap-1">
                <div class="flex items-center gap-2">
                    <span class="font-semibold w-12">School:</span>
                    <span class="border-b border-gray-800 flex-1">{{ $schoolInfo->school_name }}</span>
                    <span class="font-semibold ml-4">School ID:</span>
                    <span class="border-b border-gray-800 w-32">{{ $schoolInfo->school_id }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="font-semibold w-12">District:</span>
                    <span class="border-b border-gray-800 w-36">{{ $schoolInfo->district }}</span>
                    <span class="font-semibold ml-1">Division:</span>
                    <span class="border-b border-gray-800 w-36">{{ $schoolInfo->division }}</span>
                    <span class="font-semibold ml-4">Region:</span>
                    <span class="border-b border-gray-800 w-24">{{ $schoolInfo->region }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="font-semibold">Classified as Grade:</span>
                    <span class="border-b border-gray-800 w-16 text-center">{{ $student->yearLevel->name }}</span>
                    <span class="font-semibold ml-4">Section:</span>
                    <span class="border-b border-gray-800 w-16">{{ $student->section }}</span>
                    <span class="font-semibold ml-4">School Year:</span>
                    <span class="border-b border-gray-800">{{ $student->schoolYear->name }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="font-semibold">Name of Adviser/Teacher:</span>
                    <span class="border-b border-gray-800 flex-1">{{ $student->user->name }}</span>
                    <span class="font-semibold ml-4">Signature:</span>
                    <span class="border-b border-gray-800 w-48">&nbsp;</span>
                </div>
            </div>
        </div>

        <table class="w-full border-collapse border border-gray-800">
            <thead>
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
                @php
                    $subjects = [
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

                    $mapehSubjects = ['music', 'art', 'physical education', 'health'];
                    $mapehAverages = [];
                    $quarterlyAverages = [];
                    $excludedSubjects = ['music', 'art', 'physical education', 'health'];

                    // Compute MAPEH average per quarter
                    for ($q = 1; $q <= 4; $q++) {
                        $total = 0;
                        $count = 0;

                        foreach ($mapehSubjects as $mapehSubject) {
                            if (isset($grades[$mapehSubject][$q]) && is_numeric($grades[$mapehSubject][$q])) {
                                $total += floatval($grades[$mapehSubject][$q]);
                                $count++;
                            }
                        }

                        $mapehAverages[$q] = $count > 0 ? round($total / $count) : '';
                    }

                    // Compute final MAPEH grade
                    $finalMapehTotal = array_sum(array_filter($mapehAverages, 'is_numeric'));
                    $finalMapehCount = count(array_filter($mapehAverages, 'is_numeric'));
                    $mapehFinal = $finalMapehCount > 0 ? round($finalMapehTotal / $finalMapehCount) : '';

                    // Compute quarterly general averages
                    for ($q = 1; $q <= 4; $q++) {
                        $total = 0;
                        $count = 0;

                        foreach ($grades as $subject => $data) {
                            if (!in_array($subject, $excludedSubjects) && isset($data[$q]) && is_numeric($data[$q])) {
                                $total += floatval($data[$q]);
                                $count++;
                            }
                        }

                        // Include MAPEH in general average
                        if (is_numeric($mapehAverages[$q])) {
                            $total += $mapehAverages[$q];
                            $count++;
                        }

                        $quarterlyAverages[$q] = $count > 0 ? number_format($total / $count, 2) : '';
                    }

                    // Compute final general average
                    $finalTotal = array_sum(array_filter($quarterlyAverages, 'is_numeric'));
                    $finalCount = count(array_filter($quarterlyAverages, 'is_numeric'));
                    $finalGeneralAverage = $finalCount > 0 ? number_format($finalTotal / $finalCount, 2) : '';
                @endphp

                @foreach ($subjects as $subject)
                    @php
                        $subjectKey = strtolower(($subject));
                        $quarters = $grades[$subjectKey] ?? [];
                    @endphp

                    @if ($subject === 'MAPEH')
                        <tr>
                            <td class="border border-gray-800 p-1">&nbsp;&nbsp;&nbsp;MAPEH</td>
                            @for ($q = 1; $q <= 4; $q++)
                                <td class="border border-gray-800 p-1 text-center">{{ $mapehAverages[$q] ?? '' }}</td>
                            @endfor
                            <td class="border border-gray-800 p-1 text-center">{{ $mapehFinal }}</td>
                            <td class="border border-gray-800 p-1 text-center">
                                @if (is_numeric($mapehFinal))
                                    {{ $mapehFinal >= 75 ? 'PASSED' : 'FAILED' }}
                                @endif
                            </td>
                        </tr>
                        @continue {{-- Skip printing MAPEH again below --}}
                    @endif

                    <tr>
                        <td class="border border-gray-800 p-1">&nbsp;&nbsp;&nbsp;{{ $subject }}</td>
                        @for ($q = 1; $q <= 4; $q++)
                            <td class="border border-gray-800 p-1 text-center">{{ $quarters[$q] ?? '' }}</td>
                        @endfor
                        <td class="border border-gray-800 p-1 text-center">{{ $quarters['final'] ?? '' }}</td>
                        <td class="border border-gray-800 p-1 text-center">
                            @if (isset($quarters['final']))
                                {{ $quarters['final'] >= 75 ? 'PASSED' : 'FAILED' }}
                            @endif
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td class="border border-gray-800 p-1 font-bold">&nbsp;&nbsp;&nbsp;General Average</td>
                    @for ($q = 1; $q <= 4; $q++)
                        <td class="border border-gray-800 p-1 text-center font-bold">{{ $quarterlyAverages[$q] }}</td>
                    @endfor
                    <td class="border border-gray-800 p-1 text-center font-bold">{{ $finalGeneralAverage }}</td>
                    <td class="border border-gray-800 p-1 text-center font-bold">
                        @if (is_numeric($finalGeneralAverage))
                            {{ $finalGeneralAverage >= 75 ? 'PROMOTED' : 'REMEDIAL' }}
                        @endif
                    </td>
                </tr>

                <tr>
                    <td class="border border-gray-800 px-4 p-1">Remedial Classes</td>
                    <td colspan="6" class="border border-gray-800 px-2 p-1">Date Conducted: __________________
                        to __________________</td>
                </tr>
                <tr>
                    <th class="border border-gray-800 p-1">Learning Areas</th>
                    <th class="border border-gray-800 p-1">Final Rating</th>
                    <th colspan="2" class="border border-gray-800 p-1">Remedial Class Mark</th>
                    <th colspan="2" class="border border-gray-800 p-1">Recomputed Final Grade</th>
                    <th class="border border-gray-800 p-1">Remarks</th>
                </tr>
                <tr>
                    <td class="border border-gray-800 p-1">&nbsp;</td>
                    <td class="border border-gray-800 p-1"></td>
                    <td colspan="2" class="border border-gray-800 p-1"></td>
                    <td colspan="2" class="border border-gray-800 p-1"></td>
                    <td class="border border-gray-800 p-1"></td>
                </tr>
                <tr>
                    <td class="border border-gray-800 p-1">&nbsp;</td>
                    <td class="border border-gray-800 p-1"></td>
                    <td colspan="2" class="border border-gray-800 p-1"></td>
                    <td colspan="2" class="border border-gray-800 p-1"></td>
                    <td class="border border-gray-800 p-1"></td>
                </tr>
            </tbody>
        </table>
    </div>
@else
    {{-- year level 1 or grade 1 --}}
    <!-- Grades Table -->
    <div class="overflow-x-auto">
        <!-- School Information Section -->
        <div class="p-2  border border-gray-800">
            <div class="grid grid-cols-1 gap-1">
                <div class="flex items-center gap-2">
                    <span class="font-semibold w-12">School:</span>
                    <span class="border-b border-gray-800 flex-1">&nbsp;</span>
                    <span class="font-semibold w-24 ml-4">School ID:</span>
                    <span class="border-b border-gray-800 w-32">&nbsp;</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="font-semibold w-12">District:</span>
                    <span class="border-b border-gray-800">&nbsp;</span>
                    <span class="font-semibold ml-1">Division:</span>
                    <span class="border-b border-gray-800 flex-1 w-48">&nbsp;</span>
                    <span class="font-semibold ml-4">Region:</span>
                    <span class="border-b border-gray-800">&nbsp;</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="font-semibold">Classified as Grade:</span>
                    <span class="border-b border-gray-800 w-16 text-center">&nbsp;</span>
                    <span class="font-semibold ml-4">Section:</span>
                    <span class="border-b border-gray-800 w-40">&nbsp;</span>
                    <span class="font-semibold ml-4">School Year:</span>
                    <span class="border-b border-gray-800">&nbsp;</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="font-semibold">Name of Adviser/Teacher:</span>
                    <span class="border-b border-gray-800 flex-1">&nbsp;</span>
                    <span class="font-semibold ml-4">Signature:</span>
                    <span class="border-b border-gray-800 w-48">&nbsp;</span>
                </div>
            </div>
        </div>
        <!-- Grades Table -->
        <div class="border-1 border-gray-800 overflow-hidden">
            <table class="w-full">
                <thead>
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
                            <td class="border border-gray-800 p-1">{{ $subject }}</td>
                            <td class="border border-gray-800 p-1 text-center"></td>
                            <td class="border border-gray-800 p-1 text-center"></td>
                            <td class="border border-gray-800 p-1 text-center"></td>
                            <td class="border border-gray-800 p-1 text-center"></td>
                            <td class="border border-gray-800 p-1 text-center"></td>
                            <td class="border border-gray-800 p-1 text-center"></td>
                        </tr>
                    @endforeach
                    <tr class="font-bold">
                        <td class="border border-gray-800 p-1 text-left">
                            General Average</td>
                        <td class="border border-gray-800 p-1 text-center"></td>
                        <td class="border border-gray-800 p-1 text-center"></td>
                        <td class="border border-gray-800 p-1 text-center"></td>
                        <td class="border border-gray-800 p-1 text-center"></td>
                        <td class="border border-gray-800 p-1 text-center"></td>
                        <td class="border border-gray-800 p-1 text-center"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Remedial Classes -->
        <div class="border-1 border-gray-800 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr>
                        <td colspan="2" class="border border-gray-800 p-1">Remedial Classes</td>
                        <td colspan="3" class="border border-gray-800 p-1">Date Conducted: ___________________ to
                            ___________________</td>
                    </tr>
                    <tr>
                        <th class="border border-gray-800 p-1">Learning Areas</th>
                        <th class="border border-gray-800 p-1">Final Rating</th>
                        <th class="border border-gray-800 p-1">Remedial Class Mark</th>
                        <th class="border border-gray-800 p-1">Recomputed Final Grade</th>
                        <th class="border border-gray-800 p-1">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 2; $i++)
                        <tr>
                            <td class="border border-gray-800 p-1">&nbsp;</td>
                            <td class="border border-gray-800 p-1">&nbsp;</td>
                            <td class="border border-gray-800 p-1">&nbsp;</td>
                            <td class="border border-gray-800 p-1">&nbsp;</td>
                            <td class="border border-gray-800 p-1">&nbsp;</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
@endif
