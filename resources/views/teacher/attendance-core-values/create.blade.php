<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h4 class="text-xl font-semibold text-gray-800">
                    {{ isset($record) ? 'Edit' : 'Add' }} Attendance & Core Values for
                    {{ $enrollment->student->LRN_num ?? '' }}
                </h4>
                <p class="text-gray-600">
                    Grade {{ $enrollment->yearLevel->name ?? '' }} -
                    Section {{ $enrollment->student->section ?? '' }}
                </p>
            </div>

            <div class="p-6">
                <form method="POST"
                    action="{{ isset($record)
                        ? route('teacher.attendance-core-values.update', $record->id)
                        : route('teacher.attendance-core-values.store') }}">
                    @csrf
                    @if (isset($record))
                        @method('PUT')
                    @endif

                    <input type="hidden" name="student_enrollment_id" value="{{ $enrollment->id }}">

                    {{-- Attendance Section --}}
                    <div class="mb-8">
                        <h5 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Attendance Records</h5>
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach (['jun', 'jul', 'aug', 'sept', 'oct', 'nov', 'dec', 'jan', 'feb', 'mar', 'apr'] as $month)
                                <div class="bg-gray-50 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                                    <label
                                        class="block font-semibold text-gray-700 mb-2 text-capitalize">{{ $month }}</label>
                                    <div class="space-y-3">
                                        <div>
                                            <label for="{{ $month }}_days">Number of days</label>
                                            <input type="number" name="{{ $month }}_days"
                                                id="{{ $month }}_days"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error($month . '_days') border-red-500 @enderror"
                                                placeholder="School days"
                                                value="{{ old($month . '_days', $record->{$month . '_days'} ?? '') }}"
                                                min="0">
                                            @error($month . '_days')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="{{ $month }}_present">Number of present</label>
                                            <input type="number" name="{{ $month }}_present"
                                                id="{{ $month }}_present"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error($month . '_present') border-red-500 @enderror"
                                                placeholder="Days present"
                                                value="{{ old($month . '_present', $record->{$month . '_present'} ?? '') }}"
                                                min="0">
                                            @error($month . '_present')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Core Values Section --}}
                    <div class="mb-8">
                        <h5 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Core Values Assessment</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                            <p>AO = Always Observed</p>
                            <p>SO = Sometimes Observed</p>
                            <p>RO = Rarely Observed</p>
                            <p>NO = Not Observed</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['maka_diyos', 'makatao', 'maka_kalikasan', 'makabansa'] as $coreValue)
                                <div class="bg-gray-50 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                                    <h6 class="font-medium text-gray-800 mb-3 text-capitalize">
                                        {{ str_replace('_', ' ', $coreValue) }}</h6>
                                    <div class="grid grid-cols-2 gap-4">
                                        @foreach (['q1', 'q2', 'q3', 'q4'] as $quarter)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    {{ strtoupper($quarter) }} Score
                                                </label>
                                                <select name="{{ $coreValue }}_{{ $quarter }}"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error($coreValue . '_' . $quarter) border-red-500 @enderror">
                                                    <option value="">Select Rating</option>
                                                    @foreach (['AO', 'SO', 'RO', 'NO'] as $rating)
                                                        <option value="{{ $rating }}"
                                                            {{ old($coreValue . '_' . $quarter, $record->{$coreValue . '_' . $quarter} ?? '') == $rating ? 'selected' : '' }}>
                                                            {{ $rating }} - @php
                                                                echo [
                                                                    'AO' => 'Always Observed',
                                                                    'SO' => 'Sometimes Observed',
                                                                    'RO' => 'Rarely Observed',
                                                                    'NO' => 'Not Observed',
                                                                ][$rating];
                                                            @endphp
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error($coreValue . '_' . $quarter)
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-8 pt-4 border-t">
                        <a href="{{ route('teacher.students.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition">
                            Back to Students
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                            {{ isset($record) ? 'Update' : 'Save' }} Records
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/attendance-core-values/create.js') }}"></script>
</x-app-layout>
