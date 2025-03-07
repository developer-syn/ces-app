<x-app-layout>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: white;
        }

        .dashboard {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }

        .logo-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
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
            font-size: 24px;
            margin: 10px 0 5px;
        }

        .subtitle {
            font-size: 14px;
            margin-bottom: 20px;
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

        /* .form-group input {
            border: 1px solid #000;
            padding: 5px;
            width: 150px;
        } */
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
                <div class="logo-container">
                    <img src="https://upload.wikimedia.org/wikipedia/en/thumb/4/4b/Seal_of_the_Department_of_Education_of_the_Philippines.svg/1200px-Seal_of_the_Department_of_Education_of_the_Philippines.svg.png"
                        alt="DepEd Logo" class="deped-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Department_of_Education_%28DepEd%29.svg/2560px-Department_of_Education_%28DepEd%29.svg.png"
                        alt="DepEd Text" class="deped-text">
                </div>
                <h1 class="title">Class Record</h1>
                <div class="subtitle">(Pursuant to DepEd Order 8 s. of 2015)</div>
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

            <form method="POST" action="{{ route('teacher.class-records.store') }}">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
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
                            <select name="school_year">
                                @foreach ($schoolYear as $year)
                                    <option value="{{ $year->name }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <table class="grade-table">
                    <tr class="head-row">
                        <th class="table-head" colspan="2">
                            <select name="quarter"
                                style="border: none; background-color: #f7fcff; text-align:center; font-size: 14px;">
                                <option value="" disabled selected>--Select Quarter--</option>
                                @foreach ($quarter as $quarter)
                                    <option value="{{ $quarter->name }}">{{ $quarter->name }}</option>
                                @endforeach
                            </select>
                        </th>
                        <th class="table-head" colspan="5">GRADE & SECTION:</th>
                        <th class="table-head" colspan="6">
                            <input type="text" name="grade_section"
                                value="{{ auth()->user()->year_level_id }} - {{ auth()->user()->section }}"
                                placeholder="GRADE & SECTION">
                        </th>
                        <th class="table-head" colspan="2">TEACHER:</th>
                        <th class="table-head" colspan="10">
                            <input type="text" name="teacher" value="{{ auth()->user()->name }}"
                                placeholder="TEACHER"
                                style="border: none; background-color: #f7fcff; text-align:center;">
                        </th>
                        <th class="table-head" colspan="4">SUBJECT:</th>
                        <th class="table-head" colspan="4">
                            <select name="subject"
                                style="border: none; background-color: #f7fcff; text-align:center; font-size: 14px;">
                                <option value="" disabled selected>--Select Subject--</option>
                                @foreach ($subject as $subj)
                                    <option value="{{ $subj->name }}">{{ $subj->name }}</option>
                                @endforeach
                            </select>
                        </th>
                    </tr>
                    <tr class="header-row">
                        <th rowspan="1"></th>
                        <th rowspan="1" class="name-column">LEARNERS' NAMES</th>
                        <th colspan="13">WRITTEN WORKS (30%)</th>
                        <th colspan="13">PERFORMANCE TASKS (50%)</th>
                        <th colspan="3">QUARTERLY ASSESSMENT (20%)</th>
                        <th rowspan="3">Initial Grade</th>
                        <th rowspan="3">Quarterly Grade</th>
                    </tr>
                    <tr class="subheader-row">
                        <th></th>
                        <th></th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>Total</th>
                        <th>PS</th>
                        <th>WS</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>Total</th>
                        <th>PS</th>
                        <th>WS</th>
                        <th>1</th>
                        <th>PS</th>
                        <th>WS</th>
                    <tr>
                        <td></td>
                        <td>HIGHEST POSSIBLE SCORE</td>
                        <!-- Written Works -->
                        @for ($i = 1; $i <= 10; $i++)
                            <td>
                                <input type="number" name="hww[]" id="hww{{ $i }}"
                                    oninput="calculateGrades()">
                            </td>
                        @endfor

                        <td id="hwwTotal">00</td>
                        <td>100</td>
                        <td>30%</td>

                        <!-- Performance Tasks -->
                        @for ($i = 1; $i <= 10; $i++)
                            <td>
                                <input type="number" name="hpt[]" id="hpt{{ $i }}"
                                    oninput="calculateGrades()">
                            </td>
                        @endfor

                        <td id="hptTotal">00</td>
                        <td>100</td>
                        <td>50%</td>


                        <!-- Quarterly Assessment -->
                        <td><input type="number" name="global_hqa" id="hqa" oninput="calculateGrades()"></td>
                        <td>100.00</td>
                        <td>20%</td>
                    </tr>
                    @php $index = 0; @endphp
                    @foreach ($students as $student)
                        @php $index++; @endphp
                        <tr>
                            <td>{{ $index }}</td>
                            <td>{{ $student->name }}</td>
                            <input type="hidden" name="student_id[]" value="{{ $student->id }}">

                            <!-- Written Works -->
                            @for ($i = 1; $i <= 10; $i++)
                                <td><input type="number" name="written_works[{{ $student->id }}][]"
                                        id="ww{{ $index }}_{{ $i }}"
                                        oninput="calculateGrades({{ $index }})"></td>
                            @endfor
                            <td id="wwTotal{{ $index }}">0</td>
                            <td id="wwPS{{ $index }}">0</td>
                            <td id="wwWS{{ $index }}">0</td>

                            <!-- Performance Tasks -->
                            @for ($i = 1; $i <= 10; $i++)
                                <td><input type="number" name="performance_tasks[{{ $student->id }}][]"
                                        id="pt{{ $index }}_{{ $i }}"
                                        oninput="calculateGrades({{ $index }})"></td>
                            @endfor
                            <td id="ptTotal{{ $index }}">0</td>
                            <td id="ptPS{{ $index }}">0</td>
                            <td id="ptWS{{ $index }}">0</td>

                            <!-- Quarterly Assessment -->
                            <td><input type="number" name="quarterly_assessment[{{ $student->id }}]"
                                    id="qa{{ $index }}" oninput="calculateGrades({{ $index }})">
                            </td>
                            <td id="qaPS{{ $index }}">0</td>
                            <td id="qaWS{{ $index }}">0</td>

                            <td><input type="text" name="initial_grade[{{ $student->id }}]"
                                    id="initialGrade{{ $index }}" readonly></td>
                            <td><input type="text" name="quarterly_grade[{{ $student->id }}]"
                                    id="quarterlyGrade{{ $index }}" readonly></td>
                        </tr>
                    @endforeach
                </table>
                <button type="submit" class="btn btn-primary">Submit Grades</button>
            </form>
            <script>
                // Update global header totals when any global field changes.
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

                // Calculate the student's grades using both the student's inputs and the global header values.
                function calculateGrades(studentIndex) {
                    // First update the global totals.
                    updateGlobalTotals();
                    let wwTotal = 0,
                        ptTotal = 0,
                        qaTotal = 0;
                    // Get global totals (computed in header row)
                    let globalHwwTotal = parseFloat(document.getElementById("hwwTotal").textContent) || 0;
                    let globalHptTotal = parseFloat(document.getElementById("hptTotal").textContent) || 0;
                    let hqaTotal = parseFloat(document.getElementById("hqa").value) || 0;

                    // Sum student's Written Works
                    for (let i = 1; i <= 10; i++) {
                        wwTotal += parseFloat(document.getElementById(`ww${studentIndex}_${i}`).value) || 0;
                    }
                    document.getElementById(`wwTotal${studentIndex}`).textContent = wwTotal;

                    // Sum student's Performance Tasks
                    for (let i = 1; i <= 10; i++) {
                        ptTotal += parseFloat(document.getElementById(`pt${studentIndex}_${i}`).value) || 0;
                    }
                    document.getElementById(`ptTotal${studentIndex}`).textContent = ptTotal;

                    // Get student's Quarterly Assessment score
                    qaTotal = parseFloat(document.getElementById(`qa${studentIndex}`).value) || 0;
                    let qaPS = hqaTotal ? (qaTotal / hqaTotal) * 100 : 0;
                    let qaWS = qaPS * 0.20;
                    document.getElementById(`qaPS${studentIndex}`).textContent = qaPS.toFixed(2);
                    document.getElementById(`qaWS${studentIndex}`).textContent = qaWS.toFixed(2);

                    // Compute Written Works percentage and weighted score
                    let wwPS = globalHwwTotal ? (wwTotal / globalHwwTotal) * 100 : 0;
                    let wwWS = wwPS * 0.30;
                    document.getElementById(`wwPS${studentIndex}`).textContent = wwPS.toFixed(2);
                    document.getElementById(`wwWS${studentIndex}`).textContent = wwWS.toFixed(2);

                    // Compute Performance Tasks percentage and weighted score
                    let ptPS = globalHptTotal ? (ptTotal / globalHptTotal) * 100 : 0;
                    let ptWS = ptPS * 0.50;
                    document.getElementById(`ptPS${studentIndex}`).textContent = ptPS.toFixed(2);
                    document.getElementById(`ptWS${studentIndex}`).textContent = ptWS.toFixed(2);

                    // Final grade calculations
                    let initialGrade = wwWS + ptWS + qaWS;
                    document.getElementById(`initialGrade${studentIndex}`).value = initialGrade.toFixed(2);
                    document.getElementById(`quarterlyGrade${studentIndex}`).value = Math.round(initialGrade);
                }
            </script>
        </div>
    </section>
</x-app-layout>
