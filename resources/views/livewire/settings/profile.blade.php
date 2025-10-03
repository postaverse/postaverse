<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your personal information and social profile')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <flux:input wire:model="name" :label="__('Display Name')" type="text" required autofocus autocomplete="name" />
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <flux:input wire:model="username" :label="__('Username')" type="text" required />
                    @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <flux:input wire:model="bio" :label="__('Bio')" type="text" placeholder="Tell us about yourself..." />
                    @error('bio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <flux:input wire:model="location" :label="__('Location')" type="text" placeholder="Where are you from?" />
                    @error('location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <flux:input wire:model="website" :label="__('Website')" type="url" placeholder="https://..." />
                    @error('website') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <flux:input wire:model="birth_date" :label="__('Birth Date')" type="date" />
                    @error('birth_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <flux:field>
                    <flux:label>Profile Avatar</flux:label>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if($current_avatar)
                                <img class="h-16 w-16 rounded-full object-cover" src="{{ Storage::url($current_avatar) }}" alt="Profile avatar">
                            @else
                                <div class="h-16 w-16 rounded-full bg-zinc-300 dark:bg-zinc-600 flex items-center justify-center">
                                    <svg class="h-8 w-8 text-zinc-600 dark:text-zinc-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <flux:input wire:model="avatar" type="file" accept="image/*" class="flex-1" />
                    </div>
                    @error('avatar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </flux:field>
            </div>

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                <div class="rounded-md bg-yellow-50 dark:bg-yellow-900/20 p-4">
                    <flux:text class="text-yellow-800 dark:text-yellow-200">
                        {{ __('Your email address is unverified.') }}

                        <flux:link class="font-medium underline cursor-pointer" wire:click.prevent="resendVerificationNotification">
                            {{ __('Click here to re-send the verification email.') }}
                        </flux:link>
                    </flux:text>

                    @if (session('status') === 'verification-link-sent')
                        <flux:text class="mt-2 font-medium text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </flux:text>
                    @endif
                </div>
            @endif

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
