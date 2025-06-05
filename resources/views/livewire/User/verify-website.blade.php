<x-form-section submit="verify">
    <x-slot name="title">
        {{ __('Verify Website') }}
    </x-slot>

    <x-slot name="description">
        <div class="text-gray-300">
            {{ __('Verify your website by adding a verification code to your site.') }}
        </div>
    </x-slot>

    <x-slot name="form">
        <!-- Domain -->
        @if ($site && $site->is_verified)
            <div class="col-span-6 sm:col-span-4">
                <x-label for="verified_domain" value="{{ __('Verified Domain') }}" class="text-gray-300 mb-2" />
                <div class="flex items-center">
                    <x-input id="verified_domain" type="text"
                        class="mt-1 block w-full bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white"
                        value="{{ $site->domain }}" readonly />
                    <div class="ml-3 bg-green-500/20 px-3 py-1 rounded-full inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-green-300 text-xs font-medium">Verified</span>
                    </div>
                </div>
            </div>
        @else
            <div class="col-span-6 sm:col-span-4">
                <x-label for="domain" value="{{ __('Domain') }}" class="text-gray-300 mb-2" />
                <x-input id="domain" type="text"
                    class="mt-1 block w-full bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all"
                    wire:model="domain" required autocomplete="domain" placeholder="example.com" />
                <p class="text-xs text-gray-400 mt-2">Enter your website domain without http:// or https://</p>
                <x-input-error for="domain" class="mt-2" />
            </div>
        @endif

        <!-- Verification Status -->
        @if (!($site && $site->is_verified))
            <div class="col-span-6 sm:col-span-4">
                <x-label for="is_verified" value="{{ __('Verification Status') }}" class="text-gray-300 mb-2" />
                <div
                    class="mt-1 p-3 rounded-lg border
                    @if ($site && $site->is_verified) bg-green-500/10 border-green-500/20 text-green-300
                    @else
                        bg-gray-800/40 backdrop-blur-sm border-white/10 text-gray-400 @endif
                ">
                    <div class="flex items-center">
                        @if ($site && $site->is_verified)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                        {{ $site ? ($site->is_verified ? 'Verified' : 'Not Verified') : 'No Site Found' }}
                    </div>
                </div>
            </div>
        @endif

        @if ($site && !$site->is_verified)
            <!-- Instructions -->
            <div class="col-span-6 sm:col-span-4 mt-2">
                <x-label value="{{ __('Verification Instructions') }}" class="text-gray-300 mb-2" />
                <div class="mt-1 p-4 bg-gray-800/40 backdrop-blur-sm border border-white/10 rounded-lg">
                    <ol class="list-decimal list-inside space-y-3 text-gray-300">
                        <li>Add the following meta tag to your website's <code
                                class="text-indigo-300">&lt;head&gt;</code> section:</li>
                        <div
                            class="bg-gray-900/80 p-3 rounded-lg border border-indigo-500/20 mt-2 mb-3 overflow-x-auto">
                            <code class="text-indigo-300 text-sm" style="word-break: break-all;">&lt;meta
                                name="postaverse-web-verification" content="{{ $verificationCode }}"&gt;</code>
                        </div>
                        <li>Click the verify button below once you've added the meta tag to your site.</li>
                    </ol>
                </div>
            </div>
        @endif
        @if ($site && $site->is_verified)
            <!-- Option to remove site -->
            <div class="col-span-6 sm:col-span-4 mt-4">
                <x-danger-button wire:click="removeSite" wire:loading.attr="disabled" wire:target="removeSite"
                    class="bg-red-500/10 backdrop-blur-sm border border-red-500/20 hover:border-red-500/30 text-red-400 hover:text-red-300 transition-all">
                    {{ __('Remove Verified Website') }}
                </x-danger-button>
                <p class="text-xs text-gray-400 mt-2">Removing your verified website will remove the verification badge
                    from your profile.</p>
            </div>
        @endif
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3 text-green-400" on="verified">
            {{ __('Website Verified!') }}
        </x-action-message>

        @if (!($site && $site->is_verified))
            <x-button wire:loading.attr="disabled" wire:target="verify"
                class="bg-indigo-600 hover:bg-indigo-700 transition-colors">
                {{ __('Verify Website') }}
            </x-button>
        @endif
    </x-slot>
</x-form-section>
