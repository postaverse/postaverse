<?php

use JoelButcher\Socialstream\Features;
use JoelButcher\Socialstream\Providers;

return [
    'middleware' => ['web'],
    'prompt' => 'Or Login Via',
    'providers' => [
        Providers::github(),
        Providers::google(),
        [
            'id' => 'discord',
            'name' => 'Discord',
            'label' => 'Discord',
        ],
        /*
        [
            'id' => 'reddit',
            'name' => 'Reddit',
            'label' => 'Reddit',
        ],
        */
    ],
    'features' => [
        Features::generateMissingEmails(),
        Features::createAccountOnFirstLogin(),
        Features::globalLogin(),
        Features::authExistingUnlinkedUsers(),
        Features::rememberSession(),
        Features::providerAvatars(),
        Features::refreshOAuthTokens(),
    ],
    'home' => '/home',
    'redirects' => [
        'login' => '/home',
        'register' => '/settings',
        'login-failed' => '/login',
        'registration-failed' => '/register',
        'provider-linked' => '/settings',
        'provider-link-failed' => '/settings',
    ]
];
