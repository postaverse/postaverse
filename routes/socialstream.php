<?php

use Illuminate\Support\Facades\Route;
use JoelButcher\Socialstream\Http\Controllers\OAuthController;

Route::group(['middleware' => config('socialstream.middleware', ['web'])], function () {
    Route::get('/auth/{provider}', [OAuthController::class, 'redirect'])->name('oauth.redirect');
    Route::match(['get', 'post'], '/auth/{provider}/callback', [OAuthController::class, 'callback'])->name('oauth.callback');
});
