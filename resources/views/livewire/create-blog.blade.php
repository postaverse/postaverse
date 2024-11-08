<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6" x-data="{ title: '', content: '' }">
    <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
        <form wire:submit.prevent="submit">
            <div class="mb-4">
                <x-label for="title" value="{{ __('Title') }}" />
                <x-input id="title" type="text" class="mt-1 block w-full" wire:model="title" x-model="title" />
                <div :class="{ 'text-red-500': title.length > 255, 'text-gray-600': title.length <= 255 }"
                    class="mt-1 text-sm">
                    Characters: <span x-text="title.length"></span> / 255
                </div>
                @error('title')
                    <span class="error text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label for="content" value="{{ __('Post') }}" />
                <x-textarea id="content" class="mt-1 block w-full" wire:model="content" x-model="content" />
                <div :class="{ 'text-red-500': content.length > 999999999, 'text-gray-600': content.length <= 999999999 }"
                    class="mt-1 text-sm">
                    Characters: <span x-text="content.length"></span> / ∞
                </div>
                @error('content')
                    <span class="error text-red-500">{{ $message }}</span>
                @enderror
                @if (session()->has('message'))
                    <div class="text-green-500 mt-1">
                        {{ session('message') }}
                    </div>
                @endif
                <p class="text-sm text-gray-600"><a href="https://www.markdownguide.org/cheat-sheet/"
                        target="_blank">Click here for the Markdown guide.</a></p>
            </div>
            <x-button type="submit" class="bg-green-600"><img src="{{ asset('images/icons/blastoff.png') }}" alt="Submit"
                    width="35" height="35" class="p-1 pr-2">{{ __('Post') }}</x-button>
            @if ($errors->has('rateLimit'))
                <div class="error text-red-500">
                    {{ $errors->first('rateLimit') }}
                </div>
            @endif
        </form>
    </div>
</div>
