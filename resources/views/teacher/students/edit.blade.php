<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('teacher.students.update', $student->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Student Name</label>
                            <div class="mt-1">
                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $student->name) }}"
                                       required
                                       class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter student's name">
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birthdate Field -->
                        <div>
                            <label for="birthdate" class="block text-sm font-medium text-gray-700">Birthdate</label>
                            <div class="mt-1">
                                <input type="date"
                                       id="birthdate"
                                       name="birthdate"
                                       value="{{ old('birthdate', $student->birthdate) }}"
                                       required
                                       class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            @error('birthdate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Birthdate Field -->
                        <div>
                            <label for="section" class="block text-sm font-medium text-gray-700">Section</label>
                            <div class="mt-1">
                                <input type="date"
                                       id="section"
                                       name="section"
                                       value="{{ old('section', $student->section) }}"
                                       required
                                       class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            @error('section')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Year Level Field -->
                        <div>
                            <label for="year_level_id" class="block text-sm font-medium text-gray-700">Year Level</label>
                            <div class="mt-1">
                                <div class="mt-1">
                                    <select id="year_level_id"
                                            name="year_level_id"
                                            required
                                            class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">--Select Year Level--</option>
                                        @foreach ($yearLevels as $yearLevel)
                                            <option value="{{ $yearLevel->id }}"
                                                {{ old('year_level_id', $student->year_level_id) == $yearLevel->id ? 'selected' : '' }}>
                                                {{ $yearLevel->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('year_level_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- School Year Field -->
                        <div>
                            <label for="school_year_id" class="block text-sm font-medium text-gray-700">School Year</label>
                            <div class="mt-1">
                                <div class="mt-1">
                                    <select id="school_year_id"
                                            name="school_year_id"
                                            required
                                            class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">--Select School Year--</option>
                                        @foreach ($schoolYears as $schoolYear)
                                            <option value="{{ $schoolYear->id }}"
                                                {{ old('school_year_id', $student->school_year_id) == $schoolYear->id ? 'selected' : '' }}>
                                                {{ $schoolYear->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('school_year_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-4 pt-4">
                            <a href="{{ route('teacher.students.index') }}"
                               class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Register Student
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
