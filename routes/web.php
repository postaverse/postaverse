<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Profile;
use App\Livewire\Follow;
use App\Livewire\Feed;
use App\Livewire\Settings;
use App\Livewire\Search;
use App\Livewire\Blog;
use App\Livewire\DeleteAllPosts;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    } else {
        return view('welcome');
    }
});

Route::get('/u/{userId}', Profile::class)->name('user-profile');
Route::get('/search', Search::class)->name('search');
Route::get('/blog', Blog::class)->name('blog');

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
    Route::get('/settings', [Settings::class, 'show'])->name('settings.show');
    Route::get('/clear-posts', DeleteAllPosts::class)->name('clear-posts');
});

require __DIR__ . '/socialstream.php';
