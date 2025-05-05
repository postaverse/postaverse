<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['nullable', 'string', 'max:255'], // Changed from required to nullable
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'bio' => ['nullable', 'max:5120'],
            'handle' => ['nullable', 'string', 'max:16', Rule::unique('users')->ignore($user->id)],
            'profanity_block_type' => ['nullable', 'string', Rule::in(['show', 'hide', 'hide_clickable'])],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => isset($input['name']) ? $input['name'] : '',  // Always provide at least empty string
                'email' => $input['email'],
                'bio' => $input['bio'],
                'handle' => $input['handle'] ?? '',
                'profanity_block_type' => $input['profanity_block_type'] ?? 'hide_clickable',
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => isset($input['name']) ? $input['name'] : '',  // Always provide at least empty string
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
