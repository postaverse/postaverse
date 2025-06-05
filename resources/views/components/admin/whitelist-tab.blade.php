@props([''])

<div
    class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
    <h2 class="text-2xl font-bold text-white mb-4">Email Whitelist Management</h2>

    <x-admin.session-messages />

    <form wire:submit.prevent="addEmail" class="max-w-lg mx-auto">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <x-label for="email" :value="__('Email Address')" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" wire:model="email"
                    required />
                @error('email')
                    <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <x-button class="w-full justify-center">
                    {{ __('Add to Whitelist') }}
                </x-button>
            </div>
        </div>
    </form>
</div>
