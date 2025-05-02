<x-form-section>
    <x-slot name="title">
        {{ __('Block Users') }}
    </x-slot>

    <x-slot name="description">
        <div class="text-gray-300">
            {{ __('Block users by entering their username and clicking the Add button. Blocked users will not be able to see your posts or interact with you.') }}
        </div>
    </x-slot>

    <x-slot name="form">
        <!-- Add User Form -->
        <div class="col-span-6 sm:col-span-4">
            <div class="flex">
                <div class="grow mr-2">
                    <x-label for="username" value="{{ __('Username') }}" class="text-gray-300 mb-2" />
                    <x-input id="username" type="text" wire:model="username" class="block w-full mt-1 bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all" name="username" autocomplete="off" placeholder="Enter a username" />
                </div>
                <div class="flex items-end">
                    <x-button type="button" wire:click="addUser" class="bg-indigo-600 hover:bg-indigo-700 transition-colors">
                        {{ __('Block User') }}
                    </x-button>
                </div>
            </div>
            <x-input-error for="username" class="mt-2" />
        </div>

        <!-- Blocked Users List -->
        <div class="col-span-6 sm:col-span-4 mt-4">
            <h3 class="text-lg font-medium text-white mb-3">{{ __('Blocked Users') }}</h3>
            
            @if (count($blockedUsersList) > 0)
                <ul class="mt-2 border border-white/10 rounded-lg divide-y divide-white/10 overflow-hidden">
                    @foreach ($blockedUsersList as $user)
                        <li class="px-4 py-3 flex items-center justify-between bg-gray-800/40 backdrop-blur-sm hover:bg-gray-800/60 transition-colors">
                            <span class="text-white font-medium">{{ '@' . $user['handle'] }}</span>
                            <button type="button" wire:click="removeUser('{{ $user['id'] }}')" class="text-red-400 hover:text-red-300 focus:outline-none transition-colors duration-200 p-1.5 hover:bg-red-500/10 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="p-4 bg-gray-800/40 backdrop-blur-sm rounded-lg border border-white/10 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-gray-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <p class="text-gray-400">{{ __('You have not blocked any users yet.') }}</p>
                </div>
            @endif
        </div>
    </x-slot>
</x-form-section>