<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Grade Reports') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($highHonors->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="bg-green-600 text-white px-4 py-3 font-medium text-lg">
                        High Honors Students (90-100)
                    </div>
                    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                        <p class="font-medium">No high honors students found.</p>
                    </div>
                </div>
            @else
                <!-- High Honors Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="bg-green-600 text-white px-4 py-3 font-medium text-lg">
                        High Honors Students (90-100)
                    </div>
                    <div class="p-2 sm:p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            LRN No.
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Lastname, Firstname
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Average Grade
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Grade Level
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($highHonors as $student)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $student->LRN_num }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                                {{ $student->lastname }}, {{ $student->firstname }}
                                            </td>
                                            @php
                                                // Calculate the average grade for the student
                                                $average = optional($student->classRecords->first())->average_grade;
                                            @endphp

                                            <td class="px-6 py-4 whitespace-nowrap bg-blue-50">
                                                <div class="text-sm font-semibold text-gray-900 text-center">
                                                    @if ($average !== null)
                                                        <span
                                                            class="@if ($average < 75) text-red-600 @elseif($average >= 90) text-green-600 @endif">
                                                            {{ number_format($average) }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-900">-</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                                {{ $student->grade_level }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if ($needsImprovement->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="bg-red-600 text-white px-4 py-3 font-medium text-lg">
                        Students Needing Improvement (<75) </div>
                            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded"
                                role="alert">
                                <p class="font-medium">All students are passing!</p>
                            </div>
                    </div>
                @else
                    <!-- Needs Improvement Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="bg-red-600 text-white px-4 py-3 font-medium text-lg">
                            Students Needing Improvement (<75) </div>
                                <div class="p-2 sm:p-4">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        LRN No.
                                                    </th>
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Lastname, Firstname
                                                    </th>
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Average Grade
                                                    </th>
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Grade Level
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach ($needsImprovement as $student)
                                                    <tr class="hover:bg-gray-50">
                                                        <td
                                                            class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                            {{ $student->LRN_num }}
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                                            {{ $student->lastname }}, {{ $student->firstname }}
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                                            @php
                                                                $average = $student->classRecords->isNotEmpty()
                                                                    ? $student->classRecords->avg('quarterly_grade')
                                                                    : 0;
                                                            @endphp
                                                            {{ number_format($average, 2) }}
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700"
                                                            style="font-family: 'Times New Roman', Times, serif">
                                                            <strong>{{ $student->yearLevel->name }}</strong>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        </div>
            @endif

            <!-- Download Button -->
            <div class="mt-6 mb-6 flex justify-start">
                <a href="{{ route('teacher.reports.download') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white tracking-wide hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-300 transition ease-in-out duration-150">
                    <i class="fas fa-download mr-2"></i> Download PDF Report
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
