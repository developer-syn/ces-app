<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('School Information') }}
        </h2>
    </x-slot>

    <div class="py-4">
        @if (session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Manage Schools</h3>
                    <button onclick="document.getElementById('createModal').style.display='flex'"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors duration-200">
                        Add New School
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
                                    <td class="border-t border-gray-200 px-6 py-4">
                                        <div class="flex space-x-2">
                                            <button type="button"
                                                class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors duration-200"
                                                onclick="document.getElementById('editModal{{ $schoolInfo->id }}').style.display='flex'">
                                                edit
                                            </button>
                                            <form action="{{ route('admin.school-infos.destroy', $schoolInfo->id) }}"
                                                method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors duration-200">
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

    <!-- Create Modal -->
    <div id="createModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden items-center justify-center">
        <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add New School Information</h3>
                <form action="{{ route('admin.school-infos.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <!-- Form fields -->
                        @include('admin.school-infos._form-fields')

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" onclick="document.getElementById('createModal').style.display='none'"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition-colors duration-200">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition-colors duration-200">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modals -->
    @foreach ($schoolInfos as $schoolInfo)
        <div id="editModal{{ $schoolInfo->id }}"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden items-center justify-center">
            <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Edit School Information</h3>
                    <form action="{{ route('admin.school-infos.update', $schoolInfo->id) }}" method="POST"
                        id="editForm{{ $schoolInfo->id }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <!-- Form fields -->
                            @include('admin.school-infos._form-fields')

                            <div class="flex justify-end space-x-3 mt-6">
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        // Function to validate forms
        function validateForm(form) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            return isValid;
        }

        // Add event listeners when document is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Add form validation to all forms
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!validateForm(this)) {
                        e.preventDefault();
                    }
                });
            });

            // Close modal when clicking outside
            const modals = document.querySelectorAll('[id^="createModal"], [id^="editModal"]');
            modals.forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.style.display = 'none';
                    }
                });
            });

            // Show validation errors in modal if needed
            @if ($errors->any())
                @if (old('_method') == 'PUT')
                    // For edit form errors
                    const editId = "{{ old('id') }}";
                    if (editId) {
                        document.getElementById('editModal' + editId).style.display = 'flex';
                    }
                @else
                    // For create form errors
                    document.getElementById('createModal').style.display = 'flex';
                @endif
            @endif
        });
    </script>
</x-app-layout>
