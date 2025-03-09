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
                    <form method="POST" action="{{ route('teacher.students.store') }}" class="space-y-6">
                        @csrf
                        <!-- LRN_num Field -->
                        <div>
                            <label for="LRN_num" class="block text-sm font-medium text-gray-700">Student LRN number</label>
                            <div class="mt-1">
                                <input type="number"
                                       id="LRN_num"
                                       name="LRN_num"
                                       required
                                       class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter LRN number">
                            </div>
                            @error('LRN_num')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Student Name</label>
                            <div class="mt-1">
                                <input type="text"
                                       id="name"
                                       name="name"
                                       required
                                       class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter student's name">
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Gender Field -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Student Gender</label>
                            <div class="mt-1">
                                <select name="gender"
                                        id="gender"
                                        required
                                        class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="" disabled selected>--Select Gender--</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    {{-- <option value="other">Other</option> --}}
                                </select>
                            </div>
                            @error('gender')
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
                                       required
                                       class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            @error('birthdate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- section --}}
                        <div>
                            <label for="section" class="block text-sm font-medium text-gray-700">Section</label>
                            <div class="mt-1">
                                <input type="text"
                                       id="section"
                                       name="section"
                                       value="{{ $user->section }}"
                                       required
                                       class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter student's Section">
                            </div>
                            @error('section')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- School Year Field -->
                        <div>
                            <label for="school_year_id" class="block text-sm font-medium text-gray-700">School Year</label>
                            <div class="mt-1">
                                <select name="school_year_id"
                                        id="school_year_id"
                                        required
                                        class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">--Select School Year--</option>
                                    @foreach ($schoolYears as $schoolYear)
                                        <option value="{{ $schoolYear->id }}">{{ $schoolYear->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('school_year_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Year Level Field -->
                        <div>
                            <label for="year_level_id" class="block text-sm font-medium text-gray-700">Year Level</label>
                            <div class="mt-1">
                                <select name="year_level_id"
                                        id="year_level_id"
                                        required
                                        class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">--Select Year Level--</option>
                                    @foreach ($yearLevels as $yearLevel)
                                        <option value="{{ $yearLevel->id }}">{{ $yearLevel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('year_level_id')
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
