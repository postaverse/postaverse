<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialStreamController;

Route::get('auth/{provider}', [SocialStreamController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [SocialStreamController::class, 'handleProviderCallback']);
