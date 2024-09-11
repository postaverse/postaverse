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
use App\Livewire\PostPage;
use App\Livewire\Banned;
use App\Http\Middleware\CheckIfBanned;

Route::get('/banned', Banned::class)->name('banned');

Route::get('/', function () {
    if (auth()->check()) {
        return view('home');
    }
    return redirect()->route('welcome');
})->name('home')->middleware(CheckIfBanned::class);

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/@{handle}', Profile::class)->name('user-profile')->middleware(CheckIfBanned::class);
Route::get('/search', Search::class)->name('search')->middleware(CheckIfBanned::class);

Route::get('/blog', Blogs::class)->name('blogs');

Route::get('/post/{postId}', PostPage::class)->name('post')->middleware(CheckIfBanned::class);

Route::get('/home', function () {
    if (!auth()->check()) {
        return view('home');
    }
    return redirect()->route('home');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {


    Route::get('/admin', AdminDashboard::class)->name('admin');
    Route::post('/follow/{user}', Follow::class)->name('follow');
    Route::delete('/unfollow/{user}', Follow::class)->name('unfollow');
    Route::get('/feed', Feed::class)->name('feed')->middleware(CheckIfBanned::class);
    Route::get('/settings', [Settings::class, 'show'])->name('settings.show');
    Route::get('/shop', ShopTextThemes::class)->name('shop')->middleware(CheckIfBanned::class);
});

require __DIR__ . '/socialstream.php';
