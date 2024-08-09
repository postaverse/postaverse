<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Discord\DiscordExtendSocialite;

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
    public function boot()
    {
        $this->app->booted(function () {
            $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
            $socialite->extend('discord', function ($app) use ($socialite) {
                $config = $app['config']['services.discord'];
                return $socialite->buildProvider(DiscordExtendSocialite::class, $config);
            });
        });
    }
}
