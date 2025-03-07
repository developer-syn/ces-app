<div class="space-y-4">
    <div>
        <label for="school_id" class="block text-sm font-medium text-gray-700">School ID</label>
        <input type="text"
               name="school_id"
               id="school_id"
               value="{{ old('school_id', $schoolInfo->school_id ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
               required>
    </div>

    <div>
        <label for="school_name" class="block text-sm font-medium text-gray-700">School Name</label>
        <input type="text"
               name="school_name"
               id="school_name"
               value="{{ old('school_name', $schoolInfo->school_name ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
               required>
    </div>

    <div>
        <label for="region" class="block text-sm font-medium text-gray-700">Region</label>
        <input type="text"
               name="region"
               id="region"
               value="{{ old('region', $schoolInfo->region ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
               required>
    </div>

    <div>
        <label for="division" class="block text-sm font-medium text-gray-700">Division</label>
        <input type="text"
               name="division"
               id="division"
               value="{{ old('division', $schoolInfo->division ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
               required>
    </div>

    <div>
        <label for="district" class="block text-sm font-medium text-gray-700">District</label>
        <input type="text"
               name="district"
               id="district"
               value="{{ old('district', $schoolInfo->district ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
               required>
    </div>
</div>
