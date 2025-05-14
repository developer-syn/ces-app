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
                    <form method="POST" action="{{ route('teacher.students.store') }}" class="space-y-6" id="studentForm">
                        @csrf
                        <!-- LRN Field -->
                        <div>
                            <label for="LRN_num" class="block text-sm font-medium text-gray-700">Student LRN number</label>
                            <div class="mt-1">
                                <input type="number" id="LRN_num" name="LRN_num" required
                                    class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('LRN_num') border-red-500 @enderror"
                                    placeholder="Enter LRN number"
                                    value="{{ old('LRN_num') }}">
                                @error('LRN_num')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Name Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" id="lastname" name="lastname" required
                                    class="mt-1 px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('lastname') border-red-500 @enderror"
                                    value="{{ old('lastname') }}">
                                @error('lastname')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" id="firstname" name="firstname" required
                                    class="mt-1 px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('firstname') border-red-500 @enderror"
                                    value="{{ old('firstname') }}">
                                @error('firstname')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="middlename" class="block text-sm font-medium text-gray-700">Middle Name</label>
                                <input type="text" id="middlename" name="middlename"
                                    class="mt-1 px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('middlename') border-red-500 @enderror"
                                    value="{{ old('middlename') }}">
                                @error('middlename')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Suffix -->
                        <div>
                            <label for="suffix" class="block text-sm font-medium text-gray-700">Suffix</label>
                            <input type="text" id="suffix" name="suffix"
                                class="mt-1 px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('suffix') border-red-500 @enderror"
                                value="{{ old('suffix') }}">
                            @error('suffix')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birthdate and Age -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="birthdate" class="block text-sm font-medium text-gray-700">Birthdate</label>
                                <input type="date" id="birthdate" name="birthdate" required
                                    class="mt-1 px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('birthdate') border-red-500 @enderror"
                                    min="{{ date('Y-m-d', strtotime('-60 years')) }}"
                                    max="{{ date('Y-m-d', strtotime('-5 years')) }}"
                                    value="{{ old('birthdate') }}"
                                    onchange="calculateAge()">
                                @error('birthdate')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                                <input type="number" id="age" name="age" required readonly
                                    class="mt-1 px-4 py-2 block w-full rounded-lg border bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('age') border-red-500 @enderror"
                                    value="{{ old('age') }}">
                                @error('age')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <select id="gender" name="gender" required
                                class="mt-1 px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('gender') border-red-500 @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- School Information -->
                        <div>
                            <label for="school_info_id" class="block text-sm font-medium text-gray-700">School</label>
                            <select id="school_info_id" name="school_info_id" required
                                class="mt-1 px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('school_info_id') border-red-500 @enderror">
                                <option value="">Select School</option>
                                @foreach ($school_infos as $school)
                                    <option value="{{ $school->id }}" {{ old('school_info_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->school_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('school_info_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Year Level -->
                        <div>
                            <label for="year_level_id" class="block text-sm font-medium text-gray-700">Year Level</label>
                            <select id="year_level_id" name="year_level_id" required
                                class="mt-1 px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('year_level_id') border-red-500 @enderror">
                                <option value="">Select Year Level</option>
                                @foreach ($yearLevels as $level)
                                    <option value="{{ $level->id }}" {{ old('year_level_id') == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('year_level_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- School Year -->
                        <div>
                            <label for="school_year_id" class="block text-sm font-medium text-gray-700">School Year</label>
                            <select id="school_year_id" name="school_year_id" required
                                class="mt-1 px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('school_year_id') border-red-500 @enderror">
                                <option value="">Select School Year</option>
                                @foreach ($schoolYears as $year)
                                    <option value="{{ $year->id }}" {{ old('school_year_id') == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('school_year_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Section -->
                        <div>
                            <label for="section" class="block text-sm font-medium text-gray-700">Section</label>
                            <input type="text" id="section" name="section" required
                                class="mt-1 px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('section') border-red-500 @enderror"
                                value="{{ old('section', $user->section ?? '') }}">
                            @error('section')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

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

    <script>
        // Calculate age from birthdate
        function calculateAge() {
            const birthdateInput = document.getElementById('birthdate');
            const ageInput = document.getElementById('age');
            const birthdate = birthdateInput.value;

            if (!birthdate) {
                ageInput.value = '';
                return;
            }

            const birthDate = new Date(birthdate);
            const today = new Date();

            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDifference = today.getMonth() - birthDate.getMonth();

            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            ageInput.value = age;
        }

        // Form validation and error focus
        document.addEventListener('DOMContentLoaded', function() {
            // Focus on first error field if any
            const firstErrorField = document.querySelector('.text-red-600');
            if (firstErrorField) {
                const inputId = firstErrorField.getAttribute('data-input-id') ||
                               firstErrorField.previousElementSibling.querySelector('input, select')?.id;
                if (inputId) {
                    const inputElement = document.getElementById(inputId);
                    if (inputElement) {
                        inputElement.focus();
                        inputElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }

            // Age validation on form submit
            const form = document.getElementById('studentForm');
            form.addEventListener('submit', function(event) {
                const ageInput = document.getElementById('age');
                const age = parseInt(ageInput.value);

                if (age < 5 || age > 60) {
                    event.preventDefault();
                    const errorElement = document.querySelector('[data-input-id="age"]') ||
                                        ageInput.nextElementSibling;
                    if (errorElement) {
                        errorElement.textContent = "Age must be between 5 and 60 years";
                        errorElement.classList.remove('hidden');
                    }
                    ageInput.classList.add('border-red-500');
                    ageInput.focus();
                    ageInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                return true;
            });
        });
    </script>
</x-app-layout>
