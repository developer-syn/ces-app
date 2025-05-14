<!DOCTYPE html>
<html>

<head>
    <title>Student Grade Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Base Styles */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }

        /* Header Styling */
        .report-header {
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }

        .report-header h2 {
            font-size: 24px;
            color: #2563eb;
            /* blue-600 */
            margin-bottom: 6px;
            font-weight: bold;
        }

        .report-date {
            font-size: 14px;
            color: #6b7280;
            /* gray-500 */
            margin-bottom: 20px;
        }

        .section-header {
            position: relative;
            margin: 30px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
            /* gray-200 */
        }

        .high-honors {
            color: #059669;
            /* green-600 */
            font-size: 18px;
            font-weight: bold;
        }

        .needs-improvement {
            color: #dc2626;
            /* red-600 */
            font-size: 18px;
            font-weight: bold;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 14px;
        }

        th {
            background-color: #f3f4f6;
            /* gray-100 */
            color: #374151;
            /* gray-700 */
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
            padding: 12px 10px;
            border: 1px solid #d1d5db;
            /* gray-300 */
            text-align: left;
        }

        td {
            padding: 12px 10px;
            border: 1px solid #e5e7eb;
            /* gray-200 */
            vertical-align: middle;
        }

        .high-honors-table tr td:nth-child(3) {
            color: #059669;
            /* green-600 */
            font-weight: bold;
        }

        .needs-improvement-table tr td:nth-child(3) {
            color: #dc2626;
            /* red-600 */
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
            /* gray-50 */
        }

        .empty-state {
            padding: 20px;
            background-color: #eff6ff;
            /* blue-50 */
            border-left: 4px solid #3b82f6;
            /* blue-500 */
            color: #1e40af;
            /* blue-800 */
            border-radius: 4px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .passing-message {
            padding: 20px;
            background-color: #ecfdf5;
            /* green-50 */
            border-left: 4px solid #10b981;
            /* green-500 */
            color: #065f46;
            /* green-800 */
            border-radius: 4px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            /* gray-200 */
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            /* gray-500 */
        }
    </style>
</head>

<body>
    <div class="report-header">
        <h2>Student Grade Report</h2>
        <div class="report-date">Generated on: {{ now()->format('F j, Y, g:i a') }}</div>
    </div>

    <!-- High Honors Section -->
    <div class="section-header">
        <h3 class="high-honors">High Honors Students (90-100)</h3>
    </div>

    @if ($highHonors->isEmpty())
        <div class="empty-state">
            No high honors students found.
        </div>
    @else
        <table class="high-honors-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Average Grade</th>
                    <th>Grade Level</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($highHonors as $student)
                    <tr>
                        <td>{{ $student->LRN_num }}</td>
                        <td>{{ $student->lastname }}, {{ $student->firstname }}</td>
                        <td>
                            @php
                                $average = $student->classRecords->isNotEmpty()
                                    ? $student->classRecords->avg('quarterly_grade')
                                    : 0;
                            @endphp
                            {{ number_format($average) }}</td>
                        <td>{{ $student->yearLevel->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Needs Improvement Section -->
    <div class="section-header">
        <h3 class="needs-improvement">Students Needing Improvement (&lt;75)</h3>
    </div>

    @if ($needsImprovement->isEmpty())
        <div class="passing-message">
            All students are passing!
        </div>
    @else
        <table class="needs-improvement-table">
            <thead>
                <tr>
                    <th>LRN No.</th>
                    <th>Lastname, Firstname</th>
                    <th>Average Grade</th>
                    <th>Grade Level</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($needsImprovement as $student)
                    <tr>
                        <td>{{ $student->LRN_num }}</td>
                        <td>{{ $student->lastname }}, {{ $student->firstname }}</td>
                        <td>
                            @php
                                $average = $student->classRecords->isNotEmpty()
                                    ? $student->classRecords->avg('quarterly_grade')
                                    : 0;
                            @endphp
                            {{ number_format($average) }}
                        </td>
                        <td style="font-family: 'Times New Roman', Times, serif; font-weight: bolder">
                            {{ $student->yearLevel->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        This report is confidential and intended for educational purposes only.
    </div>
</body>

</html>
