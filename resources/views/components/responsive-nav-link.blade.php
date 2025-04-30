@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-600 text-start text-base font-medium text-indigo-300 bg-gray-800/10 backdrop-blur-sm border border-white/20 rounded-r-md focus:outline-none focus:text-indigo-200 focus:border-indigo-300 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-400 hover:text-gray-200 hover:bg-gray-800/10 hover:backdrop-blur-sm hover:border-white/20 hover:rounded-r-md focus:outline-none focus:text-gray-200 focus:border-white/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
