<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-8 mb-8 hover:border-white/20 transition-all duration-300">
                <h2 class="text-2xl font-bold text-white mb-6">Account Settings</h2>
                
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    <div class="mb-10">
                        @livewire('profile.update-profile-information-form')
                    </div>
                    <x-section-border />
                @endif

                <!-- Blocked Users -->
                <div class="mt-10 sm:mt-0 mb-10">
                    @livewire('user.blocked-users')
                </div>
                <x-section-border />

                <!-- Verify Website -->
                <div class="mt-10 sm:mt-0 mb-10">
                    @if (class_exists(\App\Livewire\User\VerifyWebsite::class))
                        @livewire('user.verify-website')
                    @else
                        <div>Website verification is not available</div>
                    @endif
                </div>
                <x-section-border />

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()) &&
                        !is_null($user->getAuthPassword()))
                    <div class="mt-10 sm:mt-0 mb-10">
                        @livewire('profile.update-password-form')
                    </div>
                    <x-section-border />
                @else
                    <div class="mt-10 sm:mt-0 mb-10">
                        @livewire('profile.set-password-form')
                    </div>
                    <x-section-border />
                @endif

                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication() && !is_null($user->getAuthPassword()))
                    <div class="mt-10 sm:mt-0 mb-10">
                        @livewire('profile.two-factor-authentication-form')
                    </div>
                    <x-section-border />
                @endif

                @if (JoelButcher\Socialstream\Socialstream::show())
                    <div class="mt-10 sm:mt-0 mb-10">
                        @livewire('profile.connected-accounts-form')
                    </div>
                    <x-section-border />
                @endif

                @if (!is_null($user->getAuthPassword()))
                    <div class="mt-10 sm:mt-0 mb-10">
                        @livewire('profile.logout-other-browser-sessions-form')
                    </div>
                    <x-section-border />
                @endif

                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures() && !is_null($user->getAuthPassword()))
                    <div class="mt-10 sm:mt-0">
                        @livewire('profile.delete-user-form')
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="stars"></div>
    @vite(['resources/js/stars.js'])
</x-app-layout>
