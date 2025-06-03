<?php

namespace App\Http\Controllers;

use App\Models\User\User;
use App\Models\ConnectedAccount;
use App\Models\Whitelisted;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Actions\Socialstream\CreateConnectedAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Exception;

class SocialStreamController extends Controller
{
    /**
     * Redirect the user to the specified provider authentication page.
     *
     * @param string $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the callback from the OAuth provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            $providerUser = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            Log::error("OAuth error with provider {$provider}: " . $e->getMessage());
            return redirect()->route('login')->with('error', 'Failed to authenticate with ' . ucfirst($provider));
        }

        // Get authenticated user if logged in
        $authenticatedUser = Auth::user();
        $email = $providerUser->getEmail();
        
        // If no email was provided by the provider, we can't proceed
        if (!$email) {
            return redirect()->route('login')->with('error', 'Could not retrieve email from ' . ucfirst($provider));
        }
        
        // Find existing user with this email
        $user = $authenticatedUser ?? User::where('email', $email)->first();
        
        if ($user) {
            return $this->handleExistingUser($user, $provider, $providerUser);
        } else {
            return $this->createNewUser($provider, $providerUser);
        }
    }

    /**
     * Handle existing user social authentication.
     *
     * @param User $user
     * @param string $provider
     * @param \Laravel\Socialite\Contracts\User $providerUser
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleExistingUser(User $user, $provider, $providerUser)
    {
        // Check if the user has already connected the account
        $connectedAccount = ConnectedAccount::where('user_id', $user->id)
            ->where('provider', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();

        if (!$connectedAccount) {
            // Create a new connected account
            $createConnectedAccount = new CreateConnectedAccount();
            $createConnectedAccount->create($user, $provider, $providerUser);
        }

        Auth::login($user, true);
        return redirect()->intended('/home');
    }

    /**
     * Create a new user from OAuth data.
     *
     * @param string $provider
     * @param \Laravel\Socialite\Contracts\User $providerUser
     * @return \Illuminate\Http\RedirectResponse
     */
    private function createNewUser($provider, $providerUser)
    {
        $email = $providerUser->getEmail();
        
        // Check if whitelisting is enabled
        if (Config::get('whitelisting.enabled', false) && 
            !Whitelisted::where('email', $email)->exists()) {
            return redirect()->route('login')->with('error', 'You are not whitelisted.');
        }
        
        // Create a new user
        $user = User::create([
            'name' => $providerUser->getName() ?? explode('@', $email)[0],
            'email' => $email,
        ]);

        // Create a new connected account
        $createConnectedAccount = new CreateConnectedAccount();
        $createConnectedAccount->create($user, $provider, $providerUser);

        Auth::login($user, true);
        return redirect()->intended('/home');
    }
}
