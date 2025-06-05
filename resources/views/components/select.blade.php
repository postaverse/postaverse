@props(['disabled' => false])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'border-white/20 bg-gray-800/10 backdrop-blur-sm text-gray-300 focus:border-white/30 focus:ring-indigo-600 rounded-md shadow-sm p-2 w-full',
]) !!}>
    {{ $slot }}
</select>
