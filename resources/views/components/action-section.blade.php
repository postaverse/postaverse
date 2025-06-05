<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <div
            class="px-4 py-5 sm:p-6 bg-gray-800/10 backdrop-blur-sm border border-white/20 shadow-sm sm:rounded-lg hover:border-white/30 transition-colors duration-200">
            {{ $content }}
        </div>
    </div>
</div>
