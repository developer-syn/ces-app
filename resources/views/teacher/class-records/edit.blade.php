<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/class-records/edit.css') }}">
    <section class="home p-6">
        <div class="dashboard">
            <header class="header">
                <div class="left">
                    <img src="{{ asset('img/Seal_of_the_Department_of_Education_of_the_Philippines.png') }}"
                        alt="DepEd Logo" class="deped-logo">
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
                <input type="hidden" name="subject_id" value="{{ $classRecord->subject_id }}">
                <input type="hidden" name="grade_section" value="{{ $classRecord->grade_section }}">
                <input type="hidden" name="quarter_id" value="{{ $classRecord->quarter_id }}">
                {{-- <input type="hidden" name="school_year_id" value="{{ $classRecord->school_year_id }}"> --}}
                <input type="hidden" name="teacher" value="{{ $classRecord->teacher }}">
                <input type="hidden" name="year_level_id" value="{{ $classRecord->year_level_id }}">


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
                            <input type="text" value="{{ auth()->user()->schoolInfo->school_name }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>SCHOOL ID:</label>
                            <input type="text" value="{{ auth()->user()->schoolInfo->school_id }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>SCHOOL YEAR:</label>
                            <select name="school_year_id">
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
                            <select name="quarter_id" class="border-none bg-blue-50 text-center text-sm" disabled>
                                <option value="" disabled>--Select Quarter--</option>
                                @foreach ($quarter as $q)
                                    <option value="{{ $q->quarter_id }}"
                                        {{ $classRecord->quarter_id == $q->name ? 'selected' : '' }}>
                                        {{ $q->name }}
                                    </option>
                                @endforeach
                            </select>
                        </th>
                        <th class="table-head" colspan="6">GRADE & SECTION:</th>
                        <th class="table-head" colspan="5">
                            <input type="text" name="grade_section" value="{{ $classRecord->grade_section }}"
                                placeholder="GRADE & SECTION" class="w-full border px-2 py-1 bg-blue-50 text-center"
                                disabled>
                        </th>
                        <th class="table-head" colspan="5">TEACHER:</th>
                        <th class="table-head" colspan="7">
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
                    @foreach ($mergedRecords as $detail)
                        @php $index++; @endphp
                        <tr>
                            <td>{{ $index }}</td>
                            <td class="text-left" style="text-align: left; padding-left: 5px;">
                                {{ ucwords(strtolower($detail->student->lastname)) }},
                                {{ ucwords(strtolower($detail->student->firstname)) }},
                                {{ ucwords(strtolower($detail->student->middlename)) }}</td>
                            <input type="hidden" name="student_id[]" value="{{ $detail->student_id }}">

                            <!-- Written Works for this student -->
                            @php
                                // Define the helper function once
                                $formatScore = function ($score) {
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
                                    $rawScore = old(
                                        "written_works.$detail->student_id." . ($i - 1),
                                        $detail->{'written_work_' . $i},
                                    );
                                    // Format the score using our helper function
                                    $displayValue = $formatScore($rawScore);
                                @endphp

                                <td>
                                    <input type="number" name="written_works[{{ $detail->student_id }}][]"
                                        id="ww{{ $index }}_{{ $i }}" value="{{ $displayValue }}"
                                        min="0" step="1" class="score-input"
                                        data-student-index="{{ $index }}" data-type="ww"
                                        data-task="{{ $i }}">
                                </td>
                            @endfor
                            <td id="wwTotal{{ $index }}">{{ $detail->written_works_total }}</td>
                            <td id="wwPS{{ $index }}">{{ $detail->written_works_ps }}</td>
                            <td id="wwWS{{ $index }}">{{ $detail->written_works_ws }}</td>
                            <!-- Performance Tasks for this student -->
                            @for ($i = 1; $i <= 10; $i++)
                                @php
                                    // Retrieve the stored or old value for performance task i
                                    $rawScore = old(
                                        "performance_tasks.$detail->student_id." . ($i - 1),
                                        $detail->{'performance_task_' . $i},
                                    );
                                    // Format the score: blank if 0 or null, otherwise trimmed version (e.g., "11.5" instead of "11.50")
                                    $displayValue = $formatScore($rawScore);
                                @endphp
                                <td>
                                    <input type="number" name="performance_tasks[{{ $detail->student_id }}][]"
                                        id="pt{{ $index }}_{{ $i }}" value="{{ $displayValue }}"
                                        min="0" step="1" class="score-input"
                                        data-student-index="{{ $index }}" data-type="pt"
                                        data-task="{{ $i }}">
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
                                    class="qa-input" data-student-index="{{ $index }}">
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

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('teacher.class-records.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save
                    </button>
                </div>
            </form>
        </div>

        <script>
            function updateGlobalTotals() {
                let hwwTotal = 0,
                    hptTotal = 0;

                // Calculate totals for written works
                for (let i = 1; i <= 10; i++) {
                    const score = parseFloat(document.getElementById(`hww${i}`).value) || 0;
                    document.getElementById(`hww${i}`).value = Math.max(0, score);
                    hwwTotal += score;
                }

                // Calculate totals for performance tasks
                for (let i = 1; i <= 10; i++) {
                    const score = parseFloat(document.getElementById(`hpt${i}`).value) || 0;
                    document.getElementById(`hpt${i}`).value = Math.max(0, score);
                    hptTotal += score;
                }

                // Update displayed totals
                document.getElementById("hwwTotal").textContent = hwwTotal;
                document.getElementById("hptTotal").textContent = hptTotal;

                // Validate quarterly assessment header
                const hqaInput = document.getElementById('hqa');
                const hqaValue = parseFloat(hqaInput.value) || 0;
                hqaInput.value = Math.max(0, hqaValue);
            }

            function calculateGrades(studentIndex) {
                // Get all maximum possible scores
                const maxScores = {
                    ww: Array.from({
                        length: 10
                    }, (_, i) => {
                        return parseFloat(document.getElementById(`hww${i+1}`).value) || 0;
                    }),
                    pt: Array.from({
                        length: 10
                    }, (_, i) => {
                        return parseFloat(document.getElementById(`hpt${i+1}`).value) || 0;
                    }),
                    qa: parseFloat(document.getElementById('hqa').value) || 0
                };

                // Clamp and validate written works
                let wwTotal = 0;
                for (let i = 1; i <= 10; i++) {
                    const input = document.getElementById(`ww${studentIndex}_${i}`);
                    let value = parseFloat(input.value) || 0;
                    const max = maxScores.ww[i - 1];

                    value = Math.min(Math.max(value, 0), max);
                    input.value = value === 0 ? '' : value;
                    wwTotal += value;
                    input.classList.toggle('invalid-input', value > max);
                }

                // Clamp and validate performance tasks
                let ptTotal = 0;
                for (let i = 1; i <= 10; i++) {
                    const input = document.getElementById(`pt${studentIndex}_${i}`);
                    let value = parseFloat(input.value) || 0;
                    const max = maxScores.pt[i - 1];

                    value = Math.min(Math.max(value, 0), max);
                    input.value = value === 0 ? '' : value;
                    ptTotal += value;
                    input.classList.toggle('invalid-input', value > max);
                }

                // Validate quarterly assessment
                const qaInput = document.getElementById(`qa${studentIndex}`);
                let qaValue = parseFloat(qaInput.value) || 0;
                qaValue = Math.min(Math.max(qaValue, 0), maxScores.qa);
                qaInput.value = qaValue === 0 ? '' : qaValue;
                qaInput.classList.toggle('invalid-input', qaValue > maxScores.qa);

                // Calculate percentages
                const globalHwwTotal = Math.max(parseFloat(document.getElementById("hwwTotal").textContent), 1);
                const globalHptTotal = Math.max(parseFloat(document.getElementById("hptTotal").textContent), 1);
                const hqaTotal = Math.max(maxScores.qa, 1);

                // Written works calculations
                const wwPS = (wwTotal / globalHwwTotal) * 100;
                const wwWS = wwPS * 0.30;
                document.getElementById(`wwTotal${studentIndex}`).textContent = wwTotal.toFixed(2);
                document.getElementById(`wwPS${studentIndex}`).textContent = wwPS.toFixed(2);
                document.getElementById(`wwWS${studentIndex}`).textContent = wwWS.toFixed(2);

                // Performance tasks calculations
                const ptPS = (ptTotal / globalHptTotal) * 100;
                const ptWS = ptPS * 0.50;
                document.getElementById(`ptTotal${studentIndex}`).textContent = ptTotal.toFixed(2);
                document.getElementById(`ptPS${studentIndex}`).textContent = ptPS.toFixed(2);
                document.getElementById(`ptWS${studentIndex}`).textContent = ptWS.toFixed(2);

                // Quarterly assessment calculations
                const qaPS = (qaValue / hqaTotal) * 100;
                const qaWS = qaPS * 0.20;
                document.getElementById(`qaPS${studentIndex}`).textContent = qaPS.toFixed(2);
                document.getElementById(`qaWS${studentIndex}`).textContent = qaWS.toFixed(2);

                // Transmutation logic
                const TRANSMUTATION_TABLE = [{
                        min: 100,
                        grade: 100
                    },
                    {
                        min: 98.40,
                        grade: 99
                    },
                    {
                        min: 96.80,
                        grade: 98
                    },
                    {
                        min: 95.20,
                        grade: 97
                    },
                    {
                        min: 93.60,
                        grade: 96
                    },
                    {
                        min: 92.00,
                        grade: 95
                    },
                    {
                        min: 90.40,
                        grade: 94
                    },
                    {
                        min: 88.80,
                        grade: 93
                    },
                    {
                        min: 87.20,
                        grade: 92
                    },
                    {
                        min: 85.60,
                        grade: 91
                    },
                    {
                        min: 84.00,
                        grade: 90
                    },
                    {
                        min: 82.40,
                        grade: 89
                    },
                    {
                        min: 80.80,
                        grade: 88
                    },
                    {
                        min: 79.20,
                        grade: 87
                    },
                    {
                        min: 77.60,
                        grade: 86
                    },
                    {
                        min: 76.00,
                        grade: 85
                    },
                    {
                        min: 74.40,
                        grade: 84
                    },
                    {
                        min: 72.80,
                        grade: 83
                    },
                    {
                        min: 71.20,
                        grade: 82
                    },
                    {
                        min: 69.60,
                        grade: 81
                    },
                    {
                        min: 68.00,
                        grade: 80
                    },
                    {
                        min: 66.40,
                        grade: 79
                    },
                    {
                        min: 64.80,
                        grade: 78
                    },
                    {
                        min: 63.20,
                        grade: 77
                    },
                    {
                        min: 61.60,
                        grade: 76
                    },
                    {
                        min: 60.00,
                        grade: 75
                    },
                    {
                        min: 56.00,
                        grade: 74
                    },
                    {
                        min: 52.00,
                        grade: 73
                    },
                    {
                        min: 48.00,
                        grade: 72
                    },
                    {
                        min: 44.00,
                        grade: 71
                    },
                    {
                        min: 40.00,
                        grade: 70
                    },
                    {
                        min: 36.00,
                        grade: 69
                    },
                    {
                        min: 32.00,
                        grade: 68
                    },
                    {
                        min: 28.00,
                        grade: 67
                    },
                    {
                        min: 24.00,
                        grade: 66
                    },
                    {
                        min: 20.00,
                        grade: 65
                    },
                    {
                        min: 16.00,
                        grade: 64
                    },
                    {
                        min: 12.00,
                        grade: 63
                    },
                    {
                        min: 8.00,
                        grade: 62
                    },
                    {
                        min: 4.00,
                        grade: 61
                    },
                    {
                        min: 0,
                        grade: 60
                    }
                ].sort((a, b) => b.min - a.min);

                function getTransmutedGrade(initialGrade) {
                    if (initialGrade >= 100) return 100;
                    const entry = TRANSMUTATION_TABLE.find(entry => initialGrade >= entry.min);
                    return entry ? entry.grade : 60;
                }

                const initialGrade = wwWS + ptWS + qaWS;
                const transmutedGrade = getTransmutedGrade(initialGrade);
                document.getElementById(`initialGrade${studentIndex}`).value = initialGrade.toFixed(2);
                document.getElementById(`quarterlyGrade${studentIndex}`).value = transmutedGrade;
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize global totals
                updateGlobalTotals();

                // Event delegation for all score inputs
                document.addEventListener('input', function(e) {
                    const target = e.target;

                    if (target.classList.contains('score-input')) {
                        const studentIndex = target.dataset.studentIndex;
                        const taskType = target.dataset.type;
                        const taskNumber = target.dataset.task;

                        clampScore(target, `h${taskType}${taskNumber}`);
                        calculateGrades(studentIndex);
                    }

                    if (target.classList.contains('qa-input')) {
                        const studentIndex = target.dataset.studentIndex;
                        calculateGrades(studentIndex);
                    }
                });

                // Header inputs listener
                document.querySelectorAll('[id^="hww"], [id^="hpt"], #hqa').forEach(input => {
                    input.addEventListener('input', function() {
                        this.value = Math.max(0, parseFloat(this.value) || 0);
                        updateGlobalTotals();
                        recalculateAllGrades();
                    });
                });
            });

            // Global functions
            function clampScore(input, maxScoreId) {
                const max = parseFloat(document.getElementById(maxScoreId).value) || 0;
                let value = parseFloat(input.value) || 0;
                value = Math.min(Math.max(value, 0), max);
                input.value = value === 0 ? '' : value.toFixed(2);
            }

            function recalculateAllGrades() {
                const studentIndices = new Set(
                    Array.from(document.querySelectorAll('.score-input')).map(input =>
                        input.dataset.studentIndex
                    )
                );
                studentIndices.forEach(index => calculateGrades(index));
            }
        </script>
        <style>
            .invalid-input {
                border-color: #dc3545 !important;
                background-color: #fff5f5 !important;
                animation: shake 0.3s;
            }

            @keyframes shake {

                0%,
                100% {
                    transform: translateX(0);
                }

                25% {
                    transform: translateX(-3px);
                }

                75% {
                    transform: translateX(3px);
                }
            }

            /* Remove number input arrows */
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
        </style>
        </div>
    </section>
</x-app-layout>
