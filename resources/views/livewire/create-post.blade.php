<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6" x-data="{ title: '', content: '' }">
    <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
        <form wire:submit.prevent="submit">
            <div class="mb-4">
                <x-label for="title" value="{{ __('Title') }}" />
                <x-input id="title" type="text" class="mt-1 block w-full" wire:model="title" x-model="title" />
                <div :class="{'text-red-500': title.length > 100, 'text-gray-600': title.length <= 100}" class="mt-1 text-sm">
                    Characters: <span x-text="title.length"></span> / 100
                </div>
                @error('title') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <x-label for="content" value="{{ __('Post') }}" />
                <x-textarea id="content" class="mt-1 block w-full" wire:model="content" x-model="content" />
                <div :class="{'text-red-500': content.length > 500, 'text-gray-600': content.length <= 500}" class="mt-1 text-sm">
                    Characters: <span x-text="content.length"></span> / 500
                </div>
                @error('content') <span class="error text-red-500">{{ $message }}</span> @enderror
            </div>
            <x-button type="submit">{{ __('Submit') }}</x-button>
        </form>
    </div>
</div>