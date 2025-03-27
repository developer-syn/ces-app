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
                                                <form action="{{ route('teacher.year-levels.update', $yearLevel->id) }}"
                                                    method="POST" class="flex gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" name="name" value="{{ $yearLevel->name }}"
                                                        class="w-10 rounded-md border-gray-300 text-sm" required>
                                                    <button type="submit"
                                                        class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                                        Update
                                                    </button>
                                                </form>
                                                <form
                                                    action="{{ route('teacher.year-levels.destroy', $yearLevel->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                                        onclick="return confirm('Are you sure you want to delete this year level?')">
                                                        Delete
                                                    </button>
                                                </form>
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
                                            <form action="{{ route('teacher.school-years.update', $schoolYear->id) }}"
                                                method="POST" class="gap-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="name" value="{{ $schoolYear->name }}"
                                                    class="rounded-md border-gray-300 text-sm" required>

                                                <label class="flex items-center">
                                                    <input type="hidden" name="current" value="0">
                                                    <!-- Ensures unchecked checkboxes send 0 -->
                                                    <input type="checkbox" name="current" value="1"
                                                        {{ $schoolYear->current ? 'checked' : '' }}>
                                                    <span class="ml-2 text-sm mt-2">Current</span>
                                                </label>

                                                <button type="submit"
                                                    class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition mt-2">
                                                    update
                                                </button>
                                            </form>

                                            <form action="{{ route('teacher.school-years.destroy', $schoolYear->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition mt-2"
                                                    onclick="return confirm('Are you sure you want to delete this year level?')">
                                                    Delete
                                                </button>
                                            </form>
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
                                                <form action="{{ route('teacher.subjects.update', $subject->id) }}"
                                                    method="POST" class="flex gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" name="name"
                                                        value="{{ $subject->name }}"
                                                        class="rounded-md border-gray-300 text-sm" required>
                                                    <button type="submit"
                                                        class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                                        Update
                                                    </button>
                                                </form>
                                                <form action="{{ route('teacher.subjects.destroy', $subject->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                                        onclick="return confirm('Are you sure you want to delete this subject?')">
                                                        Delete
                                                    </button>
                                                </form>
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
                                                <form action="{{ route('teacher.quarters.update', $quarter->id) }}"
                                                    method="POST" class="flex gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" name="name"
                                                        value="{{ $quarter->name }}"
                                                        class="rounded-md border-gray-300 text-sm" required>
                                                    <button type="submit"
                                                        class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                                                        Update
                                                    </button>
                                                </form>
                                                <form action="{{ route('teacher.quarters.destroy', $quarter->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                                        onclick="return confirm('Are you sure you want to delete this subject?')">
                                                        Delete
                                                    </button>
                                                </form>
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
</x-app-layout>
