<x-app-layout>
    <x-slot name="title">YSYQ Management</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subjects / Year Levels / School Years / Quarters') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="grid md:grid-cols-4 gap-6">
            <!-- Year Levels Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Year Levels</h3>
                    <!-- Add Year Level Form -->
                    <form action="{{ route('teacher.year-levels.store') }}" method="POST" class="mb-6">
                        @csrf
                        <div class="flex gap-2">
                            <input type="text" name="name"
                                class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Enter year level name" required>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Save
                            </button>
                        </div>
                    </form>
                    <!-- Year Levels Table -->
                    <div class="overflow-x-auto rounded-lg border">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th> --}}
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($yearLevels as $yearLevel)
                                    <tr class="hover:bg-gray-50">
                                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $yearLevel->id }}</td> --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $yearLevel->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex gap-2">
                                                @if (Auth::user()->role === 'admin')
                                                    <form
                                                        action="{{ route('teacher.year-levels.update', $yearLevel->id) }}"
                                                        method="POST" class="flex gap-4">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="text" name="name"
                                                            value="{{ $yearLevel->name }}"
                                                            class="w-10 rounded-md border-gray-300 text-sm" required>
                                                        <button type="submit"
                                                            class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                                            <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                                class="text-white" xmlns="http://www.w3.org/2000/svg">
                                                                <g id="Complete">
                                                                    <g id="edit">
                                                                        <g>
                                                                            <path
                                                                                d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8"
                                                                                fill="none" stroke="currentColor"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2" />
                                                                            <polygon fill="none"
                                                                                points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8"
                                                                                stroke="currentColor"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2" />
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <form
                                                        action="{{ route('teacher.year-levels.destroy', $yearLevel->id) }}"
                                                        method="POST" class="flex gap-4">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                                            onclick="return confirm('Are you sure you want to delete this year level?')">
                                                            <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                                fill="none" class="text-white"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M3 3L6 6M6 6L10 10M6 6V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18M6 6H4M10 10L14 14M10 10V17M14 14L18 18M14 14V17M18 18L21 21M18 6V12.3906M18 6H16M18 6H20M16 6L15.4558 4.36754C15.1836 3.55086 14.4193 3 13.5585 3H10.4415C9.94239 3 9.47572 3.18519 9.11861 3.5M16 6H11.6133"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <!-- Button -->
                                                    <button type="button"
                                                        class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition"
                                                        onclick="showAccessDeniedModal()">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                            class="text-white" xmlns="http://www.w3.org/2000/svg">
                                                            <g id="Complete">
                                                                <g id="edit">
                                                                    <g>
                                                                        <path
                                                                            d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8"
                                                                            fill="none" stroke="currentColor"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2" />
                                                                        <polygon fill="none"
                                                                            points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8"
                                                                            stroke="currentColor" stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2" />
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </button>

                                                    <!-- Modal -->
                                                    <div id="accessDeniedModal"
                                                        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">
                                                        <div class="flex items-center justify-center min-h-screen">
                                                            <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
                                                                <div class="text-yellow-500 mb-4">
                                                                    <svg class="w-16 h-16 mx-auto" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <h3 class="text-2xl font-bold text-center mb-4">403 -
                                                                    Access Denied</h3>
                                                                <p class="text-gray-600 text-center mb-6">
                                                                    This action requires special permissions.<br>
                                                                    Contact your administrator to request access.
                                                                </p>
                                                                <div class="text-center">
                                                                    <button onclick="hideAccessDeniedModal()"
                                                                        class="px-6 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                                                        Understood
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Button -->
                                                    <button type="button"
                                                        class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                                        onclick="showAccessDeniedModal()">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                            fill="none" class="text-white"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M3 3L6 6M6 6L10 10M6 6V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18M6 6H4M10 10L14 14M10 10V17M14 14L18 18M14 14V17M18 18L21 21M18 6V12.3906M18 6H16M18 6H20M16 6L15.4558 4.36754C15.1836 3.55086 14.4193 3 13.5585 3H10.4415C9.94239 3 9.47572 3.18519 9.11861 3.5M16 6H11.6133"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </button>

                                                    <!-- Modal -->
                                                    <div id="accessDeniedModal"
                                                        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">
                                                        <div class="flex items-center justify-center min-h-screen">
                                                            <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
                                                                <div class="text-red-500 mb-4">
                                                                    <svg class="w-16 h-16 mx-auto" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <h3 class="text-2xl font-bold text-center mb-4">403 -
                                                                    Access Denied</h3>
                                                                <p class="text-gray-600 text-center mb-6">
                                                                    You don't have permission to perform this
                                                                    action.<br>
                                                                    Please contact your administrator.
                                                                </p>
                                                                <div class="text-center">
                                                                    <button onclick="hideAccessDeniedModal()"
                                                                        class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                                                                        OK
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- School Years Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">School Years</h3>
                    <!-- Add School Year Form -->
                    <form action="{{ route('teacher.school-years.store') }}" method="POST" class="mb-6">
                        @csrf
                        <div class="flex gap-2">
                            <input type="text" name="name"
                                class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Enter school year name" required>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Save
                            </button>
                        </div>
                    </form>
                    <!-- Shool Years Table -->
                    <div class="overflow-x-auto rounded-lg border">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">School
                                        Year</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($schoolYears as $schoolYear)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $schoolYear->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($schoolYear->current)
                                                <span class="text-green-500 font-bold">Yes</span>
                                            @else
                                                <span class="text-gray-400">No</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if (Auth::user()->role === 'admin')
                                                <form
                                                    action="{{ route('teacher.school-years.update', $schoolYear->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <input type="text" name="name"
                                                        value="{{ $schoolYear->name }}"
                                                        class="rounded-md border-gray-300 text-sm" required>

                                                    <label class="flex items-center">
                                                        <input type="hidden" name="current" value="no">
                                                        <!-- Ensures unchecked checkboxes send "no" -->
                                                        <input type="checkbox" name="current" value="yes"
                                                            {{ $schoolYear->current ? 'checked' : '' }}>
                                                        <span class="ml-2 text-sm mt-2">Current</span>
                                                    </label>

                                                    <button type="submit"
                                                        class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                            class="text-white" xmlns="http://www.w3.org/2000/svg">
                                                            <g id="Complete">
                                                                <g id="edit">
                                                                    <g>
                                                                        <path
                                                                            d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8"
                                                                            fill="none" stroke="currentColor"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            stroke-width="2" />
                                                                        <polygon fill="none"
                                                                            points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8"
                                                                            stroke="currentColor"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            stroke-width="2" />
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </button>
                                                </form>
                                                <form
                                                    action="{{ route('teacher.school-years.destroy', $schoolYear->id) }}"
                                                    method="POST" class="mt-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                                        onclick="return confirm('Are you sure you want to delete this subject?')">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                            fill="none" class="text-white"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M3 3L6 6M6 6L10 10M6 6V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18M6 6H4M10 10L14 14M10 10V17M14 14L18 18M14 14V17M18 18L21 21M18 6V12.3906M18 6H16M18 6H20M16 6L15.4558 4.36754C15.1836 3.55086 14.4193 3 13.5585 3H10.4415C9.94239 3 9.47572 3.18519 9.11861 3.5M16 6H11.6133"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <!-- Button -->
                                                <button type="button"
                                                    class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition"
                                                    onclick="showAccessDeniedModal()">
                                                    <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                        class="text-white" xmlns="http://www.w3.org/2000/svg">
                                                        <g id="Complete">
                                                            <g id="edit">
                                                                <g>
                                                                    <path
                                                                        d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8"
                                                                        fill="none" stroke="currentColor"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" />
                                                                    <polygon fill="none"
                                                                        points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8"
                                                                        stroke="currentColor" stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2" />
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </button>

                                                <!-- Modal -->
                                                <div id="accessDeniedModal"
                                                    class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">
                                                    <div class="flex items-center justify-center min-h-screen">
                                                        <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
                                                            <div class="text-yellow-500 mb-4">
                                                                <svg class="w-16 h-16 mx-auto" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <h3 class="text-2xl font-bold text-center mb-4">403 -
                                                                Access Denied</h3>
                                                            <p class="text-gray-600 text-center mb-6">
                                                                This action requires special permissions.<br>
                                                                Contact your administrator to request access.
                                                            </p>
                                                            <div class="text-center">
                                                                <button onclick="hideAccessDeniedModal()"
                                                                    class="px-6 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                                                    Understood
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Button -->
                                                <button type="button"
                                                    class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                                    onclick="showAccessDeniedModal()">
                                                    <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                        fill="none" class="text-white"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M3 3L6 6M6 6L10 10M6 6V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18M6 6H4M10 10L14 14M10 10V17M14 14L18 18M14 14V17M18 18L21 21M18 6V12.3906M18 6H16M18 6H20M16 6L15.4558 4.36754C15.1836 3.55086 14.4193 3 13.5585 3H10.4415C9.94239 3 9.47572 3.18519 9.11861 3.5M16 6H11.6133"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>

                                                <!-- Modal -->
                                                <div id="accessDeniedModal"
                                                    class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">
                                                    <div class="flex items-center justify-center min-h-screen">
                                                        <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
                                                            <div class="text-red-500 mb-4">
                                                                <svg class="w-16 h-16 mx-auto" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <h3 class="text-2xl font-bold text-center mb-4">403 -
                                                                Access Denied</h3>
                                                            <p class="text-gray-600 text-center mb-6">
                                                                You don't have permission to perform this action.<br>
                                                                Please contact your administrator.
                                                            </p>
                                                            <div class="text-center">
                                                                <button onclick="hideAccessDeniedModal()"
                                                                    class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                                                                    OK
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <!-- Subjects Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Subjects</h3>

                    <!-- Add Subject Form -->
                    <form action="{{ route('teacher.subjects.store') }}" method="POST" class="mb-6">
                        @csrf
                        <div class="flex gap-2">
                            <input type="text" name="name"
                                class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Enter subject name" required>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Save
                            </button>
                        </div>
                    </form>

                    <!-- Subjects Table -->
                    <div class="overflow-x-auto rounded-lg border">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Subjects</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($subjects as $subject)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $subject->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex gap-2">
                                                @if (Auth::user()->role === 'admin')
                                                    <form
                                                        action="{{ route('teacher.subjects.update', $subject->id) }}"
                                                        method="POST" class="flex gap-2">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="text" name="name"
                                                            value="{{ $subject->name }}"
                                                            class="rounded-md border-gray-300 text-sm" required>
                                                        <button type="submit"
                                                            class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                                            <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                                class="text-white" xmlns="http://www.w3.org/2000/svg">
                                                                <g id="Complete">
                                                                    <g id="edit">
                                                                        <g>
                                                                            <path
                                                                                d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8"
                                                                                fill="none" stroke="currentColor"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2" />
                                                                            <polygon fill="none"
                                                                                points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8"
                                                                                stroke="currentColor"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2" />
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <form
                                                        action="{{ route('teacher.subjects.destroy', $subject->id) }}"
                                                        method="POST" class="flex gap-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                                            onclick="return confirm('Are you sure you want to delete this subject?')">
                                                            <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                                fill="none" class="text-white"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M3 3L6 6M6 6L10 10M6 6V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18M6 6H4M10 10L14 14M10 10V17M14 14L18 18M14 14V17M18 18L21 21M18 6V12.3906M18 6H16M18 6H20M16 6L15.4558 4.36754C15.1836 3.55086 14.4193 3 13.5585 3H10.4415C9.94239 3 9.47572 3.18519 9.11861 3.5M16 6H11.6133"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <!-- Button -->
                                                    <button type="button"
                                                        class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition"
                                                        onclick="showAccessDeniedModal()">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                            class="text-white" xmlns="http://www.w3.org/2000/svg">
                                                            <g id="Complete">
                                                                <g id="edit">
                                                                    <g>
                                                                        <path
                                                                            d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8"
                                                                            fill="none" stroke="currentColor"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            stroke-width="2" />
                                                                        <polygon fill="none"
                                                                            points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8"
                                                                            stroke="currentColor"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            stroke-width="2" />
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </button>

                                                    <!-- Modal -->
                                                    <div id="accessDeniedModal"
                                                        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">
                                                        <div class="flex items-center justify-center min-h-screen">
                                                            <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
                                                                <div class="text-yellow-500 mb-4">
                                                                    <svg class="w-16 h-16 mx-auto" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <h3 class="text-2xl font-bold text-center mb-4">403 -
                                                                    Access Denied</h3>
                                                                <p class="text-gray-600 text-center mb-6">
                                                                    This action requires special permissions.<br>
                                                                    Contact your administrator to request access.
                                                                </p>
                                                                <div class="text-center">
                                                                    <button onclick="hideAccessDeniedModal()"
                                                                        class="px-6 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                                                        Understood
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Button -->
                                                    <button type="button"
                                                        class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                                        onclick="showAccessDeniedModal()">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                            fill="none" class="text-white"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M3 3L6 6M6 6L10 10M6 6V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18M6 6H4M10 10L14 14M10 10V17M14 14L18 18M14 14V17M18 18L21 21M18 6V12.3906M18 6H16M18 6H20M16 6L15.4558 4.36754C15.1836 3.55086 14.4193 3 13.5585 3H10.4415C9.94239 3 9.47572 3.18519 9.11861 3.5M16 6H11.6133"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </button>

                                                    <!-- Modal -->
                                                    <div id="accessDeniedModal"
                                                        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">
                                                        <div class="flex items-center justify-center min-h-screen">
                                                            <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
                                                                <div class="text-red-500 mb-4">
                                                                    <svg class="w-16 h-16 mx-auto" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <h3 class="text-2xl font-bold text-center mb-4">403 -
                                                                    Access Denied</h3>
                                                                <p class="text-gray-600 text-center mb-6">
                                                                    You don't have permission to perform this
                                                                    action.<br>
                                                                    Please contact your administrator.
                                                                </p>
                                                                <div class="text-center">
                                                                    <button onclick="hideAccessDeniedModal()"
                                                                        class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                                                                        OK
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quarters Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Quraters</h3>

                    <!-- Add Subject Form -->
                    <form action="{{ route('teacher.quarters.store') }}" method="POST" class="mb-6">
                        @csrf
                        <div class="flex gap-2">
                            <input type="text" name="name"
                                class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Enter quarter name" required>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Save
                            </button>
                        </div>
                    </form>

                    <!-- Subjects Table -->
                    <div class="overflow-x-auto rounded-lg border">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Quarters</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($quarters as $quarter)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $quarter->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex gap-2">
                                                @if (Auth::user()->role === 'admin')
                                                    <form
                                                        action="{{ route('teacher.quarters.update', $quarter->id) }}"
                                                        method="POST" class="flex gap-2">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="text" name="name"
                                                            value="{{ $quarter->name }}"
                                                            class="rounded-md border-gray-300 text-sm" required>
                                                        <button type="submit"
                                                            class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                                            <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                                class="text-white" xmlns="http://www.w3.org/2000/svg">
                                                                <g id="Complete">
                                                                    <g id="edit">
                                                                        <g>
                                                                            <path
                                                                                d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8"
                                                                                fill="none" stroke="currentColor"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2" />
                                                                            <polygon fill="none"
                                                                                points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8"
                                                                                stroke="currentColor"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2" />
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <form
                                                        action="{{ route('teacher.quarters.destroy', $quarter->id) }}"
                                                        method="POST" class="flex gap-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                                            onclick="return confirm('Are you sure you want to delete this subject?')">
                                                            <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                                fill="none" class="text-white"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M3 3L6 6M6 6L10 10M6 6V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18M6 6H4M10 10L14 14M10 10V17M14 14L18 18M14 14V17M18 18L21 21M18 6V12.3906M18 6H16M18 6H20M16 6L15.4558 4.36754C15.1836 3.55086 14.4193 3 13.5585 3H10.4415C9.94239 3 9.47572 3.18519 9.11861 3.5M16 6H11.6133"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <!-- Button -->
                                                    <button type="button"
                                                        class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition"
                                                        onclick="showAccessDeniedModal()">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                            class="text-white" xmlns="http://www.w3.org/2000/svg">
                                                            <g id="Complete">
                                                                <g id="edit">
                                                                    <g>
                                                                        <path
                                                                            d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8"
                                                                            fill="none" stroke="currentColor"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            stroke-width="2" />
                                                                        <polygon fill="none"
                                                                            points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8"
                                                                            stroke="currentColor"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            stroke-width="2" />
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </button>

                                                    <!-- Modal -->
                                                    <div id="accessDeniedModal"
                                                        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">
                                                        <div class="flex items-center justify-center min-h-screen">
                                                            <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
                                                                <div class="text-yellow-500 mb-4">
                                                                    <svg class="w-16 h-16 mx-auto" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <h3 class="text-2xl font-bold text-center mb-4">403 -
                                                                    Access Denied</h3>
                                                                <p class="text-gray-600 text-center mb-6">
                                                                    This action requires special permissions.<br>
                                                                    Contact your administrator to request access.
                                                                </p>
                                                                <div class="text-center">
                                                                    <button onclick="hideAccessDeniedModal()"
                                                                        class="px-6 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                                                        Understood
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Button -->
                                                    <button type="button"
                                                        class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                                        onclick="showAccessDeniedModal()">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                            fill="none" class="text-white"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M3 3L6 6M6 6L10 10M6 6V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18M6 6H4M10 10L14 14M10 10V17M14 14L18 18M14 14V17M18 18L21 21M18 6V12.3906M18 6H16M18 6H20M16 6L15.4558 4.36754C15.1836 3.55086 14.4193 3 13.5585 3H10.4415C9.94239 3 9.47572 3.18519 9.11861 3.5M16 6H11.6133"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </button>

                                                    <!-- Modal -->
                                                    <div id="accessDeniedModal"
                                                        class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">
                                                        <div class="flex items-center justify-center min-h-screen">
                                                            <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
                                                                <div class="text-red-500 mb-4">
                                                                    <svg class="w-16 h-16 mx-auto" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <h3 class="text-2xl font-bold text-center mb-4">403 -
                                                                    Access Denied</h3>
                                                                <p class="text-gray-600 text-center mb-6">
                                                                    You don't have permission to perform this
                                                                    action.<br>
                                                                    Please contact your administrator.
                                                                </p>
                                                                <div class="text-center">
                                                                    <button onclick="hideAccessDeniedModal()"
                                                                        class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                                                                        OK
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showAccessDeniedModal() {
            document.getElementById('accessDeniedModal').classList.remove('hidden');
        }

        function hideAccessDeniedModal() {
            document.getElementById('accessDeniedModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
