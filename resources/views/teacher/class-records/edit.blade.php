<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Class Record') }}
        </h2>
    </x-slot>

    <div class="py-12 container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('teacher.class-records.store') }}" method="POST">
            @csrf

            <!-- Top: Basic Class Info (Region, Division, District, School Name, ID, etc.) -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="region" class="form-label">Region</label>
                    <input type="text" name="region" id="region" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="division" class="form-label">Division</label>
                    <input type="text" name="division" id="division" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="district" class="form-label">District</label>
                    <input type="text" name="district" id="district" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="school_name" class="form-label">School Name</label>
                    <input type="text" name="school_name" id="school_name" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="school_id" class="form-label">School ID</label>
                    <input type="text" name="school_id" id="school_id" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select name="subject_id" id="subject_id" class="form-control" required>
                        <option value="">--Select Subject--</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="grade_section" class="form-label">Grade & Section</label>
                    <input type="text" name="grade_section" id="grade_section" class="form-control" placeholder="e.g. Grade 6 - Sulayse" required>
                </div>
                <div class="col-md-3">
                    <label for="school_year" class="form-label">School Year</label>
                    <input type="text" name="school_year" id="school_year" class="form-control" placeholder="e.g. 2024-2025" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="quarter" class="form-label">Quarter</label>
                    <select name="quarter" id="quarter" class="form-control" required>
                        <option value="1st">1st Quarter</option>
                        <option value="2nd">2nd Quarter</option>
                        <option value="3rd">3rd Quarter</option>
                        <option value="4th">4th Quarter</option>
                    </select>
                </div>
            </div>

            <!-- Scores Table: Example with 5 Written Works, 3 Performance Tasks, 1 Quarterly Assessment -->
            <h4>Class Record Table</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Learner's Name</th>
                        <!-- Written Works columns -->
                        @for($i = 1; $i <= 5; $i++)
                            <th>WW{{ $i }}</th>
                        @endfor
                        <!-- Performance Tasks columns -->
                        @for($i = 1; $i <= 3; $i++)
                            <th>PT{{ $i }}</th>
                        @endfor
                        <!-- Quarterly Assessment -->
                        <th>QA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <!-- Example: name your input fields so they can be stored in a JSON or separate table -->
                            @for($i = 1; $i <= 5; $i++)
                                <td>
                                    <input type="number" name="scores[{{ $student->id }}][WW{{ $i }}]" class="form-control" min="0">
                                </td>
                            @endfor

                            @for($i = 1; $i <= 3; $i++)
                                <td>
                                    <input type="number" name="scores[{{ $student->id }}][PT{{ $i }}]" class="form-control" min="0">
                                </td>
                            @endfor

                            <td>
                                <input type="number" name="scores[{{ $student->id }}][QA]" class="form-control" min="0">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-success">Save Class Record</button>
        </form>
    </div>
</x-app-layout>
