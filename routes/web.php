<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Profile;
use App\Livewire\Follow;
use App\Livewire\Feed;
use App\Livewire\Settings;
use App\Livewire\Search;
use App\Livewire\AdminDashboard;
use App\Livewire\ShopTextThemes;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    } else {
        return view('welcome');
    }
});

Route::get('/@{handle}', Profile::class)->name('user-profile');
Route::get('/search', Search::class)->name('search');

Route::get('/admin', AdminDashboard::class)->name('admin');

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
    Route::get('/shop/text-themes', ShopTextThemes::class)->name('shop.text-themes');
});

require __DIR__ . '/socialstream.php';
