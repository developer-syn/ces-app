<!-- Responsive Navigation Menu -->
<nav x-data="{ open: false, schoolManagementOpen: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class=" mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
                <div class="hidden sm:flex sm:items-center sm:ms-6 mt-1">
                    <x-dropdown align="left" width="36">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ __('School Management') }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('admin.school-infos.index')">
                                {{ __('School Information') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.teachers.index')">
                                {{ __('Teachers lists') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('teacher.students.index')">
                                {{ __('Students lists') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('teacher.year-levels-subjects-school-years-quarters')">
                                {{ __('YSYQ Management') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('teacher.class-records.index')" :active="request()->routeIs('teacher.class-records.index')">
                        {{ __('Class Records') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('teacher.summary_quarterly_grades.index')" :active="request()->routeIs('teacher.summary_quarterly_grades.index')">
                        {{ __('Quarterly Grades') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('teacher.school-forms-10.index')" :active="request()->routeIs('teacher.school-forms-10.index')">
                        {{ __('School Form 10') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('teacher.reports.students-report')" :active="request()->routeIs('teacher.reports.students-report')">
                        {{ __('Student Reports') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="fixed top-20 right-4 z-50 space-y-4">
                    @if (session('success'))
                        <div id="success-alert" class="flex items-center p-4 max-w-md bg-green-50 border-l-4 border-green-500 rounded-r shadow-lg transform transition-all duration-500 animate__animated animate__fadeInRight">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div id="error-alert" class="flex items-center p-4 max-w-md bg-red-50 border-l-4 border-red-500 rounded-r shadow-lg transform transition-all duration-500 animate__animated animate__fadeInRight">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div id="validation-alert" class="flex items-center p-4 max-w-md bg-red-50 border-l-4 border-red-500 rounded-r shadow-lg transform transition-all duration-500 animate__animated animate__fadeInRight">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <ul class="text-sm font-medium text-red-800">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- School Management Dropdown -->
            <div class="px-4">
                <button @click="schoolManagementOpen = !schoolManagementOpen" class="w-full flex justify-between items-center py-2 text-left text-gray-600 hover:text-gray-900">
                    <span>{{ __('School Management') }}</span>
                    <svg class="h-4 w-4" :class="{ 'transform rotate-180': schoolManagementOpen }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="schoolManagementOpen" class="pl-4 space-y-1">
                    <x-responsive-nav-link :href="route('admin.school-infos.index')">
                        {{ __('School Information') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.teachers.index')">
                        {{ __('Teachers lists') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('teacher.students.index')">
                        {{ __('Students lists') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('teacher.year-levels-subjects-school-years-quarters')">
                        {{ __('YSYQ Management') }}
                    </x-responsive-nav-link>
                </div>
            </div>

            <x-responsive-nav-link :href="route('teacher.class-records.index')" :active="request()->routeIs('teacher.class-records.index')">
                {{ __('Class Records') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('teacher.summary_quarterly_grades.index')" :active="request()->routeIs('teacher.summary_quarterly_grades.index')">
                {{ __('Quarterly Grades') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('teacher.school-forms-10.index')" :active="request()->routeIs('teacher.school-forms-10.index')">
                {{ __('School Form 10') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('teacher.reports.students-report')" :active="request()->routeIs('teacher.reports.students-report')">
                {{ __('Student Reports') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = ['success-alert', 'error-alert', 'validation-alert'];
        alerts.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                setTimeout(() => {
                    element.classList.remove('animate__fadeInRight');
                    element.classList.add('animate__fadeOutRight');
                    setTimeout(() => element.remove(), 1000);
                }, 3000);
            }
        });
    });

    // Add this to your Alpine.js component or script
    document.addEventListener('alpine:init', () => {
        Alpine.data('navigation', () => ({
            open: false,
            schoolManagementOpen: false
        }));
    });
</script>
