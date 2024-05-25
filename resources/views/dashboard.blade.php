<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                <form>
                    <div class="mb-4">
                        <x-label for="name" value="{{ __('Title') }}" />
                        <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-label for="bio" value="{{ __('Post') }}" />
                        <x-textarea id="bio" class="mt-1 block w-full" wire:model="state.bio" />
                        <x-input-error for="bio" class="mt-2" />
                    </div>

                    <x-button wire:loading.attr="disabled" wire:target="photo">
                        {{ __('Post') }}
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
