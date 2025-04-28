<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Models\Whitelisted;
use Illuminate\Support\Facades\Config;

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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'age' => ['required'],
            'selected_emoji' => ['required', function ($attribute, $value, $fail) use ($correctEmoji) {
                if ($value !== $correctEmoji) {
                    $fail('The selected emoji is incorrect.');
                }
            }],
        ])->validate();

        return DB::transaction(function () use ($input) {
            return User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);
        });
    }
}