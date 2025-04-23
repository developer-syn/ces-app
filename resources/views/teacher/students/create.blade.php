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

                        <!-- Firstname Field -->
                        <div>
                            <label for="firstname" class="block text-sm font-medium text-gray-700">Student firstname</label>
                            <div class="mt-1">
                                <input type="text"
                                       id="firstname"
                                       name="firstname"
                                       required
                                       class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="firstname">
                            </div>
                            @error('firstname')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- middlename Field -->
                        <div>
                            <label for="middlename" class="block text-sm font-medium text-gray-700">Student middlename</label>
                            <div class="mt-1">
                                <input type="text"
                                       id="middlename"
                                       name="middlename"                                       class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="middlename">
                            </div>
                            @error('middlename')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- lastname Field -->
                        <div>
                            <label for="lastname" class="block text-sm font-medium text-gray-700">Student lastname</label>
                            <div class="mt-1">
                                <input type="text"
                                       id="lastname"
                                       name="lastname"
                                       required
                                       class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="lastname">
                            </div>
                            @error('lastname')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- suffix Field -->
                        <div>
                            <label for="suffix" class="block text-sm font-medium text-gray-700">Student suffix</label>
                            <div class="mt-1">
                                <input type="text"
                                       id="suffix"
                                       name="suffix"
                                       class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="suffix">
                            </div>
                            @error('suffix')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birthdate and Age Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Birthdate Field -->
                            <div>
                                <label for="birthdate" class="block text-sm font-medium text-gray-700">Birthdate</label>
                                <div class="mt-1">
                                    <input type="date"
                                           id="birthdate"
                                           name="birthdate"
                                           min="1900-01-01"
                                           max="{{ date('Y-m-d') }}"
                                           required
                                           class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           onchange="calculateAge()">
                                </div>
                                @error('birthdate')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Age Field -->
                            <div>
                                <label for="age" class="block text-sm font-medium text-gray-700">Student Age</label>
                                <div class="mt-1">
                                    <input type="number"
                                           id="age"
                                           name="age"
                                           required
                                           readonly
                                           class="px-4 py-2 block w-full rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="Enter student's age">
                                </div>
                                @error('age')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
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

                        {{-- section Field --}}
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

                        <!-- School Information Field -->
                        <div>
                            <x-input-label for="school_info_id" :value="__('School Name')" class="mt-4" />
                            <select id="school_info_id" name="school_info_id" class="block mt-1 w-full rounded-md"
                                autofocus>
                                <option value="" disabled selected>Select a School Name</option>
                                @foreach ($school_infos as $school_info)
                                    <option value="{{ $school_info->id}}">{{ $school_info->school_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('school_info_id')" class="mt-2" />
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

    <script>
        function calculateAge() {
            const birthdateInput = document.getElementById('birthdate');
            const birthdate = birthdateInput.value;

            if (!birthdate) return; // Exit if no date selected

            const birthDate = new Date(birthdate);
            const today = new Date();

            // Check if birthdate is in the future (even if max date is set, manual entry could bypass it)
            if (birthDate > today) {
                alert("Birthdate cannot be in the future!");
                birthdateInput.value = ''; // Clear the invalid date
                document.getElementById('age').value = ''; // Clear age field
                return;
            }

            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDifference = today.getMonth() - birthDate.getMonth();

            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            document.getElementById('age').value = Math.max(0, age);
        }
    </script>
</x-app-layout>
