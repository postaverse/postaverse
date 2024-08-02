<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialstreamController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        // Find or create the user
        $authUser = User::firstOrCreate(
            ['email' => $user->getEmail()],
            ['name' => $user->getName(), 'provider_id' => $user->getId()]
        );

        // Create the connected account
        $authUser->createConnectedAccount($provider, $user);

        Auth::login($authUser, true);

        return redirect()->intended('/home');
    }
}