<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/student/sf09.css') }}">
    <x-slot name="header">
        <div class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student SF09 Report Card') }}
        </div>
    </x-slot>

    <div class="py-4">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-wrap justify-between gap-4">
                        <!-- Go Back Button -->
                        <a href="{{ route('teacher.students.index') }}"
                            class="inline-flex items-center px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-all duration-200 ease-in-out transform hover:scale-[1.02] shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Return to Previous Page
                        </a>

                        <!-- Print Button -->
                        <button onclick="printReportCard()"
                                class="inline-flex items-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-all duration-200 ease-in-out transform hover:scale-[1.02] shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Generate Printable Report
                        </button>
                    </div>



                    <!-- Report Card Container -->
                    <div id="reportCardContainer">
                        <!-- Front Page -->
                        <div class="page">
                            <div class="front-container">
                                <!-- Left Column -->
                                <section class="column-left">
                                    <h2>Attendance Record</h2>
                                    <table>
                                        <thead>
                                            <tr style="background: #eef3ff;">
                                                <th></th>
                                                <th>Jun</th>
                                                <th>Jul</th>
                                                <th>Aug</th>
                                                <th>Sept</th>
                                                <th>Oct</th>
                                                <th>Nov</th>
                                                <th>Dec</th>
                                                <th>Jan</th>
                                                <th>Feb</th>
                                                <th>Mar</th>
                                                <th>Apr</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>No. of School Days</td>
                                                @foreach(['jun', 'jul', 'aug', 'sept', 'oct', 'nov', 'dec', 'jan', 'feb', 'mar', 'apr'] as $month)
                                                <td>{{ $attendance->{$month.'_days'} ?? 0 }}</td>
                                                @endforeach
                                                <td>{{ $attendance->total_days ?? 0 }}</td>
                                            </tr>
                                            <tr>
                                                <td>No. of Days Present</td>
                                                @foreach(['jun', 'jul', 'aug', 'sept', 'oct', 'nov', 'dec', 'jan', 'feb', 'mar', 'apr'] as $month)
                                                <td>{{ $attendance->{$month.'_present'} ?? 0 }}</td>
                                                @endforeach
                                                <td>{{ $attendance->total_present ?? 0 }}</td>
                                            </tr>
                                            <tr>
                                                <td>No. of Times Absent</td>
                                                @foreach(['jun', 'jul', 'aug', 'sept', 'oct', 'nov', 'dec', 'jan', 'feb', 'mar', 'apr'] as $month)
                                                <td>{{ ($attendance->{$month.'_days'} ?? 0) - ($attendance->{$month.'_present'} ?? 0) }}</td>
                                                @endforeach
                                                <td>{{ ($attendance->total_days ?? 0) - ($attendance->total_present ?? 0) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="parent-signature">
                                        <h3>Parent/Guardian's Signature</h3>
                                        <div class="signature-line">
                                            <span>1<sup>st</sup> Quarter</span>
                                            <div class="line"></div>
                                        </div>
                                        <div class="signature-line">
                                            <span>2<sup>nd</sup> Quarter</span>
                                            <div class="line"></div>
                                        </div>
                                        <div class="signature-line">
                                            <span>3<sup>rd</sup> Quarter</span>
                                            <div class="line"></div>
                                        </div>
                                        <div class="signature-line">
                                            <span>4<sup>th</sup> Quarter</span>
                                            <div class="line"></div>
                                        </div>
                                    </div>
                                </section>

                                <!-- Right Column -->
                                <section class="column-right">
                                    <div class="school-header">
                                        <img src="{{ asset('img/Seal_of_the_Department_of_Education_of_the_Philippines.png') }}" alt="DepEd Logo" class="deped-logo" style="border-radius: 50%;">
                                        <div class="school-header-text">
                                            <h2>Republic of the Philippines</h2>
                                            <h2>DEPARTMENT OF EDUCATION</h2>
                                        </div>
                                    </div>

                                    <div class="school-details">
                                        <div class="school-detail">
                                            <span class="label">Region</span>
                                            <div class="line">&nbsp;&nbsp;&nbsp;{{ $enrollment->student->schoolInfo->region ?? 'N/A' }}</div>
                                        </div>
                                        <div class="school-detail">
                                            <span class="label">Division</span>
                                            <div class="line">&nbsp;&nbsp;&nbsp;{{ $student->schoolInfo->division ?? 'N/A' }}</div>
                                        </div>
                                        <div class="school-detail">
                                            <span class="label">District</span>
                                            <div class="line">&nbsp;&nbsp;{{ $student->schoolInfo->district ?? 'N/A' }}</div>
                                        </div>
                                        <div class="school-detail">
                                            <span class="label">School</span>
                                            <div class="line">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $student->schoolInfo->school_name ?? 'N/A' }}</div>
                                        </div>
                                    </div>

                                    <div class="report-card-header">
                                        <h3>LEARNER'S PROGRESS REPORT CARD</h3>
                                        <p>School Year: {{ $enrollment->schoolYear->name }}</p>
                                    </div>

                                    <div class="student-info">
                                        <div class="student-info-row">
                                            <span class="label">Name:</span>
                                            <div class="line">&nbsp;&nbsp;&nbsp;{{ ucwords(strtolower($student->lastname)) }}, {{ ucwords(strtolower($student->firstname)) }} {{ ucwords(strtolower($student->middlename)) }}</div>
                                        </div>
                                        <div class="student-info-row">
                                            <span class="label">Age:</span>
                                            <div class="line" style="flex: 0.3;">&nbsp;&nbsp;&nbsp;{{ $enrollment->age ?? '' }}</div>
                                            <span class="short-label">Sex:</span>
                                            <div class="line" style="flex: 0.7;">&nbsp;&nbsp;&nbsp;{{ $student->gender ?? '' }}</div>
                                        </div>
                                        <div class="student-info-row">
                                            <span class="label">Grade:</span>
                                            <div class="line"
                                                style="flex: 0.2; font-family:'Times New Roman', Times, serif; font-weight:800;">
                                                &nbsp;&nbsp;&nbsp;{{ $enrollment->yearLevel->name ?? '' }}
                                            </div>
                                            <span class="short-label">Section:</span>
                                            <div class="line" style="flex: 0.3;">&nbsp;&nbsp;&nbsp;{{ $enrollment->section ?? $student->section }}</div>
                                            <span class="short-label">LRN:</span>
                                            <div class="line" style="flex: 0.5;">&nbsp;&nbsp;&nbsp;{{ $enrollment->student->LRN_num }}</div>
                                        </div>
                                    </div>

                                    <div class="parent-message">
                                        <p><i>Dear Parent,</i></p>
                                        <p style="padding-left: 50px;"><i>This report card shows the ability and the
                                                progress your child has made in</p>
                                        <p> the different learning areas as well as
                                            his/her progress in core values.</i></p>
                                        <p style="padding-left: 50px;"><i>The school welcomes you should you desire to
                                                know more about your child's</p>
                                        <p> progress.</i></p>
                                    </div>

                                    <div class="signature-section">
                                        <div class="signature-box-right">
                                            <div class="line text-center">{{ $enrollment->teacher->name }}</div>
                                            <p>Teacher</p>
                                        </div>
                                        <div class="signature-box-left">
                                            <div class="line text-center">{{ $student->schoolInfo->principal_name ?? '-' }}</div>
                                            <p style="padding-left: 50px;">Principal</p>
                                        </div>
                                    </div>

                                    <div class="certificate">
                                        <h3>Certificate of Transfer</h3>
                                        <div class="certificate-row">
                                            <span class="label">Admitted to Grade</span>
                                            <div class="line"></div>
                                            <span class="label-right">Section</span>
                                            <div class="line" style="flex: 0.4;"></div>
                                            <span class="label">Room</span>
                                            <div class="line"></div>
                                        </div>

                                        <div class="certificate-row">
                                            <span class="label">Eligible for Admission to Grade</span>
                                            <div class="line"></div>
                                        </div>
                                        <div class="certificate-row">
                                            <span class="label">Approved:</span>
                                        </div>

                                        <div class="certificate-signatures">
                                            <div class="cert-signature">
                                                <div class="line" style="flex: 0.4;"></div>
                                                <p>Head Teacher/ Principal</p>
                                            </div>
                                            <div class="cert-signature">
                                                <div class="line" style="flex: 0.4;"></div>
                                                <p>Teacher</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="cancellation">
                                        <h4>Cancellation of Eligibility to Transfer</h4>
                                        <div class="certificate-row">
                                            <span class="label">Admitted in</span>
                                            <div class="line"></div>
                                        </div>
                                        <div class="certificate-row">
                                            <span class="label">Date:</span>
                                            <div class="line"></div>
                                        </div>

                                        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                                            <div class="cert-signature" style="width: 40%;">
                                                <div class="line"></div>
                                                <p>Principal</p>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>

                        <!-- Back Page -->
                        <div class="page">
                            <h2 style="font-weight: bold; text-align: left;">
                                GRADE {{ $enrollment->yearLevel->name ?? 'GRADE -' }}</h2>
                            <div class="back-container mt-4">
                                <!-- Learning Progress and Achievement Section -->
                                    <div class="progress-column">
                                    <h3
                                        style="text-align: center; margin-bottom: 15px; font-size: 14px; text-transform: uppercase;">
                                        REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</h3>
                                    <table>
                                        <thead>
                                            <tr style="background: #eef3ff;">
                                                <th rowspan="2"
                                                    style="width: 30%; text-align: center; padding-left: 5px;">Learning
                                                    Areas</th>
                                                <th colspan="4" style="width: 40%; text-align: center;">Quarter
                                                </th>
                                                <th rowspan="2" style="width: 15%; text-align: center;">Final
                                                    Rating</th>
                                                <th rowspan="2" style="width: 15%; text-align: center;">Remarks
                                                </th>
                                            </tr>
                                            <tr style="background: #eef3ff;">
                                                <th style="text-align: center;">1</th>
                                                <th style="text-align: center;">2</th>
                                                <th style="text-align: center;">3</th>
                                                <th style="text-align: center;">4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $mapehSubjects = ['music', 'arts', 'physical education', 'health'];
                                            @endphp

                                            @foreach($grades as $subject => $quarters)
                                                @if($subject === 'music')
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
                                                        <td>MAPEH</td>
                                                        <td style="text-align: center;">{{ $mapehAverages[1] ?? '' }}</td>
                                                        <td style="text-align: center;">{{ $mapehAverages[2] ?? '' }}</td>
                                                        <td style="text-align: center;">{{ $mapehAverages[3] ?? '' }}</td>
                                                        <td style="text-align: center;">{{ $mapehAverages[4] ?? '' }}</td>
                                                        <td style="text-align: center;">{{ $mapehFinal }}</td>
                                                        <td style="text-align: center;">
                                                            @if($mapehFinal)
                                                                {{ $mapehFinal >= 75 ? 'Passed' : 'Failed' }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif

                                                <tr>
                                                    <td>{{ ucfirst($subject) }}</td>
                                                    <td style="text-align: center;">{{ $quarters['1'] ?? '' }}</td>
                                                    <td style="text-align: center;">{{ $quarters['2'] ?? '' }}</td>
                                                    <td style="text-align: center;">{{ $quarters['3'] ?? '' }}</td>
                                                    <td style="text-align: center;">{{ $quarters['4'] ?? '' }}</td>
                                                    <td style="text-align: center;">{{ $quarters['final'] ?? '' }}</td>
                                                    <td style="text-align: center;">
                                                        @if(isset($quarters['final']))
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
                                                        if (!in_array($subject, $excludedSubjects) && isset($data['final']) && is_numeric($data['final'])) {
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
                                        </tbody>
                                    </table>
                                    <div style="margin-top: 10px;">
                                        <div style="padding: 5px; display: flex;">
                                            <div style="width: 40%; text-align: left; padding-left: 5px;">Descriptors</div>
                                            <div style="width: 30%; text-align: center;">Grading Scale</div>
                                            <div style="width: 30%; text-align: center;">Remarks</div>
                                        </div>
                                        <div style="padding: 5px; display: flex;">
                                            <div style="width: 40%; padding-left: 5px;">Outstanding</div>
                                            <div style="width: 30%; text-align: center;">90-100</div>
                                            <div style="width: 30%; text-align: center;">Passed</div>
                                        </div>
                                        <div style="padding: 5px; display: flex;">
                                            <div style="width: 40%; padding-left: 5px;">Very Satisfactory</div>
                                            <div style="width: 30%; text-align: center;">85-89</div>
                                            <div style="width: 30%; text-align: center;">Passed</div>
                                        </div>
                                        <div style="padding: 5px; display: flex;">
                                            <div style="width: 40%; padding-left: 5px;">Satisfactory</div>
                                            <div style="width: 30%; text-align: center;">80-84</div>
                                            <div style="width: 30%; text-align: center;">Passed</div>
                                        </div>
                                        <div style="padding: 5px; display: flex;">
                                            <div style="width: 40%; padding-left: 5px;">Fairly Satisfactory</div>
                                            <div style="width: 30%; text-align: center;">75-79</div>
                                            <div style="width: 30%; text-align: center;">Passed</div>
                                        </div>
                                        <div style="padding: 5px; display: flex;">
                                            <div style="width: 40%; padding-left: 5px;">Did Not Meet Expectations</div>
                                            <div style="width: 30%; text-align: center;">Below 75</div>
                                            <div style="width: 30%; text-align: center;">Failed</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Learner's Observed Values Section -->
                                <div class="values-column">
                                    <h3 style="text-align: center; margin-bottom: 15px; font-size: 14px; text-transform: uppercase;">
                                        REPORT ON LEARNER'S OBSERVED VALUES
                                    </h3>
                                    <table>
                                        <thead>
                                            <tr style="background: #eef3ff;">
                                                <th rowspan="2" style="width: 25%; text-align: center;">Core Values</th>
                                                <th rowspan="2" style="width: 30%; text-align: center;">Behavior Statements</th>
                                                <th colspan="4" style="width: 40%; text-align: center;">Quarter</th>
                                            </tr>
                                            <tr style="background: #eef3ff;">
                                                <th style="text-align: center;">1</th>
                                                <th style="text-align: center;">2</th>
                                                <th style="text-align: center;">3</th>
                                                <th style="text-align: center;">4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($coreValues as $key => $value)
                                            <tr>
                                                <td style="padding: 5px;">{{ $loop->iteration }}. {{ ucfirst(str_replace('_', '-', $key)) }}</td>
                                                <td style="padding: 5px; font-size: 12px;">{{ $value['statement'] }}</td>
                                                @foreach(['q1', 'q2', 'q3', 'q4'] as $quarter)
                                                <td style="text-align: center;">{{ $value['quarters'][$quarter] ?? '' }}</td>
                                                @endforeach
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td></td>
                                                <td style="padding: 5px; font-size: 12px;">Demonstrates appropriate
                                                    behavior in carrying out activities in the school, community, and
                                                    country</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="ratings-container">
                                        <div class="ratings-section">
                                            <div class="grid grid-cols-2">
                                                <h4 style="margin-bottom: 10px; text-align: center; font-weight:800; font-size: 12px">
                                                    Marking
                                                </h4>
                                                <h4 style="margin-bottom: 10px; text-align: center; font-weight:800; font-size: 12px">
                                                    Non-numerical Rating
                                                </h4>
                                            </div>
                                            <div class="grid grid-cols-2">
                                                <p style="margin-bottom: 10px; text-align: center; font-size: 12px">AO</p>
                                                <p style="margin-bottom: 10px; text-align: center; font-size: 12px">Always Observed</p>
                                            </div>
                                            <div class="grid grid-cols-2">
                                                <p style="margin-bottom: 10px; text-align: center; font-size: 12px">SO</p>
                                                <p style="margin-bottom: 10px; text-align: center; font-size: 12px">Sometimes Observed</p>
                                            </div>
                                            <div class="grid grid-cols-2">
                                                <p style="margin-bottom: 10px; text-align: center; font-size: 12px">RO</p>
                                                <p style="margin-bottom: 10px; text-align: center; font-size: 12px">Rarely Observed</p>
                                            </div>
                                            <div class="grid grid-cols-2">
                                                <p style="margin-bottom: 10px; text-align: center; font-size: 12px">NO</p>
                                                <p style="margin-bottom: 10px; text-align: center; font-size: 12px">Not Observed</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printReportCard() {
            window.print();
        }
    </script>
</x-app-layout>
