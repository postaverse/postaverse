<div>
    @if (auth()->user())
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6 post-creation-container" x-data="{ title: '', content: '' }">
            <div class="bg-gray-800/10 backdrop-blur-sm border border-white/20 overflow-hidden shadow-sm sm:rounded-lg p-4 hover:border-white/30 transition-colors duration-200">
                <form wire:submit.prevent="submit">
                    <div class="mb-4">
                        <x-label for="title" value="{{ __('Title') }}" />
                        <x-input id="title" type="text" class="mt-1 block w-full" wire:model="title"
                            x-model="title" />
                        <div :class="{ 'text-red-500': title.length > 100, 'text-gray-600': title.length <= 100 }"
                            class="mt-1 text-sm">
                            Characters: <span x-text="title.length"></span> / 100
                        </div>
                        @error('title')
                            <span class="error text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-label for="content" value="{{ __('Post') }}" />
                        <x-textarea id="content" class="mt-1 block w-full" wire:model="content" x-model="content" />
                        <div :class="{ 'text-red-500': content.length > 500, 'text-gray-600': content.length <= 500 }"
                            class="mt-1 text-sm">
                            Characters: <span x-text="content.length"></span> / 500
                        </div>
                        @error('content')
                            <span class="error text-red-500">{{ $message }}</span>
                        @enderror
                        <p class="text-sm text-gray-600"><a href="https://www.markdownguide.org/cheat-sheet/"
                                target="_blank">Click here for the Markdown guide.</a></p>
                    </div>

                    
                    <div class="mb-4">
                        <x-label for="photos" value="{{ __('Photos') }}" />
                        <input type="file" wire:model="photos" multiple class="mt-1 block w-full text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm" />

                        @error('photos.*')
                            <span class="error text-red-500">{{ $message }}</span>
                        @enderror

                        @if ($photos)
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                @foreach($photos as $photo)
                                    <img src="{{ $photo->temporaryUrl() }}" />
                                @endforeach
                            </div>
                        @endif
                    </div>
                    

                    <x-button type="submit" class="bg-green-600"><img src="{{ asset('images/icons/blastoff.png') }}"
                            alt="Submit" width="35" height="35" class="p-1 pr-2">{{ __('Post') }}</x-button>

                    @if ($errors->has('rateLimit'))
                        <div class="error text-red-500">
                            {{ $errors->first('rateLimit') }}
                        </div>
                    @endif
                </form>
            </div>
        </div>
    @endif
</div>
