<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="w-full bg-white shadow-md rounded-lg p-6 text-center">
                    <h1 class="text-4xl font-bold text-red-600">403</h1>
                    <p class="text-gray-700 mt-2">Unauthorized Access</p>
                    <p class="text-gray-500 mt-2">
                        {{ $message ?? 'You do not have permission to access this page.' }}
                    </p>
                    <a href="{{ url()->previous() }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Go Back to Previous Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
