<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\User\Profile;
use App\Livewire\Post\Feed;
use App\Livewire\User\Settings;
use App\Livewire\Interaction\Search;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Blog\Blogs;
use App\Livewire\Content\ContentPage;
use App\Livewire\User\Banned;
use App\Livewire\Interaction\Notifications;
use App\Http\Middleware\CheckIfBanned;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\DeleteController;

require __DIR__ . '/auth.php';
require __DIR__ . '/markdown.php';

// Public routes
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/banned', Banned::class)->name('banned');

Route::get('/blog', Blogs::class)->name('blogs');

// Email verification routes
Route::get('/verify-email/{id}/{token}', [EmailVerificationController::class, 'verify'])
    ->name('pending.verification.verify');
Route::get('/email/verify', function () {
    return view('auth.registration-verification');
})->name('verification.notice');

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
    Route::get('/post/{contentId}', ContentPage::class)->name('post');
    Route::get('/blog/{contentId}', ContentPage::class)->name('blog');
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
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow');
    Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow');
    Route::delete('/post/{postId}', [DeleteController::class, 'deletePost'])->name('post.delete');
    
    // User content routes with banned check
    Route::middleware([CheckIfBanned::class])->group(function () {
        Route::get('/feed', Feed::class)->name('feed');
        Route::get('/notifications', Notifications::class)->name('notifications');
    });
    
    Route::get('/settings', [Settings::class, 'show'])->name('settings.show');
});
