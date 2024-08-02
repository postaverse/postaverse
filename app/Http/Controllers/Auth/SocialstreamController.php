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

        $authUser = User::firstOrCreate(
            ['email' => $user->getEmail()],
            ['name' => $user->getName(), 'provider_id' => $user->getId()]
        );

        // Create or update the connected account
        ConnectedAccount::updateOrCreate(
            [
                'user_id' => $authUser->id,
                'provider' => $provider,
                'provider_id' => $user->getId(),
                'token' => $user->token,
                'secret' => $user->tokenSecret ?? null,
                'refresh_token' => $user->refreshToken ?? null,
                'expires_at' => $user->expiresIn ?? null,
            ]
        );

        Auth::login($authUser, true);

        return redirect()->intended('/home');
    }
}