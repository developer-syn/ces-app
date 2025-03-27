<!-- Grades Table -->
<div class="overflow-x-auto">
    <!-- School Information Section -->
    <div class="p-2  border border-gray-800">
        <div class="grid grid-cols-1 gap-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold w-12">School:</span>
                <span class="border-b border-gray-800 flex-1">{{ $schoolInfo->school_name}}</span>
                <span class="font-semibold ml-4">School ID:</span>
                <span class="border-b border-gray-800 w-32">{{ $schoolInfo->school_id}}</span>
            </div>

            <div class="flex items-center gap-2">
                <span class="font-semibold w-12">District:</span>
                <span class="border-b border-gray-800 w-36">{{ $schoolInfo->district}}</span>
                <span class="font-semibold ml-1">Division:</span>
                <span class="border-b border-gray-800 w-36">{{ $schoolInfo->division}}</span>
                <span class="font-semibold ml-4">Region:</span>
                <span class="border-b border-gray-800 w-24">{{ $schoolInfo->region }}</span>
            </div>

            <div class="flex items-center gap-2">
                <span class="font-semibold">Classified as Grade:</span>
                <span class="border-b border-gray-800 w-16 text-center">{{ $student->yearLevel->name}}</span>
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
        <thead class="">
            <tr>
                <th class="border border-gray-800 text-center" rowspan="2">Learning
                    Areas</th>
                <th class="border border-gray-800 text-center" colspan="4">
                    Quarter</th>
                <th class="border border-gray-800 text-center" rowspan="2">Final
                    Rating</th>
                <th class="border border-gray-800 text-center" rowspan="2">
                    Remarks</th>
            </tr>
            <tr>
                <th class="border border-gray-800 text-center">1</th>
                <th class="border border-gray-800 text-center">2</th>
                <th class="border border-gray-800 text-center">3</th>
                <th class="border border-gray-800 text-center">4</th>
            </tr>
        </thead>
        <tbody>
            @php
                $mapehSubjects = ['music', 'art', 'physical education', 'health'];
            @endphp

            @foreach ($grades as $subject => $quarters)
                @if ($subject === 'music')
                    @php
                        $mapehAverages = [];
                        for ($q = 1; $q <= 4; $q++) {
                            $quarterTotal = 0;
                            $quarterCount = 0;

                            foreach ($mapehSubjects as $mapehSubject) {
                                if (isset($grades[$mapehSubject][$q]) && is_numeric($grades[$mapehSubject][$q])) {
                                    $quarterTotal += floatval($grades[$mapehSubject][$q]);
                                    $quarterCount++;
                                }
                            }

                            $mapehAverages[$q] = $quarterCount > 0 ? round($quarterTotal / $quarterCount) : '';
                        }

                        // Calculate final MAPEH grade
                        $finalTotal = 0;
                        $finalCount = 0;

                        foreach ($mapehAverages as $average) {
                            if (is_numeric($average)) {
                                $finalTotal += floatval($average);
                                $finalCount++;
                            }
                        }

                        $mapehFinal = $finalCount > 0 ? round($finalTotal / $finalCount) : '';
                    @endphp
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;MAPEH</td>
                        <td style="text-align: center;">{{ $mapehAverages[1] ?? '' }}</td>
                        <td style="text-align: center;">{{ $mapehAverages[2] ?? '' }}</td>
                        <td style="text-align: center;">{{ $mapehAverages[3] ?? '' }}</td>
                        <td style="text-align: center;">{{ $mapehAverages[4] ?? '' }}</td>
                        <td style="text-align: center;">{{ $mapehFinal }}</td>
                        <td style="text-align: center;">
                            @if ($mapehFinal)
                                {{ $mapehFinal >= 75 ? 'Passed' : 'Failed' }}
                            @endif
                        </td>
                    </tr>
                @endif

                <tr>
                    <td>&nbsp;&nbsp;&nbsp;{{ ucfirst($subject) }}</td>
                    <td style="text-align: center;">{{ $quarters['1'] ?? '' }}</td>
                    <td style="text-align: center;">{{ $quarters['2'] ?? '' }}</td>
                    <td style="text-align: center;">{{ $quarters['3'] ?? '' }}</td>
                    <td style="text-align: center;">{{ $quarters['4'] ?? '' }}</td>
                    <td style="text-align: center;">{{ $quarters['final'] ?? '' }}</td>
                    <td style="text-align: center;">
                        @if (isset($quarters['final']))
                            {{ $quarters['final'] >= 75 ? 'Passed' : 'Failed' }}
                        @endif
                    </td>
                </tr>
            @endforeach

            @php
                $generalTotal = 0;
                $subjectCount = 0;
                $excludedSubjects = ['music', 'art', 'physical education', 'health'];

                if (isset($grades) && is_array($grades)) {
                    foreach ($grades as $subject => $data) {
                        if (
                            !in_array($subject, $excludedSubjects) &&
                            isset($data['final']) &&
                            is_numeric($data['final'])
                        ) {
                            $generalTotal += $data['final'];
                            $subjectCount++;
                        }
                    }

                    // Include MAPEH final grade in the general average
                    if (isset($mapehFinal) && is_numeric($mapehFinal)) {
                        $generalTotal += $mapehFinal;
                        $subjectCount++;
                    }
                }

                $generalAverage = $subjectCount > 0 ? number_format($generalTotal / $subjectCount) : 'N/A';
            @endphp
            <tr>
                <td style="border: none;"></td>
                <td colspan="4" style="font-weight: bold; text-align: center;">General Average</td>
                <td style="font-weight: bold; text-align: center;">{{ $generalAverage }}</td>
            </tr>
            <tr>
                <td class=" border border-gray-800 px-4">Remedial Classes</td>
                <td colspan="6" class=" border border-gray-800 px-2">Date Conducted: ____________ to ___________
                </td>
            </tr>
            <tr>
                <th class=" border border-gray-800">Learning Areas</th>
                <th class=" border border-gray-800">Final Rating</th>
                <th colspan="2" class=" border border-gray-800">Remedial Class Mark</th>
                <th colspan="2" class=" border border-gray-800">Recomputed Final Grade</th>
                <th class=" border border-gray-800">Remarks</th>
            </tr>
            <tr>
                <td class=" border border-gray-800">&nbsp;</td>
                <td class=" border border-gray-800"></td>
                <td colspan="2" class=" border border-gray-800">
                </td>
                <td colspan="2" class=" border border-gray-800">
                </td>
                <td class=" border border-gray-800"></td>
            </tr>
            <tr>
                <td class=" border border-gray-800">&nbsp;</td>
                <td class=" border border-gray-800"></td>
                <td colspan="2" class=" border border-gray-800">
                </td>
                <td colspan="2" class=" border border-gray-800">
                </td>
                <td class=" border border-gray-800"></td>
            </tr>
        </tbody>
    </table>
</div>
