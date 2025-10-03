<div class="bg-zinc-900 border border-zinc-700 rounded-xl shadow-lg p-6 mb-6 transition-all duration-200 hover:shadow-xl">
    <div class="flex items-start space-x-4">
        <!-- User Avatar -->
        <div class="flex-shrink-0">
            @if(auth()->user()->avatar)
                <img class="h-12 w-12 rounded-full object-cover ring-2 ring-zinc-600 transition-transform duration-200 hover:scale-105" src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->display_name }}">
            @else
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-zinc-700 to-zinc-600 flex items-center justify-center ring-2 ring-zinc-600 transition-transform duration-200 hover:scale-105">
                    <span class="text-zinc-200 font-medium text-lg">{{ substr(auth()->user()->first_name, 0, 1) }}</span>
                </div>
            @endif
        </div>

        <!-- Post Form -->
        <div class="flex-1">
            <form wire:submit="createPost" class="space-y-4">
                <!-- Group selector (only shown when user has joined groups) -->
                @if(!empty($availableGroups) && count($availableGroups) > 0)
                    <div class="mb-3">
                        <label class="sr-only">Post to group</label>
                        <select
                            wire:model="groupId"
                            class="w-full border border-zinc-600 bg-zinc-700 text-zinc-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                            {{ $isLoading ? 'disabled' : '' }}
                        >
                            <option value="">📣 Post to your timeline</option>
                            @foreach($availableGroups as $g)
                                <option value="{{ $g->id }}">r/{{ $g->slug }} — {{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Content Textarea -->
                <div class="relative">
                    <textarea 
                        wire:model="content" 
                        placeholder="What's on your mind, {{ auth()->user()->first_name }}?"
                        class="w-full resize-none border-0 focus:ring-0 text-lg placeholder-zinc-400 bg-transparent text-zinc-100 min-h-[80px] focus:outline-none transition-all duration-200"
                        rows="3"
                        {{ $isLoading ? 'disabled' : '' }}
                    ></textarea>
                    @error('content') 
                        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Media Preview -->
                @if(!empty($media))
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 p-4 bg-zinc-800/50 rounded-lg">
                        @foreach($media as $index => $file)
                            <div class="relative group">
                                <img src="{{ $file->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg transition-transform duration-200 group-hover:scale-105">
                                <button 
                                    type="button"
                                    wire:click="removeMedia({{ $index }})"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1.5 hover:bg-red-600 transition-colors duration-200 opacity-0 group-hover:opacity-100"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Location Input (when adding location) -->
                @if($showLocationInput)
                    <div class="p-4 bg-zinc-800/50 rounded-lg">
                        <div class="flex items-center space-x-2 mb-2">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-sm text-zinc-300 font-medium">Add Location</span>
                        </div>
                        <input 
                            wire:model="location" 
                            type="text" 
                            placeholder="Where are you?"
                            class="w-full border border-zinc-600 bg-zinc-700 text-zinc-100 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                            {{ $isLoading ? 'disabled' : '' }}
                        >
                        @error('location') 
                            <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>
                @endif

                {{-- Group context pill when creating inside a group --}}
                @if($groupId)
                    <div class="mb-2">
                        <div class="inline-flex items-center bg-zinc-800 border border-zinc-700 text-zinc-300 text-sm px-3 py-1 rounded-lg">
                            <svg class="w-4 h-4 mr-2 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/></svg>
                            Posting to <strong class="ml-1">r/{{ \App\Models\Group::find($groupId)?->name }}</strong>
                        </div>
                        <input type="hidden" wire:model="groupId">
                    </div>
                @endif

                <!-- Post Options -->
                <div class="flex items-center justify-between pt-4 border-t border-zinc-700">
                    <div class="flex items-center space-x-6">
                        <!-- Media Upload -->
                        <label class="flex items-center space-x-2 cursor-pointer text-zinc-400 hover:text-blue-400 transition-colors duration-200 group">
                            <div class="p-2 rounded-lg group-hover:bg-blue-500/10 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Photo</span>
                            <input type="file" wire:model="media" multiple accept="image/*" class="hidden" {{ $isLoading ? 'disabled' : '' }}>
                        </label>

                        <!-- Location Toggle -->
                        <button 
                            type="button"
                            wire:click="toggleLocationInput"
                            class="flex items-center space-x-2 text-zinc-400 hover:text-blue-400 transition-colors duration-200 group {{ $showLocationInput ? 'text-blue-400' : '' }}"
                            {{ $isLoading ? 'disabled' : '' }}
                        >
                            <div class="p-2 rounded-lg group-hover:bg-blue-500/10 transition-colors duration-200 {{ $showLocationInput ? 'bg-blue-500/10' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Location</span>
                        </button>

                        <!-- Visibility Selector -->
                        <div class="flex items-center space-x-2">
                            <div class="p-2 rounded-lg text-zinc-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <select 
                                wire:model="visibility"
                                class="border border-zinc-600 bg-zinc-700 text-zinc-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                                {{ $isLoading ? 'disabled' : '' }}
                            >
                                <option value="public">🌐 Public</option>
                                <option value="friends">👥 Friends</option>
                                <option value="private">🔒 Only me</option>
                            </select>
                        </div>
                    </div>

                    <!-- Post Button -->
                    <button 
                        type="submit" 
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl"
                        :disabled="!$wire.content.trim() || $wire.isLoading"
                        {{ $isLoading ? 'disabled' : '' }}
                    >
                        @if($isLoading)
                            <div class="flex items-center space-x-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Posting...</span>
                            </div>
                        @else
                            <span>Share Post</span>
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mt-4 bg-gradient-to-r from-green-900 to-green-800 border border-green-600 text-green-100 px-4 py-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-4 bg-gradient-to-r from-red-900 to-red-800 border border-red-600 text-red-100 px-4 py-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>
