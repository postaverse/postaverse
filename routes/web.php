<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Profile;
use App\Livewire\Follow;
use App\Livewire\Feed;
use App\Livewire\Settings;
use App\Livewire\Search;
use App\Livewire\AdminDashboard;
use App\Livewire\ShopTextThemes;
use App\Livewire\Blogs;

Route::get('/', function () {
    if (auth()->check()) {
        return view('home');
    }
    return redirect()->route('welcome');
})->name('home');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/@{handle}', Profile::class)->name('user-profile');
Route::get('/search', Search::class)->name('search');

Route::get('/admin', AdminDashboard::class)->name('admin');
Route::get('/blogs', Blogs::class)->name('blogs');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/home', function () {
        return redirect()->route('home');
    });
    Route::post('/follow/{user}', Follow::class)->name('follow');
    Route::delete('/unfollow/{user}', Follow::class)->name('unfollow');
    Route::get('/feed', Feed::class)->name('feed');
    Route::get('/settings', [Settings::class, 'show'])->name('settings.show');
    Route::get('/shop', ShopTextThemes::class)->name('shop');
});

require __DIR__ . '/socialstream.php';
