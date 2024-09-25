<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialstreamController;

Route::get('auth/{provider}', [SocialstreamController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [SocialstreamController::class, 'handleProviderCallback']);
