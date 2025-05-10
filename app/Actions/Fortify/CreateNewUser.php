<?php

namespace App\Actions\Fortify;

use App\Models\User\User;
use App\Models\User\PendingUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Models\Whitelisted;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {   
        // Check if whitelisting is enabled
        if (Config::get('whitelisting.enabled', false)) {
            // Only perform whitelisting check if the feature is enabled
            if (Whitelisted::where('email', $input['email'])->first() == null) {
                throw ValidationException::withMessages([
                    'email' => ['You are not whitelisted.'],
                ]);
            }
        }
        
        $correctEmoji = session('correct_emoji');

        Validator::make($input, [
            'handle' => ['required', 'string', 'max:30', 'alpha_dash', 'unique:users,handle', 'unique:pending_users,handle'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:pending_users,email'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'age' => ['required'],
            'selected_emoji' => ['required', function ($attribute, $value, $fail) use ($correctEmoji) {
                if ($value !== $correctEmoji) {
                    $fail('The selected emoji is incorrect.');
                }
            }],
        ])->validate();
        
        $verificationToken = Str::random(60);
        
        // Store the pending user
        $pendingUser = PendingUser::create([
            'name' => $input['handle'],
            'handle' => $input['handle'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'verification_token' => $verificationToken,
        ]);
        
        // Send verification email
        $this->sendVerificationEmail($pendingUser);
        
        // We need to return a User instance to satisfy the return type,
        // but this is a temporary user that won't be persisted
        return new User([
            'name' => $input['handle'],
            'handle' => $input['handle'],
            'email' => $input['email'],
        ]);
    }
    
    /**
     * Send the email verification to the pending user.
     * 
     * @param \App\Models\User\PendingUser $pendingUser
     * @return void
     */
    protected function sendVerificationEmail(PendingUser $pendingUser): void
    {
        $verificationUrl = config('app.url') . '/verify-email/' . $pendingUser->id . '/' . $pendingUser->verification_token;
        
        Mail::send('emails.verify-email', ['url' => $verificationUrl, 'handle' => $pendingUser->handle], function ($message) use ($pendingUser) {
            $message->to($pendingUser->email)
                ->subject('Verify Your Email Address - Postaverse');
        });
    }
}