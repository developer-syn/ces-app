<x-app-layout>
    <x-slot name="header">
        <div class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student SF09 Report Card') }}
        </div>
    </x-slot>
    <style>
        /* Print-specific styles */
        @media print {
            body * {
                visibility: hidden;
            }

            #reportCardContainer,
            #reportCardContainer * {
                visibility: visible;
            }

            #reportCardContainer {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .page {
                width: 29.7cm;
                min-height: 21cm;
                margin: 0 auto;
                background: white;
            }

            @page {
                size: A4 landscape;
                margin: 0.5cm;
            }

            .no-print {
                display: none !important;
            }
        }

        /* Regular document styles */
        #reportCardContainer {
            background: white;
            font-family: Arial, sans-serif;
        }

        .page {
            /* border: 1px solid #ddd; */
            margin-bottom: 20px;
            padding-left: 80px;
            padding-right: 80px;
            width: 29.7cm;
            min-height: 21cm;
            margin: 0 auto 20px auto;
            background: white;
        }

        /* Basic reset */
        .page * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
            font-size: 13px;
        }

        /* Front page specific styles */
        .front-container {
            display: flex;
            flex-wrap: wrap;
            max-width: 100%;
            margin: 0 auto;
        }

        .column-left {
            flex: 1 1 50%;
            min-width: 280px;
            padding-right: 20px;

        }

        .column-right {
            flex: 1 1 50%;
            min-width: 280px;
            padding-left: 20px;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 20px 0;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
            height: 30px;
        }

        table td:first-child {
            text-align: left;
            font-weight: normal;
            padding-left: 10px;
        }

        /* Headings and spacing */
        h2 {
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 15px;
        }

        h3 {
            margin: 15px 0 10px 0;
            font-size: 14px;
            font-weight: bold;
        }

        /* Parent signature section */
        .parent-signature {
            margin-top: 30px;
            text-align: center;
        }

        .parent-signature h3 {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .signature-line {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            text-align: left;
        }

        .signature-line span {
            width: 80px;
            font-weight: normal;
            text-align: left;
        }

        .signature-line .line {
            flex: 1;
            padding-top: 10px;
            border-bottom: 1px solid #000;
        }

        /* School header */
        .school-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .school-header img {
            width: 60px;
            height: 60px;
            margin-right: 15px;
        }

        .school-header-text {
            text-align: center;
        }

        .school-header-text h2 {
            margin: 0;
            line-height: 1.3;
        }

        /* School details */
        .school-details {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 10px 0;
        }

        .school-detail {
            display: flex;
            align-items: center;
            /* margin-bottom: 5px; */
            width: 48%;
        }

        .school-detail .label {
            margin-right: 5px;
        }

        .school-detail .line {
            flex: 1;
            /* padding-top: 10px; */
            border-bottom: 1px solid #000;
        }

        /* Report card header */
        .report-card-header {
            text-align: center;
            /* margin: 15px 0; */
        }

        .report-card-header h3 {
            /* margin-bottom: 5px; */
        }

        /* Student info */
        .student-info {
            margin: 15px 0;
        }

        .student-info-row {
            display: flex;
            margin-bottom: 5px;
            align-items: center;
        }

        .student-info-row .label {
            min-width: 50px;
        }

        .student-info-row .line {
            flex: 1;
            /* padding-top: 10px; */
            border-bottom: 1px solid #000;
        }

        .student-info-row .short-label {
            min-width: 40px;
            margin-left: 15px;
        }

        /* Back page specific styles */
        .back-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 0 auto;
            max-width: 100%;
        }

        .progress-column {
            width: 48%;
        }

        .values-column {
            width: 48%;
        }

        .ratings-container {
            display: flex;
            margin-top: 20px;
        }

        .ratings-section {
            width: 50%;
        }

        .ratings-section h4 {
            margin-bottom: 5px;
            text-align: center;
        }

        .ratings-table {
            width: 90%;
            margin: 0 auto;
        }

        .ratings-table td {
            border: none;
            text-align: left;
            padding: 2px 5px;
        }

        /* Signature section */
        .signature-section {
            /* margin: 10px 0; */
        }

        .signature-box-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .signature-box-left {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: center;
        }

        .signature-box-left p {
            margin-left: 30px;
        }

        .signature-box-right p {
            margin-right: 80px;
        }

        .signature-box-right .line {
            width: 200px;
            /* padding-top: 30px; */
            border-bottom: 1px solid #000;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .signature-box-left .line {
            width: 200px;
            padding-top: 10px;
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
        }

        /* Certificate section */
        .certificate {
            /* margin-top: 15px; */
            /* border-top: 1px solid #ccc; */
            /* padding-top: 15px; */
        }

        .certificate h3 {
            text-align: center;
            margin-top: 5px;
        }

        .certificate-row {
            display: flex;
            /* margin-bottom: 10px; */
            align-items: center;
        }

        .certificate-row .label {
            margin-right: 10px;
        }

        .certificate-row .line {
            flex: 1;
            padding-top: 10px;

            border-bottom: 1px solid #000;
        }

        .certificate-row .label-right {
            margin: 0 10px;
        }

        .certificate-signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
        }

        .cert-signature {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 45%;
        }

        .cert-signature .line {
            width: 100%;
            padding-top: 10px;

            border-bottom: 1px solid #000;
            margin-bottom: 5px;
        }

        .cancellation {
            margin-top: 20px;
        }

        .cancellation h4 {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
    <div class="py-4">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between">
                        {{-- <h2 class="font-bold text-xl mb-4">Student Report Card (SF09)</h2> --}}
                        <button onclick="printReportCard()"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-print mr-2"></i> Print Report Card
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
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>No. of Days Present</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>No. of Times Absent</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
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
                                            <div class="line">&nbsp;&nbsp;&nbsp;{{ $schoolInfo->first()->region }}</div>
                                        </div>
                                        <div class="school-detail">
                                            <span class="label">Division</span>
                                            <div class="line">&nbsp;&nbsp;&nbsp;{{ $schoolInfo->first()->division }}</div>
                                        </div>
                                        <div class="school-detail">
                                            <span class="label">District</span>
                                            <div class="line">&nbsp;&nbsp;{{ $schoolInfo->first()->district }}</div>
                                        </div>
                                        <div class="school-detail">
                                            <span class="label">School</span>
                                            <div class="line">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $schoolInfo->first()->school_name }}</div>
                                        </div>
                                    </div>

                                    <div class="report-card-header">
                                        <h3>LEARNER'S PROGRESS REPORT CARD</h3>
                                        <p>School Year: {{ $student->schoolYear->name ?? date('Y') . '-' . (date('Y') + 1) }}</p>
                                    </div>

                                    <div class="student-info">
                                        <div class="student-info-row">
                                            <span class="label">Name:</span>
                                            <div class="line">&nbsp;&nbsp;&nbsp;{{ $student->name ?? '' }}</div>
                                        </div>
                                        <div class="student-info-row">
                                            <span class="label">Age:</span>
                                            <div class="line" style="flex: 0.3;">&nbsp;&nbsp;&nbsp;{{ $student->age ?? '' }}</div>
                                            <span class="short-label">Sex:</span>
                                            <div class="line" style="flex: 0.7;">&nbsp;&nbsp;&nbsp;{{ $student->gender ?? '' }}</div>
                                        </div>
                                        <div class="student-info-row">
                                            <span class="label">Grade:</span>
                                            <div class="line"
                                                style="flex: 0.2; font-family:'Times New Roman', Times, serif; font-weight:800;">
                                                &nbsp;&nbsp;&nbsp;{{ $student->yearLevel->name ?? '' }}
                                            </div>
                                            <span class="short-label">Section:</span>
                                            <div class="line" style="flex: 0.3;">&nbsp;&nbsp;&nbsp;{{ $student->section ?? '' }}</div>
                                            <span class="short-label">LRN:</span>
                                            <div class="line" style="flex: 0.5;">&nbsp;&nbsp;&nbsp;{{ $student->LRN_num }}</div>
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
                                            <div class="line"></div>
                                            <p>Teacher</p>
                                        </div>
                                        <div class="signature-box-left">
                                            <div class="line"></div>
                                            <p>Head Teacher/ Principal</p>
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
                                GRADE {{ $student->yearLevel->name ?? 'GRADE -' }}</h2>
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
                                                $mapehSubjects = ['music', 'art', 'physical education', 'health'];
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
                                    <h3
                                        style="text-align: center; margin-bottom: 15px; font-size: 14px; text-transform: uppercase;">
                                        REPORT ON LEARNER'S OBSERVED VALUES</h3>
                                    <table>
                                        <thead>
                                            <tr style="background: #eef3ff;">
                                                <th rowspan="2" style="width: 25%; text-align: center;">Core Values
                                                </th>
                                                <th rowspan="2" style="width: 30%; text-align: center;">Behavior
                                                    Statements</th>
                                                <th colspan="4" style="width: 40%; text-align: center;">Quarter
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
                                            <tr>
                                                <td style="padding: 5px;">1. Maka-Diyos</td>
                                                <td style="padding: 5px; font-size: 12px;">Expresses one's spiritual
                                                    beliefs while respecting the spiritual beliefs of others</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">2. Makatao</td>
                                                <td style="padding: 5px; font-size: 12px;">Shows adherence to ethical
                                                    principles by upholding truth</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">3. Maka-kalikasan</td>
                                                <td style="padding: 5px; font-size: 12px;">Cares for the environment
                                                    and utilizes resources wisely, judiciously, and economically</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px; vertical-align: top;" rowspan="2">4.
                                                    Makabansa</td>
                                                <td style="padding: 5px; font-size: 12px;">Demonstrates pride in being
                                                    a Filipino; exercises the rights and responsibilities of a Filipino
                                                    citizen</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
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
                                            <h4
                                                style="margin-bottom: 10px; text-align: center; font-weight:800; font-size: 12px">
                                                Marking</h4>
                                            <table class="ratings-table">
                                                <tr>
                                                    <td style="width: 30%; border: none; text-align: left;">AO</td>
                                                    <td style="width: 70%; border: none; text-align: left;">Always
                                                        Observed</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none; text-align: left;">SO</td>
                                                    <td style="border: none; text-align: left;">Sometimes Observed</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none; text-align: left;">RO</td>
                                                    <td style="border: none; text-align: left;">Rarely Observed</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none; text-align: left;">NO</td>
                                                    <td style="border: none; text-align: left;">Not Observed</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="ratings-section">
                                            <h4
                                                style="margin-bottom: 10px; text-align: center; font-weight:800; font-size: 12px">
                                                Non-numerical Rating</h4>
                                            <table class="ratings-table">
                                                <tr>
                                                    <td style="width: 30%; border: none; text-align: left;">AO</td>
                                                    <td style="width: 70%; border: none; text-align: left;">Always
                                                        Observed</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none; text-align: left;">SO</td>
                                                    <td style="border: none; text-align: left;">Sometimes Observed</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none; text-align: left;">RO</td>
                                                    <td style="border: none; text-align: left;">Rarely Observed</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none; text-align: left;">NO</td>
                                                    <td style="border: none; text-align: left;">Not Observed</td>
                                                </tr>
                                            </table>
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
