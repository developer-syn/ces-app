<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Teacher') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.teachers.update', $teacher->id) }}" class="w-500">
                        @csrf
                        @method('PUT')
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name', $teacher->name)" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Role Field -->
                        <div class="mt-4">
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required autofocus onchange="toggleTeacherFields()">
                                <option value="" disabled>Select a role</option>
                                <option value="admin" {{ old('role', $teacher->role) == 'admin' ? 'selected' : '' }}>
                                    Admin</option>
                                <option value="teacher"
                                    {{ old('role', $teacher->role) == 'teacher' ? 'selected' : '' }}>Teacher</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Year Level Field (Conditional) -->
                        <div id="yearLevelField" class="mt-4"
                            style="{{ old('role', $teacher->role) == 'teacher' ? '' : 'display: none;' }}">
                            <x-input-label for="year_level_id" :value="__('Year Level Assigned')" />
                            <select id="year_level_id" name="year_level_id"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                {{ old('role', $teacher->role) == 'teacher' ? 'required' : '' }}>
                                <option value="" disabled selected>Select a Year Level</option>
                                @foreach ($yearLevels as $yearLevel)
                                    <option value="{{ $yearLevel->id }}"
                                        {{ old('year_level_id', $teacher->year_level_id) == $yearLevel->id ? 'selected' : '' }}>
                                        {{ $yearLevel->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('year_level_id')" class="mt-2" />
                        </div>

                        <!-- School Information Field -->
                        <div class="mt-4">
                            <x-input-label for="school_info_id" :value="__('School Name')" />
                            <select id="school_info_id" name="school_info_id"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                                <option value="" disabled selected>Select a School Name</option>
                                @foreach ($school_infos as $school_info)
                                    <option value="{{ $school_info->id }}"
                                        {{ old('school_info_id', $teacher->school_info_id) == $school_info->id ? 'selected' : '' }}>
                                        {{ $school_info->school_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('school_info_id')" class="mt-2" />
                        </div>

                        <!-- Section Field (Conditional) -->
                        <div id="sectionField" class="mt-4" style="display: none;">
                            <x-input-label for="section" :value="__('Section')" />
                            <x-text-input id="section" class="block mt-1 w-full" type="text" name="section"
                                :value="old('section', $teacher->section)" autocomplete="section" />
                            <x-input-error :messages="$errors->get('section')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email', $teacher->email)" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <small class="text-gray-500">Leave blank to keep current password</small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                name="password_confirmation" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-3 p-6 border-t">
                            <a class="px-5 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-200"
                                href="{{ route('admin.teachers.index') }}">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button
                                class="px-5 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors duration-200">
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/teacher/edit.js') }}"></script>
</x-app-layout>
