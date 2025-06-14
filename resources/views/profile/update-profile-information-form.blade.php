<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        <div class="text-gray-300">
            {{ __('Update your account\'s profile information, email address, and bio.') }}
        </div>
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden" wire:model.live="photo" x-ref="photo"
                    x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Profile Photo') }}" class="text-gray-300 mb-2" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}"
                        class="rounded-full h-24 w-24 object-cover ring-4 ring-indigo-500/30 shadow-lg">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span
                        class="block rounded-full w-24 h-24 bg-cover bg-no-repeat bg-center ring-4 ring-indigo-500/30 shadow-lg"
                        x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <div class="mt-4 flex space-x-3">
                    <x-secondary-button type="button"
                        class="bg-gray-800/40 backdrop-blur-sm border border-white/10 hover:border-white/20 text-gray-300 hover:text-white transition-all"
                        x-on:click.prevent="$refs.photo.click()">
                        {{ __('Select New Photo') }}
                    </x-secondary-button>

                    @if ($this->user->profile_photo_path)
                        <x-secondary-button type="button"
                            class="bg-red-500/10 backdrop-blur-sm border border-red-500/20 hover:border-red-500/30 text-red-400 hover:text-red-300 transition-all"
                            wire:click="deleteProfilePhoto">
                            {{ __('Remove Photo') }}
                        </x-secondary-button>
                    @endif
                </div>

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Display Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Display Name') }} (optional)" class="text-gray-300 mb-2" />
            <x-input id="name" type="text"
                class="mt-1 block w-full bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all"
                wire:model="state.name" autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Username -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="handle" value="{{ __('Username') }}" class="text-gray-300 mb-2" />
            <x-input id="handle" type="text"
                class="mt-1 block w-full bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all"
                wire:model="state.handle" required autocomplete="handle" />
            <x-input-error for="handle" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" class="text-gray-300 mb-2" />
            <x-input id="email" type="email"
                class="mt-1 block w-full bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all"
                wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) &&
                    !$this->user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-orange-500/10 backdrop-blur-sm rounded-lg border border-orange-500/20 text-sm">
                    <p class="text-orange-300">
                        {{ __('Your email address is unverified.') }}

                        <button type="button"
                            class="underline text-orange-300 hover:text-orange-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800"
                            wire:click.prevent="sendEmailVerification">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if ($this->verificationLinkSent)
                        <p class="mt-2 font-medium text-sm text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Bio -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="bio" value="{{ __('Bio') }}" class="text-gray-300 mb-2" />
            <x-textarea id="bio"
                class="mt-1 block w-full h-32 bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all"
                wire:model="state.bio" placeholder="Tell us about yourself..." />
            <p class="text-xs text-gray-400 mt-2">You can use Markdown in your bio.</p>
            <x-input-error for="bio" class="mt-2" />
        </div>

        <!-- Profanity Detection Type -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="profanityOption" value="{{ __('Content Filter Options') }}" class="text-gray-300 mb-2" />
            <div class="mt-1 block w-full p-4 bg-gray-800/40 backdrop-blur-sm border border-white/10 rounded-lg">
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="radio" id="hide_clickable" name="profanityOption"
                            wire:model="state.profanity_block_type" value="hide_clickable"
                            class="mr-3 h-4 w-4 border-white/20 bg-gray-800 text-indigo-500 focus:ring-indigo-500">
                        <label for="hide_clickable" class="text-gray-200">Hide with clickable reveal</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="hide" name="profanityOption"
                            wire:model="state.profanity_block_type" value="hide"
                            class="mr-3 h-4 w-4 border-white/20 bg-gray-800 text-indigo-500 focus:ring-indigo-500">
                        <label for="hide" class="text-gray-200">Hide completely</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="show" name="profanityOption"
                            wire:model="state.profanity_block_type" value="show"
                            class="mr-3 h-4 w-4 border-white/20 bg-gray-800 text-indigo-500 focus:ring-indigo-500">
                        <label for="show" class="text-gray-200">Show all content</label>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3 border-t border-white/10 pt-3">Controls how potentially offensive
                    content is displayed to you.</p>
            </div>
            <x-input-error for="profanity_block_type" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3 text-green-400" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button class="bg-indigo-600 hover:bg-indigo-700 transition-colors" wire:loading.attr="disabled"
            wire:target="photo">
            {{ __('Save Changes') }}
        </x-button>
    </x-slot>
</x-form-section>
