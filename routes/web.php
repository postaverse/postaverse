<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Profile;
use App\Livewire\Follow;
use App\Livewire\Feed;
use Laravel\Jetstream\Http\Livewire\UserProfile;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    } else {
        return view('welcome');
    }
});

Route::get('/u/{userId}', Profile::class)->name('user-profile');
Route::get('/settings', UserProfile::class)->name('profile.show')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/home', function () {
        return view('home');
    })->name('home');
    Route::post('/follow/{user}', Follow::class)->name('follow');
    Route::delete('/unfollow/{user}', Follow::class)->name('unfollow');
    Route::get('/feed', Feed::class)->name('feed');
});

require __DIR__ . '/socialstream.php';
