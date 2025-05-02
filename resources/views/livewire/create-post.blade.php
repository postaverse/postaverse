<div>
    @if (auth()->user())
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6" x-data="{ title: '', content: '' }">
            <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                <h2 class="text-2xl font-bold text-white mb-6">Create a New Post</h2>
                <form wire:submit.prevent="submit">
                    <div class="mb-4">
                        <x-label for="title" value="{{ __('Title') }}" class="text-gray-300 mb-2" />
                        <x-input id="title" type="text" class="mt-1 block w-full bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all" 
                            wire:model="title" x-model="title" placeholder="Enter your post title..." />
                        <div :class="{ 'text-red-500': title.length > 128, 'text-gray-500': title.length <= 128 }"
                            class="mt-1 text-sm">
                            Characters: <span x-text="title.length"></span> / 128
                        </div>
                        @error('title')
                            <span class="error text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <x-label for="content" value="{{ __('Content') }}" class="text-gray-300 mb-2" />
                        <x-textarea id="content" class="mt-1 block w-full h-64 bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all" 
                            wire:model="content" x-model="content" placeholder="Write your post content here..."></x-textarea>
                        <div :class="{ 'text-red-500': content.length > 2048, 'text-gray-500': content.length <= 2048 }"
                            class="mt-1 text-sm">
                            Characters: <span x-text="content.length"></span> / 2048
                        </div>
                        @error('content')
                            <span class="error text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <p class="text-sm text-gray-400 mt-2">
                            <a href="https://www.markdownguide.org/cheat-sheet/" target="_blank" class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                Click here for the Markdown guide
                            </a>
                        </p>
                    </div>

                    <div class="mb-6">
                        <x-label for="photos" value="{{ __('Images') }}" class="text-gray-300 mb-2" />
                        <div class="mt-1">
                            <label class="flex justify-center items-center px-4 py-6 bg-gray-800/40 backdrop-blur-sm border border-dashed border-white/20 rounded-lg cursor-pointer hover:border-indigo-500/50 transition-all">
                                <input type="file" wire:model="photos" multiple class="hidden" accept="image/*" />
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-300">Click to upload images</p>
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 5MB each</p>
                                </div>
                            </label>
                        </div>

                        @error('photos.*')
                            <span class="error text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        @if ($photos)
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-4">
                                @foreach($photos as $photo)
                                    <div class="relative group">
                                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg" />
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                                            <button type="button" onclick="removePhoto({{ $loop->index }})" class="text-red-500 hover:text-red-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center">
                        <x-button type="submit" class="bg-indigo-600 hover:bg-indigo-700 transition-colors">
                            <img src="{{ asset('images/icons/blastoff.png') }}" alt="Submit" width="30" height="30" class="p-1 pr-2">
                            {{ __('Publish Post') }}
                        </x-button>

                        @if ($errors->has('rateLimit'))
                            <div class="error text-red-500 text-sm">
                                {{ $errors->first('rateLimit') }}
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            <script>
                function removePhoto(index) {
                    @this.photos.splice(index, 1);
                    @this.photos = [...@this.photos];
                }
            </script>
        </div>
    @endif
</div>
