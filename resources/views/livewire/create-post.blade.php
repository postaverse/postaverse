<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
    <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">

        <form wire:submit="submit">
            <div class="mb-4">
                <x-label for="title" value="{{ __('Title') }}" />
                <x-input id="title" type="text" class="mt-1 block w-full" wire:model="title" />
                <div class="mt-1 text-sm text-gray-600">
                    Characters: {{ strlen($title) }} / 100
                </div>
                @error('title') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <x-label for="content" value="{{ __('Post') }}" />
                <x-textarea id="content" class="mt-1 block w-full" wire:model="content" />
                <div class="mt-1 text-sm text-gray-600">
                    Characters: {{ strlen($content) }} / 500
                </div>
                @error('content') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>

            <x-button type="submit">
                {{ __('Post') }}
            </x-button>
        </form>
    </div>
</div>