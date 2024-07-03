<x-app-layout>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Blogs') }}
        </h2>
    </x-slot>

    <div class="py-12 main">
        @if (auth()->user() && auth()->user()->email === 'zapalew@gmail.com')
        <livewire:create-blog-post />
        @endif
        <livewire:blog />
    </div>
</x-app-layout>