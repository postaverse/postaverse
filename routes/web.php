<?php

use App\Livewire\Admin\Analytics;
use App\Livewire\Admin\Moderation;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\ShowPost;
use App\Livewire\ShowUser;
use App\Livewire\Social\DiscoverPeople;
use App\Livewire\Social\Groups;
use App\Livewire\Social\Messages;
use App\Livewire\Social\Notifications;
use App\Livewire\Social\SavedPosts;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Settings routes
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    
    // Social routes
    Route::get('discover-people', DiscoverPeople::class)->name('discover-people');
    Route::get('messages', Messages::class)->name('messages');
    Route::get('stations', Groups::class)->name('groups');
    Route::get('s/{group}', \App\Livewire\ShowGroup::class)->name('s.show');
    Route::get('notifications', Notifications::class)->name('notifications');
    Route::get('saved-posts', SavedPosts::class)->name('saved-posts');
    
    // Post routes
    Route::get('posts/{post}', ShowPost::class)->name('posts.show');
    
    // User profile routes
    Route::get('user/{username}', \App\Livewire\ShowUser::class)->name('user.profile');
    Route::get('user/{username}/followers', \App\Livewire\Social\Followers::class)->name('user.followers');
    Route::get('user/{username}/following', \App\Livewire\Social\Following::class)->name('user.following');
    
    // Admin routes (protected by admin middleware in components)
    Route::get('admin/moderation', Moderation::class)->name('admin.moderation');
    Route::get('admin/user-management', UserManagement::class)->name('admin.user-management');
    Route::get('admin/analytics', Analytics::class)->name('admin.analytics');
});

require __DIR__.'/auth.php';
