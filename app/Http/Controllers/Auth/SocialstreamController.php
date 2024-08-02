<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use JoelButcher\Socialstream\ConnectedAccount;

class SocialstreamController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        // Check if the user already exists
        $u = User::where('email', $user->getEmail())->first();

        if ($u) {
            // Check if the user has already connected the account
            $connectedAccount = ConnectedAccount::where('provider_id', $user->getId())->first();

            if ($connectedAccount) {
                Auth::login($u, true);
                return redirect()->intended('/home');
            }
        }
        else {
            // Create a new user and connect the account
            User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ]);

            $u = User::where('email', $user->getEmail())->first();

            ConnectedAccount::create([
                'user_id' => $u->id,
                'provider_id' => $user->getId(),
                'provider_name' => $provider,
            ]);
        }

        Auth::login($u, true);

        return redirect()->intended('/home');
    }
}