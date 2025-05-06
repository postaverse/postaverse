<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\User\Profile;
use App\Livewire\Interaction\Follow;
use App\Livewire\Post\Feed;
use App\Livewire\User\Settings;
use App\Livewire\Interaction\Search;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Blog\Blogs;
use App\Livewire\Blog\BlogPage;
use App\Livewire\Post\PostPage;
use App\Livewire\User\Banned;
use App\Livewire\Interaction\Notifications;
use App\Http\Middleware\CheckIfBanned;

require __DIR__ . '/auth.php';
require __DIR__ . '/markdown.php';

// Public routes
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/banned', Banned::class)->name('banned');

Route::get('/blog', Blogs::class)->name('blogs');

// Routes with banned check middleware
Route::middleware([CheckIfBanned::class])->group(function () {
    Route::get('/', function () {
        if (auth()->check()) {
            return view('home');
        }
        return view('welcome');
    })->name('home');
    
    Route::get('/@{handle}', Profile::class)->name('user-profile');
    Route::get('/search', Search::class)->name('search');
    Route::get('/post/{postId}', PostPage::class)->name('post');
    Route::get('/blog/{blogId}', BlogPage::class)->name('blog');
});

Route::get('/home', function () {
    return auth()->check() 
        ? redirect()->route('home') 
        : view('home');
});

// Authenticated routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/admin', AdminDashboard::class)->name('admin');
    
    // User interactions
    Route::post('/follow/{user}', Follow::class)->name('follow');
    Route::delete('/unfollow/{user}', Follow::class)->name('unfollow');
    
    // User content routes with banned check
    Route::middleware([CheckIfBanned::class])->group(function () {
        Route::get('/feed', Feed::class)->name('feed');
        Route::get('/notifications', Notifications::class)->name('notifications');
    });
    
    Route::get('/settings', [Settings::class, 'show'])->name('settings.show');
});
