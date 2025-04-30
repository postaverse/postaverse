<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Email Whitelisting
    |--------------------------------------------------------------------------
    |
    | This option controls whether email whitelisting is enabled for registration.
    | When enabled, only emails in the whitelist can register.
    | When disabled, anyone can register regardless of email domain.
    |
    */
    
    'enabled' => env('ENABLE_EMAIL_WHITELISTING', false),
];