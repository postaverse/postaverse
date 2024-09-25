<x-form-section submit="submit">
    <x-slot name="title">
        {{ __('Block Users') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Block users by entering their user IDs, separated by commas.') }}
    </x-slot>

    <x-slot name="form">
        <!-- User IDs Text Area -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="blocked_users" value="{{ __('User IDs') }}" />
            <x-textarea id="blocked_users" wire:model="userIds" class="block w-full mt-1" name="blocked_users">{{ $userIds }}</x-textarea>
            <x-input-error for="blocked_users" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-button>
            {{ __('Block Users') }}
        </x-button>
    </x-slot>
</x-form-section>