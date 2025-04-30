<x-form-section>
    <x-slot name="title">
        {{ __('Block Users') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Block users by entering their username and clicking the Add button. Blocked users will not be able to see your posts or interact with you.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Add User Form -->
        <div class="col-span-6 sm:col-span-4">
            <div class="flex">
                <div class="flex-grow mr-2">
                    <x-label for="username" value="{{ __('Username') }}" class="text-gray-300" />
                    <x-input id="username" type="text" wire:model="username" class="block w-full mt-1" name="username" autocomplete="off" placeholder="Enter a username" />
                </div>
                <div class="flex items-end">
                    <x-button type="button" wire:click="addUser" class="bg-gray-800 hover:bg-gray-700">
                        {{ __('Add') }}
                    </x-button>
                </div>
            </div>
            <x-input-error for="username" class="mt-2" />
        </div>

        <!-- Blocked Users List -->
        <div class="col-span-6 sm:col-span-4 mt-4">
            <h3 class="text-lg font-medium text-gray-300 mb-3">{{ __('Blocked Users') }}</h3>
            
            @if (count($blockedUsersList) > 0)
                <ul class="mt-2 border border-gray-700 rounded-md divide-y divide-gray-700 bg-gray-800/30">
                    @foreach ($blockedUsersList as $user)
                        <li class="px-4 py-3 flex items-center justify-between">
                            <span class="text-gray-300 font-medium">{{ '@' . $user['handle'] }}</span>
                            <button type="button" wire:click="removeUser('{{ $user['id'] }}')" class="text-red-500 hover:text-red-400 focus:outline-none transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-400 py-3 px-4 bg-gray-800/20 rounded-md border border-gray-700">{{ __('You have not blocked any users yet.') }}</p>
            @endif
        </div>
    </x-slot>
</x-form-section>