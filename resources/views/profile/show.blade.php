<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            @livewire('profile.update-profile-information-form')

            <x-section-border />
            @php
            public $newHandle;

            public function updateHandle()
            {
            $this->validate([
            'newHandle' => ['required', 'string', 'max:255', 'unique:users,handle'],
            ]);

            $user = User::find(auth()->id());
            $user->handle = $newHandle;
            $user->save();

            session()->flash('message', 'Handle updated successfully.');
            }
            @endphp

            <form wire:submit.prevent="updateHandle">
                <input type="text" wire:model="newHandle" placeholder="Enter new handle">
                @error('newHandle') <span class="error">{{ $message }}</span> @enderror
                <button type="submit">Update Handle</button>
            </form>

            <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()) && ! is_null($user->getAuthPassword()))
            <div class="mt-10 sm:mt-0">
                @livewire('profile.update-password-form')
            </div>

            <x-section-border />
            @else
            <div class="mt-10 sm:mt-0">
                @livewire('profile.set-password-form')
            </div>

            <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication() && ! is_null($user->getAuthPassword()))
            <div class="mt-10 sm:mt-0">
                @livewire('profile.two-factor-authentication-form')
            </div>

            <x-section-border />
            @endif

            @if (JoelButcher\Socialstream\Socialstream::show())
            <div class="mt-10 sm:mt-0">
                @livewire('profile.connected-accounts-form')
            </div>
            @endif


            @if ( ! is_null($user->getAuthPassword()))
            <x-section-border />

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>
            @endif

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures() && ! is_null($user->getAuthPassword()))
            <x-section-border />

            <div class="mt-10 sm:mt-0">
                @livewire('profile.delete-user-form')
            </div>
            @endif
        </div>
    </div>
</x-app-layout>