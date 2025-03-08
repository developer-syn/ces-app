<x-app-layout>
    <style>
        .dashboard {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            width: 100%;
            box-sizing: border-box;
        }
        .left {
            /* Sizes to its content */
        }
        .center {
            flex: 1;
            text-align: center;
        }
        .right {
            margin-left: auto;
        }
        .deped-logo {
            width: 80px;
            height: auto;
        }
        .deped-text {
            width: 200px;
            height: auto;
        }
        .title {
            margin: 0;
            font-size: 24px;
        }
        .subtitle {
            margin: 0;
            font-size: 14px;
        }
        .form-container {
            margin-bottom: 20px;
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
            <!-- Display School Info -->
            <div class="form-container mb-6">
                <div class="form-row flex flex-wrap gap-4">
                    <div class="form-group">
                        <label class="block font-semibold">REGION:</label>
                        <input type="text" value="{{ $schoolInfo->first()->region }}" disabled class="border px-2 py-1">
                    </div>
                    <div class="form-group">
                        <label class="block font-semibold">DIVISION:</label>
                        <input type="text" value="{{ $schoolInfo->first()->division }}" disabled class="border px-2 py-1">
                    </div>
                    <div class="form-group">
                        <label class="block font-semibold">DISTRICT:</label>
                        <input type="text" value="{{ $schoolInfo->first()->district }}" disabled class="border px-2 py-1">
                    </div>
                </div>

                <div class="form-row flex flex-wrap gap-4 mt-4">
                    <div class="form-group">
                        <label class="block font-semibold">SCHOOL NAME:</label>
                        <input type="text" value="{{ $schoolInfo->first()->school_name }}" disabled class="border px-2 py-1">
                    </div>
                    <div class="form-group">
                        <label class="block font-semibold">SCHOOL ID:</label>
                        <input type="text" value="{{ $schoolInfo->first()->school_id }}" disabled class="border px-2 py-1">
                    </div>
                    <div class="form-group">
                        <!-- School Year Dropdown (GET form) -->
                        <form method="GET" action="{{ route('teacher.class-records.show', $schoolYear->id) }}">
                            <div class="form-group">
                                <label class="block font-semibold">SCHOOL YEAR:</label>
                                <select name="school_year" onchange="this.form.submit()" class="border rounded px-2 py-1">
                                    <option value="">-- Select School Year --</option>
                                    @foreach ($schoolYear as $year)
                                        <option value="{{ $year->id }}"
                                            {{ isset($selectedYear) && $selectedYear == $year->id ? 'selected' : '' }}>
                                            {{ $year->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Only display details if a school year is selected -->
            @if (isset($selectedYear) && $selectedYear)
                <div class="overflow-x-auto">
                    <table class="grade-table w-full border-collapse mb-6">
                        <tr class="head-row bg-gray-100">
                            <th class="table-head" colspan="2">
                                <select name="quarter" disabled class="border-none bg-blue-50 text-center text-sm">
                                    <option value="" disabled>--Select Quarter--</option>
                                    @foreach ($quarter as $q)
                                        <option value="{{ $q->name }}"
                                            {{ (request('quarter') == $q->name) ? 'selected' : '' }}>
                                            {{ $q->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </th>
                            <th class="table-head" colspan="5">GRADE & SECTION:</th>
                            <th class="table-head" colspan="6">
                                <input type="text" disabled
                                    value="{{ auth()->user()->year_level_id }} - {{ auth()->user()->section }}"
                                    class="w-full border px-2 py-1">
                            </th>
                            <th class="table-head" colspan="2">TEACHER:</th>
                            <th class="table-head" colspan="10">
                                <input type="text" disabled
                                    value="{{ auth()->user()->name }}"
                                    class="w-full border px-2 py-1 bg-blue-50 text-center">
                            </th>
                            <th class="table-head" colspan="4">SUBJECT:</th>
                            <th class="table-head" colspan="4">
                                <select name="subject" disabled class="border-none bg-blue-50 text-center text-sm">
                                    <option value="" disabled>--Select Subject--</option>
                                    @foreach ($subject as $subj)
                                        <option value="{{ $subj->name }}"
                                            {{ ($classRecord->subject_id == $subj->name) ? 'selected' : '' }}>
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
                                        disabled>
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
                                        disabled>
                                </td>
                            @endfor
                            <td id="hptTotal">00</td>
                            <td>100</td>
                            <td>50%</td>
                            <!-- Quarterly Assessment Global -->
                            <td>
                                <input type="number" name="global_hqa" id="hqa"
                                    value="{{ old('global_hqa', $classRecord->global_hqa) }}"
                                    disabled>
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
                                <input type="hidden" value="{{ $detail->student_id }}">
                                <!-- Written Works for this student -->
                                @php
                                    // Define an anonymous function once to format scores.
                                    $formatScore = function($score) {
                                        if ($score === null || $score == 0) {
                                            return '';
                                        }
                                        return rtrim(rtrim(sprintf('%.2f', $score), '0'), '.');
                                    };
                                @endphp
                                @for ($i = 1; $i <= 10; $i++)
                                    @php
                                        $rawScore = old("written_works.$detail->student_id." . ($i - 1), $detail->{'written_work_' . $i});
                                        $displayValue = $formatScore($rawScore);
                                    @endphp
                                    <td>
                                        <input type="number"
                                            name="written_works[{{ $detail->student_id }}][]"
                                            id="ww{{ $index }}_{{ $i }}"
                                            value="{{ $displayValue }}"
                                            disabled>
                                    </td>
                                @endfor
                                <td>{{ $detail->written_works_total }}</td>
                                <td>{{ $detail->written_works_ps }}</td>
                                <td>{{ $detail->written_works_ws }}</td>
                                <!-- Performance Tasks for this student -->
                                @for ($i = 1; $i <= 10; $i++)
                                    @php
                                        $rawScore = old("performance_tasks.$detail->student_id." . ($i - 1), $detail->{'performance_task_' . $i});
                                        $displayValue = $formatScore($rawScore);
                                    @endphp
                                    <td>
                                        <input type="number"
                                            name="performance_tasks[{{ $detail->student_id }}][]"
                                            id="pt{{ $index }}_{{ $i }}"
                                            value="{{ $displayValue }}"
                                            disabled>
                                    </td>
                                @endfor
                                <td>{{ $detail->performance_tasks_total }}</td>
                                <td>{{ $detail->performance_tasks_ps }}</td>
                                <td>{{ $detail->performance_tasks_ws }}</td>
                                <!-- Quarterly Assessment for this student -->
                                <td>
                                    <input type="number"
                                        name="quarterly_assessment[{{ $detail->student_id }}]"
                                        id="qa{{ $index }}"
                                        value="{{ old("quarterly_assessment.$detail->student_id", $detail->quarterly_assessment) }}"
                                        disabled>
                                </td>
                                <td>{{ $detail->quarterly_assessment_ps }}</td>
                                <td>{{ $detail->quarterly_assessment_ws }}</td>
                                <td>{{ $detail->initial_grade }}</td>
                                <td>{{ $detail->quarterly_grade }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
