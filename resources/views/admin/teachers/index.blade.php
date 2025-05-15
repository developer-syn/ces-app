<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teachers') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Search and Filter Section -->
                <div class="mb-4 flex justify-between items-center">
                    <div class="flex gap-4">
                        <input type="text" placeholder="Search..."
                            class="px-4 py-2 rounded-lg border focus:outline-none">
                        {{-- <select class=" py-2 rounded-lg border focus:outline-none">
                            <option value="">All Roles</option>
                            <option value="teacher">Teacher</option>
                            <option value="admin">Admin</option>
                        </select> --}}
                    </div>
                    <a href="{{ route('admin.teachers.create') }}"
                        class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg group flex items-center gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            class="stroke-current group-hover:scale-110 transition-transform"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18 15.5V17.5M18 9.5V11.5M15 13.5H21M12 13.5H10C7.79086 13.5 6 15.2909 6 17.5V19M13 9.5C13 11.7091 11.2091 13.5 9 13.5C6.79086 13.5 5 11.7091 5 9.5C5 7.29086 6.79086 5.5 9 5.5C11.2091 5.5 13 7.29086 13 9.5Z"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="font-semibold tracking-wide">
                            Register New Teacher
                        </span>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Role</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Year Level</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Section</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    School Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($teachers as $teacher)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $teacher->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $teacher->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $teacher->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        style="font-family: 'Times New Roman', Times, serif; font-weight: 700;">
                                        {{ $teacher->yearLevel->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $teacher->section }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $teacher->schoolInfo->school_name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.teachers.edit', $teacher) }}"
                                                class="px-3 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-200 flex items-center gap-1">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24"
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
                                                </svg>Edit
                                            </a>
                                            <form action="{{ route('admin.teachers.destroy', $teacher) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Are you sure?')"
                                                    class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200 flex items-center gap-1">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24"
                                                        fill="none" class="text-white"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M3 3L6 6M6 6L10 10M6 6V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18M6 6H4M10 10L14 14M10 10V17M14 14L18 18M14 14V17M18 18L21 21M18 6V12.3906M18 6H16M18 6H20M16 6L15.4558 4.36754C15.1836 3.55086 14.4193 3 13.5585 3H10.4415C9.94239 3 9.47572 3.18519 9.11861 3.5M16 6H11.6133"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>Delete
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
    <script src="{{ asset('js/teacher/index.js') }}"></script>
</x-app-layout>
