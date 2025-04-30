<x-action-section>
    <x-slot name="title">
        {{ __('Two Factor Authentication') }}
    </x-slot>

    <x-slot name="description">
        <div class="text-gray-300">
            {{ __('Add additional security to your account using two factor authentication.') }}
        </div>
    </x-slot>

    <x-slot name="content">
        <h3 class="text-lg font-medium text-white">
            @if ($this->enabled)
                @if ($showingConfirmation)
                    {{ __('Finish enabling two factor authentication.') }}
                @else
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('You have enabled two factor authentication.') }}
                    </div>
                @endif
            @else
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ __('You have not enabled two factor authentication.') }}
                </div>
            @endif
        </h3>

        <div class="mt-4 max-w-xl text-sm text-gray-300">
            <p>
                {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}
            </p>
        </div>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="mt-4 max-w-xl text-sm text-gray-300">
                    <p class="font-semibold">
                        @if ($showingConfirmation)
                            {{ __('To finish enabling two factor authentication, scan the following QR code using your phone\'s authenticator application or enter the setup key and provide the generated OTP code.') }}
                        @else
                            {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.') }}
                        @endif
                    </p>
                </div>

                <div class="mt-4 p-4 inline-block bg-white rounded-lg shadow-md">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>

                <div class="mt-4 p-4 max-w-xl text-sm bg-gray-800/40 backdrop-blur-sm border border-white/10 rounded-lg">
                    <p class="font-semibold text-gray-300">
                        {{ __('Setup Key') }}: <span class="font-mono text-indigo-300 select-all">{{ decrypt($this->user->two_factor_secret) }}</span>
                    </p>
                </div>

                @if ($showingConfirmation)
                    <div class="mt-4">
                        <x-label for="code" value="{{ __('Code') }}" class="text-gray-300 mb-2" />

                        <x-input id="code" type="text" name="code" 
                            class="block mt-1 w-1/2 bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all"
                            inputmode="numeric" autofocus autocomplete="one-time-code" wire:model="code"
                            wire:keydown.enter="confirmTwoFactorAuthentication" />

                        <x-input-error for="code" class="mt-2" />
                    </div>
                @endif
            @endif

            @if ($showingRecoveryCodes)
                <div class="mt-4 max-w-xl text-sm text-gray-300">
                    <p class="font-semibold">
                        {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                    </p>
                </div>

                <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-800/40 backdrop-blur-sm border border-white/10 rounded-lg shadow-inner overflow-x-auto">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div class="select-all text-indigo-300">{{ $code }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-6">
            @if (!$this->enabled)
                <x-confirms-password wire:then="enableTwoFactorAuthentication">
                    <x-button type="button" class="bg-indigo-600 hover:bg-indigo-700 transition-colors" wire:loading.attr="disabled">
                        {{ __('Enable Two-Factor') }}
                    </x-button>
                </x-confirms-password>
            @else
                <div class="flex flex-wrap gap-3">
                    @if ($showingRecoveryCodes)
                        <x-confirms-password wire:then="regenerateRecoveryCodes">
                            <x-secondary-button class="bg-gray-800/40 backdrop-blur-sm border border-white/10 hover:border-white/20 text-gray-300 hover:text-white transition-all">
                                {{ __('Regenerate Recovery Codes') }}
                            </x-secondary-button>
                        </x-confirms-password>
                    @elseif ($showingConfirmation)
                        <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                            <x-button type="button" class="bg-indigo-600 hover:bg-indigo-700 transition-colors" wire:loading.attr="disabled">
                                {{ __('Confirm') }}
                            </x-button>
                        </x-confirms-password>
                    @else
                        <x-confirms-password wire:then="showRecoveryCodes">
                            <x-secondary-button class="bg-gray-800/40 backdrop-blur-sm border border-white/10 hover:border-white/20 text-gray-300 hover:text-white transition-all">
                                {{ __('Show Recovery Codes') }}
                            </x-secondary-button>
                        </x-confirms-password>
                    @endif

                    @if ($showingConfirmation)
                        <x-confirms-password wire:then="disableTwoFactorAuthentication">
                            <x-secondary-button class="bg-gray-800/40 backdrop-blur-sm border border-white/10 hover:border-white/20 text-gray-300 hover:text-white transition-all" wire:loading.attr="disabled">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                        </x-confirms-password>
                    @else
                        <x-confirms-password wire:then="disableTwoFactorAuthentication">
                            <x-danger-button class="bg-red-500/10 backdrop-blur-sm border border-red-500/20 hover:border-red-500/30 text-red-400 hover:text-red-300 transition-all" wire:loading.attr="disabled">
                                {{ __('Disable Two-Factor') }}
                            </x-danger-button>
                        </x-confirms-password>
                    @endif
                </div>
            @endif
        </div>
    </x-slot>
</x-action-section>
