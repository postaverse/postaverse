@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center px-3 py-1 pt-2 border-b-2 border-indigo-600 text-sm font-medium leading-5 text-gray-100 !text-gray-100 bg-gray-800/10 backdrop-blur-sm border border-white/20 rounded-t-md focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3 py-1 pt-2 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-400 !text-gray-400 hover:text-gray-300 hover:!text-gray-300 hover:border-white/30 focus:outline-none focus:text-gray-300 focus:!text-gray-300 focus:border-white/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
