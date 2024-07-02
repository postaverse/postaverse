<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Profile;
use App\Http\Controllers\FollowController;
use App\Livewire\Feed;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    } else {
        return view('welcome');
    }
});

Route::get('/u/{userId}', Profile::class)->name('user-profile');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/home', function () {
        return view('home');
    })->name('home');
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('user.follow');
    Route::post('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('user.unfollow');
});

Route::get('/feed', Feed::class)->name('feed')->middleware(['auth:sanctum', 'verified']);

require __DIR__ . '/socialstream.php';
