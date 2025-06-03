<?php

namespace App\Providers;

use App\Models\User\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravel\Pulse\Facades\Pulse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('discord', \SocialiteProviders\Discord\Provider::class);
            // $event->extendSocialite('reddit', \SocialiteProviders\Reddit\Provider::class);
        });

        Pulse::user(function (User $user) {
            return [
                'name' => $user->name,
                'extra' => $user->email,
                'avatar' => $user->profile_photo_url,
            ];
        });
    }
}
