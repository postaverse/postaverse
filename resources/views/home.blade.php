<x-app-layout>
    <x-slot name="header">
        <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Home') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 main">
        <livewire:post.create-post />
        <livewire:post.all-posts />
    </div>
</x-app-layout>
