<!DOCTYPE html>
<html>
<head>
    <title>Student Grade Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        .header { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Student Grade Report - {{ now()->format('F j, Y') }}</h2>

    <h3>High Honors Students (90-100)</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Average Grade</th>
                <th>Grade Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($highHonors as $student)
            <tr>
                <td>{{ $student->id }}</td>
                <td>{{ $student->full_name }}</td>
                <td>{{ number_format($student->classRecords->first()->average_grade, 2) }}</td>
                <td>{{ $student->grade_level }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Students Needing Improvement (<75)</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Average Grade</th>
                <th>Grade Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($needsImprovement as $student)
            <tr>
                <td>{{ $student->id }}</td>
                <td>{{ $student->full_name }}</td>
                <td>{{ number_format($student->classRecords->first()->average_grade, 2) }}</td>
                <td>{{ $student->grade_level }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
