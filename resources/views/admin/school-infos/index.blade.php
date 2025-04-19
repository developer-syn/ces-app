<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('School Information') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Manage Schools</h3>
                    <button onclick="document.getElementById('createModal').style.display='flex'"
                        class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg group flex items-center gap-3">
                        <svg width="20px" height="20px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                            fill="none">
                            <path fill="#ffffff" fill-rule="evenodd"
                                d="M9 17a1 1 0 102 0v-6h6a1 1 0 100-2h-6V3a1 1 0 10-2 0v6H3a1 1 0 000 2h6v6z" />
                        </svg>
                        <span class="font-semibold tracking-wide">
                            Register New School
                        </span>
                    </button>
                </div>

                <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                    <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                        <thead>
                            <tr class="text-left">
                                <th
                                    class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    School ID
                                </th>
                                <th
                                    class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    School Name
                                </th>
                                <th
                                    class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Region
                                </th>
                                <th
                                    class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Division
                                </th>
                                <th
                                    class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    District
                                </th>
                                <th
                                    class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Principal Name
                                </th>
                                <th
                                    class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    logo
                                </th>
                                <th
                                    class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schoolInfos as $schoolInfo)
                                <tr class="hover:bg-gray-50">
                                    <td class="border-t border-gray-200 px-6 py-4">{{ $schoolInfo->school_id }}</td>
                                    <td class="border-t border-gray-200 px-6 py-4">{{ $schoolInfo->school_name }}
                                    </td>
                                    <td class="border-t border-gray-200 px-6 py-4">{{ $schoolInfo->region }}</td>
                                    <td class="border-t border-gray-200 px-6 py-4">{{ $schoolInfo->division }}</td>
                                    <td class="border-t border-gray-200 px-6 py-4">{{ $schoolInfo->district }}</td>
                                    <td class="border-t border-gray-200 px-6 py-4">{{ $schoolInfo->principal_name }}
                                    </td>
                                    <td class="border-t border-gray-200 px-6 py-4">
                                        @if ($schoolInfo->logo_path)
                                            <img src="{{ asset($schoolInfo->logo_path) }}" alt="Logo"
                                                class="w-12 h-12 object-cover rounded">
                                        @else
                                            <span class="text-gray-500">No Logo</span>
                                        @endif
                                    </td>
                                    <td class="border-t border-gray-200 px-6 py-4">
                                        <div class="flex space-x-2">
                                            <button type="button"
                                                class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-200 flex items-center gap-1"
                                                onclick="document.getElementById('editModal{{ $schoolInfo->id }}').style.display='flex'">
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
                                            </button>
                                            <form action="{{ route('admin.school-infos.destroy', $schoolInfo->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this school?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200 flex items-center gap-1">
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

                <!-- Improved Create Modal -->
                <div id="createModal"
                    class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center p-4 z-50 transition-opacity duration-300">
                    <div
                        class="bg-white rounded-xl shadow-2xl max-w-2xl w-full overflow-hidden transform transition-all">
                        <div class="flex justify-between items-center p-6 border-b">
                            <h3 class="text-xl font-semibold text-gray-900">Add New School</h3>
                            <button onclick="document.getElementById('createModal').style.display='none'"
                                class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <form action="{{ route('admin.school-infos.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="p-6 space-y-6">
                                @include('admin.school-infos._form-fields')
                            </div>
                            <div class="flex justify-end gap-3 p-6 border-t">
                                <button type="button"
                                    onclick="document.getElementById('createModal').style.display='none'"
                                    class="px-5 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-200">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-5 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors duration-200">
                                    Save School
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Edit Modals -->
                @foreach ($schoolInfos as $schoolInfo)
                    <div id="editModal{{ $schoolInfo->id }}"
                        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center p-4 z-50 transition-opacity duration-300">
                        <div
                            class="bg-white rounded-xl shadow-2xl max-w-2xl w-full overflow-hidden transform transition-all">
                            <div class="flex justify-between items-center p-6 border-b">
                                <h3 class="text-xl font-semibold text-gray-900">Edit School Information</h3>
                            </div>
                            <form action="{{ route('admin.school-infos.update', $schoolInfo->id) }}" method="POST"
                                id="editForm{{ $schoolInfo->id }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!-- Form fields -->
                                <div class="p-6 space-y-6">
                                    @include('admin.school-infos._form-fields')
                                </div>
                                <div class="flex justify-end gap-3 p-6 border-t">
                                    <button type="button"
                                        onclick="document.getElementById('editModal{{ $schoolInfo->id }}').style.display='none'"
                                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition-colors duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition-colors duration-200">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
            @endforeach
        </div>
    </div>
    </div>
</x-app-layout>
