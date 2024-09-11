<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

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