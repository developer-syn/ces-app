<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">
                School ID <span class="text-red-500">*</span>
            </label>
            <input type="text" name="school_id" id="school_id" required
                value="{{ old('school_id', $schoolInfo->school_id ?? '') }}"
                class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 @error('school_id') border-red-500 @enderror">
            @error('school_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="region" class="block text-sm font-medium text-gray-700 mb-1">
                Region <span class="text-red-500">*</span>
            </label>
            <input type="text" name="region" id="region" required
                value="{{ old('region', $schoolInfo->region ?? '') }}"
                class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 @error('region') border-red-500 @enderror">
            @error('region')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="school_name" class="block text-sm font-medium text-gray-700 mb-1">
            School Name <span class="text-red-500">*</span>
        </label>
        <input type="text" name="school_name" id="school_name" required
            value="{{ old('school_name', $schoolInfo->school_name ?? '') }}"
            class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 @error('school_name') border-red-500 @enderror">
        @error('school_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="district" class="block text-sm font-medium text-gray-700 mb-1">
            District <span class="text-red-500">*</span>
        </label>
        <input type="text" name="district" id="district" required
            value="{{ old('district', $schoolInfo->district ?? '') }}"
            class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 @error('district') border-red-500 @enderror">
        @error('district')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="division" class="block text-sm font-medium text-gray-700 mb-1">
            Division <span class="text-red-500">*</span>
        </label>
        <input type="text" name="division" id="division" required
            value="{{ old('division', $schoolInfo->division ?? '') }}"
            class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 @error('division') border-red-500 @enderror">
        @error('division')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="principal_name" class="block text-sm font-medium text-gray-700 mb-1">
            Principal Name <span class="text-red-500">*</span>
        </label>
        <input type="text" name="principal_name" id="principal_name" required
            value="{{ old('principal_name', $schoolInfo->principal_name ?? '') }}"
            class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 @error('principal_name') border-red-500 @enderror">
        @error('principal_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="logo_path" class="block text-sm font-medium text-gray-700 mb-1">
            School Logo
        </label>
        <div class="flex items-center gap-4">
            <input type="file" name="logo_path" id="logo_path" accept="image/*"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors duration-200">

            @if (!empty($schoolInfo->logo_path))
                <div class="shrink-0 relative group">
                    <img src="{{ asset($schoolInfo->logo_path) }}" alt="School logo"
                        class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                    <div class="absolute inset-0 bg-black bg-opacity-50 hidden group-hover:flex items-center justify-center rounded-lg">
                        <a href="{{ asset($schoolInfo->logo_path) }}" target="_blank"
                           class="text-white hover:text-indigo-200 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
