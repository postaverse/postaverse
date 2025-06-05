<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-400">
            {{ __('Thanks for signing up! Before getting started, we need to verify your email address. Please check your email for a verification link. If you didn\'t receive the email, click the button below for additional help.') }}
        </div>

        @if (session('status') === 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-400">
                {{ __('A new verification link has been sent to your email address.') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 font-medium text-sm text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <div class="mt-8">
            <div class="flex items-center justify-center">
                <a href="{{ route('login') }}"
                    class="underline text-sm text-gray-400 hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800">
                    {{ __('Return to login') }}
                </a>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
