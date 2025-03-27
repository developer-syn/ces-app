{{-- <img src="{{ asset('img/caloc-anLogo.png') }}" alt="logo.png" {{ $attributes }}> --}}
@if (!empty($schoolInfo->logo_path))
<img src="{{ asset($schoolInfo->logo_path) }}" alt="School Logo" class="w-12 h-12 object-cover rounded mr-4" {{ $attributes }}>
@else
<span class="text-gray-500"><img src="{{ asset('img/Seal_of_the_Department_of_Education_of_the_Philippines.png') }}" alt="logo.png" {{ $attributes }}></span>
@endif
