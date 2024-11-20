<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Actions\Socialstream\CreateConnectedAccount;
use JoelButcher\Socialstream\ConnectedAccount;
use App\Models\Whitelisted;

class SocialstreamController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        // First, check if the user is logged in
        $liu = Auth::user();
        $u = User::where('email', $user->getEmail())->first();

        if ($liu) {
            // Set the email of the connected account to the email of the logged in user
            // instead of the email of the connected account.
            // This prevents account creation if the user is already logged in.
            // This modifies the $u variable
            $u = $liu;
        }

        if ($u) {
            // Check if the user has already connected the account
            $connectedAccount = ConnectedAccount::where('user_id', $u->id)
                ->where('provider', $provider)
                ->where('provider_id', $user->getId())
                ->first();

            if ($connectedAccount) {
                Auth::login($u, true);
                return redirect()->intended('/home');
            } else {
                // Create a new connected account
                $createConnectedAccount = new CreateConnectedAccount();
                $createConnectedAccount->create($u, $provider, $user);
            }
        } else {
            if (Whitelisted::where('email', Socialite::driver($provider)->user()->getEmail())->first() == null) {
                return redirect()->route('login')->with('error', 'You are not whitelisted.');
            }
            // Create a new user and connect the account
            User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ]);

            $u = User::where('email', $user->getEmail())->first();

            // Create a new connected account
            $createConnectedAccount = new CreateConnectedAccount();
            $createConnectedAccount->create($u, $provider, $user);
        }

        Auth::login($u, true);

        return redirect()->intended('/home');
    }
}
