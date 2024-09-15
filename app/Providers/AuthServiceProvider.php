<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Laravel\Pulse\Facades\Pulse;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('viewPulse', function (User $user) {
            return $user->admin_rank >= 1;
        });

        Pulse::user(fn ($user) => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'admin_rank' => $user->admin_rank,
            'avatar' => $user->avatar_url,
        ]);
    }
}
