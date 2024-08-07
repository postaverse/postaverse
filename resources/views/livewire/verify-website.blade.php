<x-form-section submit="verify">
    <x-slot name="title">
        {{ __('Verify Website') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Verify your website by adding a verification code to your site.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Domain -->
        @if ($site && $site->is_verified)
            <div class="col-span-6 sm:col-span-4">
                <x-label for="verified_domain" value="{{ __('Verified Domain') }}" />
                <x-input id="verified_domain" type="text" class="mt-1 block w-full" value="{{ $site->domain }}"
                    readonly />
            </div>
        @else
            <div class="col-span-6 sm:col-span-4">
                <x-label for="domain" value="{{ __('Domain') }}" />
                <x-input id="domain" type="text" class="mt-1 block w-full" wire:model="domain" required
                    autocomplete="domain" />
                <x-input-error for="domain" class="mt-2" />
            </div>
        @endif

        <!-- Verification Status -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="is_verified" value="{{ __('Verification Status') }}" />
            <x-input id="is_verified" type="text" class="mt-1 block w-full"
                value="{{ $site ? ($site->is_verified ? 'Verified' : 'Not Verified') : 'No Site Found' }}" readonly />
            <x-input-error for="is_verified" class="mt-2" />
        </div>

        @if ($site && !$site->is_verified)
            <!-- Instructions -->
            <div class="col-span-6 sm:col-span-4">
                <x-label value="{{ __('Instructions') }}" />
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Please add the following meta tag to your website\'s <code>&lt;head&gt;</code> section:') }}
                </p>
                <p class="bg-gray-100 p-2 rounded text-black" style="word-break: break-all;">
                    <code>&lt;meta name="postaverse-web-verification" content="{{ $verificationCode }}"&gt;</code>
                </p>
            </div>
        @endif
        @if ($site && $site->is_verified)
            <!-- Option to remove site -->
            <div class="col-span-6 sm:col-span-4">
                <x-danger-button wire:click="removeSite" wire:loading.attr="disabled" wire:target="removeSite">
                    {{ __('Remove Site') }}
                </x-danger-button>
            </div>
        @endif
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="verified">
            {{ __('Verified.') }}
        </x-action-message>

        @if ($site && !$site->is_verified)
            <x-button wire:loading.attr="disabled" wire:target="verify">
                {{ __('Verify') }}
            </x-button>
        @endif
    </x-slot>
</x-form-section>
