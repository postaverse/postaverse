<x-app-layout>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12 main">
        <livewire:create-post />
        <livewire:all-posts />
    </div>
    <div>
        <div id="stars" class="stars"></div>
    </div>
    <script src="stars.js"></script>
</x-app-layout>