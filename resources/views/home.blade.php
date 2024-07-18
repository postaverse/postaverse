<x-app-layout>
    <x-slot name="header">
        <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Home') }}
            </h2>
            <livewire:redeem-meteors />
        </div>
    </x-slot>

    <div class="py-12 main">
        <livewire:create-post />
        <livewire:all-posts />
    </div>
</x-app-layout>