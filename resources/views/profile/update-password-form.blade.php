<x-form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('Update Password') }}
    </x-slot>

    <x-slot name="description">
        <div class="text-gray-300">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </div>
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="current_password" value="{{ __('Current Password') }}" class="text-gray-300 mb-2" />
            <x-input id="current_password" type="password" class="mt-1 block w-full bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all" wire:model="state.current_password"
                autocomplete="current-password" />
            <x-input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password" value="{{ __('New Password') }}" class="text-gray-300 mb-2" />
            <x-input id="password" type="password" class="mt-1 block w-full bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all" wire:model="state.password"
                autocomplete="new-password" />
            <x-input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-gray-300 mb-2" />
            <x-input id="password_confirmation" type="password" class="mt-1 block w-full bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all"
                wire:model="state.password_confirmation" autocomplete="new-password" />
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>
        
        <div class="col-span-6 sm:col-span-4 mt-4">
            <div class="p-4 bg-blue-500/10 backdrop-blur-sm border border-blue-500/20 rounded-lg">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-blue-300">For better security, use a password that is at least 8 characters long with a mix of letters, numbers, and symbols.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3 text-green-400" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button class="bg-indigo-600 hover:bg-indigo-700 transition-colors">
            {{ __('Update Password') }}
        </x-button>
    </x-slot>
</x-form-section>
