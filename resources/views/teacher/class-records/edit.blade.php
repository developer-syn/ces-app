<x-app-layout>
    <style>
        .dashboard {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
        }
        .header {
            display: flex;
            /* horizontal layout */
            align-items: center;
            /* vertical centering */
            justify-content: space-between;
            padding: 0 20px;
            /* adjust horizontal padding as needed */
            width: 100%;
            /* ensure it spans full width */
            box-sizing: border-box;
            /* so padding doesn’t overflow */
        }

        .left {
            /* no flex-grow; it will just size to its content */
            position: absolute;
            top: 120px;
        }

        .center {
            flex: 1;
            /* this grows/shrinks to fill middle space */
            text-align: center;
        }

        .right {
            /* no flex-grow; it will just size to its content */
            margin-left: auto;
            position: absolute;

            /* ensures it’s pushed to the far right */
            top: 120px;
            right: 85px;
        }

        .deped-logo {
            width: 120px;
            /* adjust as desired */
            height: auto;
            position: relative;
        }

        .deped-text {
            width: 200px;
            /* adjust as desired */
            height: auto;
            position: relative;
        }

        .title {
            margin: 0;
            font-size: 24px;
            /* adjust as desired */
        }

        .subtitle {
            margin: 0;
            font-size: 14px;
            /* adjust as desired */
        }


        .form-container {
            margin-bottom: 20px;
            padding-left: 150px;
            padding-right: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 10px;
        }

        .form-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group label {
            font-weight: bold;
            white-space: nowrap;
        }

        .table-head input {
            border: none;
            background-color: #f1f4ff;
        }

        .school-name {
            flex: 2;
        }

        .school-name input {
            width: 100%;
        }

        .grade-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 20px;
        }

        .grade-table th,
        .grade-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .grade-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .grade-table td input {
            width: 100%;
            border: none;
            padding: 0;
            text-align: center;
            font-size: inherit;
            background: transparent;
        }

        .grade-table td {
            padding: 0;
            /* Ensures consistent spacing */
        }


        .name-column {
            min-width: 200px;
            text-align: left !important;
        }

        .header-row th {
            background-color: #f2f2f2;
        }

        .subheader-row th {
            background-color: #f8f8f8;
        }

        .highest-score-row {
            background-color: #f2f2f2;
        }

        .gender-row {
            background-color: #e6e6e6;
        }

        .gender-row td:first-child {
            font-weight: bold;
        }

        .head-row .table-head {
            font-weight: bold;
            font-size: 12px;
            padding: 1px;
        }

        .head-row .table-head input {
            font-weight: bold;
            font-size: 12px;
        }

        @media print {
            body {
                padding: 0;
            }

            .dashboard {
                max-width: none;
            }
        }
    </style>
    <section class="home p-6">
        <div class="dashboard">
            <header class="header">
                <div class="left">
                    <img src="{{ asset('img/depedLogo.png') }}" alt="DepEd Logo" class="deped-logo">
                </div>
                <div class="center">
                    <h1 class="title">Class Record</h1>
                    <div class="subtitle">(Pursuant to DepEd Order 8 s. of 2015)</div>
                </div>
                <div class="right">
                    <img src="{{ asset('img/deped.png') }}" alt="DepEd Text" class="deped-text">
                </div>
            </header>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.class-records.update', $classRecord) }}">
                @csrf
                @method('PUT')

                <!-- Hidden field for teacher/user -->
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <input type="hidden" name="subject" value="{{ $classRecord->subject_id }}">
                <input type="hidden" name="grade_section" value="{{ $classRecord->grade_section }}">
                <input type="hidden" name="quarter" value="{{ $classRecord->quarter }}">
                <input type="hidden" name="school_year" value="{{ $classRecord->school_year }}">
                <input type="hidden" name="teacher" value="{{ $classRecord->teacher }}">


                <div class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label>REGION:</label>
                            <input type="text" value="{{ $schoolInfo->first()->region }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>DIVISION:</label>
                            <input type="text" value="{{ $schoolInfo->first()->division }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>DISTRICT:</label>
                            <input type="text" value="{{ $schoolInfo->first()->district }}" disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group school-name">
                            <label>SCHOOL NAME:</label>
                            <input type="text" value="{{ $schoolInfo->first()->school_name }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>SCHOOL ID:</label>
                            <input type="text" value="{{ $schoolInfo->first()->school_id }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>SCHOOL YEAR:</label>
                            <select name="school_year" disabled @selected(true)>
                                @foreach ($schoolYear as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Global Class Record (Header) Table -->
                <table class="grade-table w-full border-collapse mb-6">
                    <tr class="head-row bg-gray-100">
                        <th class="table-head" colspan="2">
                            <select name="quarter" class="border-none bg-blue-50 text-center text-sm" disabled>
                                <option value="" disabled>--Select Quarter--</option>
                                @foreach ($quarter as $q)
                                    <option value="{{ $q->name }}"
                                        {{ $classRecord->quarter == $q->name ? 'selected' : '' }}>
                                        {{ $q->name }}
                                    </option>
                                @endforeach
                            </select>
                        </th>
                        <th class="table-head" colspan="5">GRADE & SECTION:</th>
                        <th class="table-head" colspan="6">
                            <input type="text" name="grade_section" value="{{ $classRecord->grade_section }}"
                                placeholder="GRADE & SECTION" class="w-full border px-2 py-1 bg-blue-50 text-center" disabled>
                        </th>
                        <th class="table-head" colspan="2">TEACHER:</th>
                        <th class="table-head" colspan="10">
                            <input type="text" name="teacher" value="{{ $classRecord->teacher }}"
                                placeholder="TEACHER" class="w-full border px-2 py-1 bg-blue-50 text-center" disabled>
                        </th>
                        <th class="table-head" colspan="4">SUBJECT:</th>
                        <th class="table-head" colspan="4">
                            <select name="subject" class="border-none bg-blue-50 text-center text-sm" disabled>
                                <option value="" disabled>--Select Subject--</option>
                                @foreach ($subject as $subj)
                                    <option value="{{ $subj->name }}"
                                        {{ $classRecord->subject_id == $subj->name ? 'selected' : '' }}>
                                        {{ $subj->name }}
                                    </option>
                                @endforeach
                            </select>
                        </th>
                    </tr>
                    <tr class="header-row bg-gray-200">
                        <th rowspan="1"></th>
                        <th rowspan="1" class="name-column">LEARNERS' NAMES</th>
                        <th colspan="13">WRITTEN WORKS (30%)</th>
                        <th colspan="13">PERFORMANCE TASKS (50%)</th>
                        <th colspan="3">QUARTERLY ASSESSMENT (20%)</th>
                        <th rowspan="3">Initial Grade</th>
                        <th rowspan="3">Quarterly Grade</th>
                    </tr>
                    <tr class="subheader-row bg-gray-200">
                        <th></th>
                        <th></th>
                        @for ($i = 1; $i <= 10; $i++)
                            <th>{{ $i }}</th>
                        @endfor
                        <th>Total</th>
                        <th>PS</th>
                        <th>WS</th>
                        @for ($i = 1; $i <= 10; $i++)
                            <th>{{ $i }}</th>
                        @endfor
                        <th>Total</th>
                        <th>PS</th>
                        <th>WS</th>
                        <th>1</th>
                        <th>PS</th>
                        <th>WS</th>
                    </tr>
                    <!-- Global header row for highest possible scores -->
                    <tr>
                        <td></td>
                        <td>HIGHEST POSSIBLE SCORE</td>
                        <!-- Written Works Global -->
                        @for ($i = 1; $i <= 10; $i++)
                            <td>
                                <input type="number" name="hww[]" id="hww{{ $i }}"
                                    value="{{ old('hww.' . ($i - 1), $classRecord->hww[$i - 1] ?? '') }}"
                                    oninput="calculateGrades()">
                            </td>
                        @endfor
                        <td id="hwwTotal">00</td>
                        <td>100</td>
                        <td>30%</td>
                        <!-- Performance Tasks Global -->
                        @for ($i = 1; $i <= 10; $i++)
                            <td>
                                <input type="number" name="hpt[]" id="hpt{{ $i }}"
                                    value="{{ old('hpt.' . ($i - 1), $classRecord->hpt[$i - 1] ?? '') }}"
                                    oninput="calculateGrades()">
                            </td>
                        @endfor
                        <td id="hptTotal">00</td>
                        <td>100</td>
                        <td>50%</td>
                        <!-- Quarterly Assessment Global -->
                        <td>
                            <input type="number" name="global_hqa" id="hqa"
                                value="{{ old('global_hqa', $classRecord->global_hqa) }}"
                                oninput="calculateGrades()">
                        </td>
                        <td>100.00</td>
                        <td>20%</td>
                    </tr>


                    <!-- Detail rows for each student -->
                    @php $index = 0; @endphp
                    @foreach ($groupRecords as $detail)
                        @php $index++; @endphp
                        <tr>
                            <td>{{ $index }}</td>
                            <td>{{ $detail->student->name }}</td>
                            <input type="hidden" name="student_id[]" value="{{ $detail->student_id }}">
                            <!-- Written Works for this student -->
                            @php
                                // Define the helper function once
                                $formatScore = function($score) {
                                    if ($score === null || $score == 0) {
                                        return '';
                                    }
                                    // Format to 2 decimal places, then remove trailing zeros and the decimal point if needed.
                                    return rtrim(rtrim(sprintf('%.2f', $score), '0'), '.');
                                };
                            @endphp

                            @for ($i = 1; $i <= 10; $i++)
                                @php
                                    // Retrieve the stored or old score value (using 0-based indexing)
                                    $rawScore = old("written_works.$detail->student_id." . ($i - 1), $detail->{'written_work_' . $i});
                                    // Format the score using our helper function
                                    $displayValue = $formatScore($rawScore);
                                @endphp

                                <td>
                                    <input type="number"
                                        name="written_works[{{ $detail->student_id }}][]"
                                        id="ww{{ $index }}_{{ $i }}"
                                        value="{{ $displayValue }}"
                                        oninput="calculateGrades({{ $index }})">
                                </td>
                            @endfor
                            <td id="wwTotal{{ $index }}">{{ $detail->written_works_total }}</td>
                            <td id="wwPS{{ $index }}">{{ $detail->written_works_ps }}</td>
                            <td id="wwWS{{ $index }}">{{ $detail->written_works_ws }}</td>
                            <!-- Performance Tasks for this student -->
                            @for ($i = 1; $i <= 10; $i++)
                                @php
                                    // Retrieve the stored or old value for performance task i
                                    $rawScore = old("performance_tasks.$detail->student_id." . ($i - 1), $detail->{'performance_task_' . $i});
                                    // Format the score: blank if 0 or null, otherwise trimmed version (e.g., "11.5" instead of "11.50")
                                    $displayValue = $formatScore($rawScore);
                                @endphp
                                <td>
                                    <input type="number"
                                        name="performance_tasks[{{ $detail->student_id }}][]"
                                        id="pt{{ $index }}_{{ $i }}"
                                        value="{{ $displayValue }}"
                                        oninput="calculateGrades({{ $index }})">
                                </td>
                            @endfor
                            <td id="ptTotal{{ $index }}">{{ $detail->performance_tasks_total }}</td>
                            <td id="ptPS{{ $index }}">{{ $detail->performance_tasks_ps }}</td>
                            <td id="ptWS{{ $index }}">{{ $detail->performance_tasks_ws }}</td>
                            <!-- Quarterly Assessment for this student -->
                            <td>
                                <input type="number" name="quarterly_assessment[{{ $detail->student_id }}]"
                                    id="qa{{ $index }}"
                                    value="{{ old("quarterly_assessment.$detail->student_id", $detail->quarterly_assessment) }}"
                                    oninput="calculateGrades({{ $index }})">
                            </td>
                            <td id="qaPS{{ $index }}">{{ $detail->quarterly_assessment_ps }}</td>
                            <td id="qaWS{{ $index }}">{{ $detail->quarterly_assessment_ws }}</td>
                            <td>
                                <input type="text" name="initial_grade[{{ $detail->student_id }}]"
                                    id="initialGrade{{ $index }}"
                                    value="{{ old("initial_grade.$detail->student_id", $detail->initial_grade) }}"
                                    readonly>
                            </td>
                            <td>
                                <input type="text" name="quarterly_grade[{{ $detail->student_id }}]"
                                    id="quarterlyGrade{{ $index }}"
                                    value="{{ old("quarterly_grade.$detail->student_id", $detail->quarterly_grade) }}"
                                    readonly>
                            </td>
                        </tr>
                    @endforeach
                </table>

                <button type="submit" class="btn btn-primary">Update Class Record</button>
            </form>
        </div>

        <script>
            function updateGlobalTotals() {
                let hwwTotal = 0,
                    hptTotal = 0;
                for (let i = 1; i <= 10; i++) {
                    hwwTotal += parseFloat(document.getElementById(`hww${i}`).value) || 0;
                    hptTotal += parseFloat(document.getElementById(`hpt${i}`).value) || 0;
                }
                document.getElementById("hwwTotal").textContent = hwwTotal;
                document.getElementById("hptTotal").textContent = hptTotal;
            }

            function calculateGrades(studentIndex) {
                updateGlobalTotals();
                let wwTotal = 0,
                    ptTotal = 0,
                    qaTotal = 0;
                let globalHwwTotal = parseFloat(document.getElementById("hwwTotal").textContent) || 0;
                let globalHptTotal = parseFloat(document.getElementById("hptTotal").textContent) || 0;
                let hqaTotal = parseFloat(document.getElementById("hqa").value) || 0;

                for (let i = 1; i <= 10; i++) {
                    wwTotal += parseFloat(document.getElementById(`ww${studentIndex}_${i}`).value) || 0;
                }
                document.getElementById(`wwTotal${studentIndex}`).textContent = wwTotal;

                for (let i = 1; i <= 10; i++) {
                    ptTotal += parseFloat(document.getElementById(`pt${studentIndex}_${i}`).value) || 0;
                }
                document.getElementById(`ptTotal${studentIndex}`).textContent = ptTotal;

                qaTotal = parseFloat(document.getElementById(`qa${studentIndex}`).value) || 0;
                let qaPS = hqaTotal ? (qaTotal / hqaTotal) * 100 : 0;
                let qaWS = qaPS * 0.20;
                document.getElementById(`qaPS${studentIndex}`).textContent = qaPS.toFixed(2);
                document.getElementById(`qaWS${studentIndex}`).textContent = qaWS.toFixed(2);

                let wwPS = globalHwwTotal ? (wwTotal / globalHwwTotal) * 100 : 0;
                let wwWS = wwPS * 0.30;
                document.getElementById(`wwPS${studentIndex}`).textContent = wwPS.toFixed(2);
                document.getElementById(`wwWS${studentIndex}`).textContent = wwWS.toFixed(2);

                let ptPS = globalHptTotal ? (ptTotal / globalHptTotal) * 100 : 0;
                let ptWS = ptPS * 0.50;
                document.getElementById(`ptPS${studentIndex}`).textContent = ptPS.toFixed(2);
                document.getElementById(`ptWS${studentIndex}`).textContent = ptWS.toFixed(2);

                let initialGrade = wwWS + ptWS + qaWS;
                document.getElementById(`initialGrade${studentIndex}`).value = initialGrade.toFixed(2);
                document.getElementById(`quarterlyGrade${studentIndex}`).value = Math.round(initialGrade);
            }
        </script>
        </div>
    </section>
</x-app-layout>
