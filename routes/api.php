<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SecurityController;
use App\Http\Controllers\Api\ConnectedAccountsController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Posts
    Route::apiResource('posts', PostController::class);
    Route::post('/posts/{id}/like', [PostController::class, 'toggleLike']);
    Route::get('/feed', [PostController::class, 'feed']);

    // Comments
    Route::post('/posts/{postId}/comments', [CommentController::class, 'storePostComment']);
    Route::post('/blogs/{blogId}/comments', [CommentController::class, 'storeBlogComment']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users/{id}/follow', [UserController::class, 'toggleFollow']);
    Route::get('/users/{id}/followers', [UserController::class, 'followers']);
    Route::get('/users/{id}/following', [UserController::class, 'following']);
    Route::get('/users/{id}/posts', [UserController::class, 'posts']);
    Route::get('/users/{id}/blogs', [UserController::class, 'blogs']);

    // User blocking
    Route::get('/users/blocked', [UserController::class, 'getBlockedUsers']);
    Route::post('/users/{id}/block', [UserController::class, 'blockUser']);
    Route::delete('/users/{id}/block', [UserController::class, 'unblockUser']);

    // Blogs
    Route::apiResource('blogs', BlogController::class);
    Route::post('/blogs/{id}/like', [BlogController::class, 'toggleLike']);

    // Search
    Route::get('/search', [SearchController::class, 'search']);
    Route::get('/search/users', [SearchController::class, 'searchUsers']);
    Route::get('/search/posts', [SearchController::class, 'searchPosts']);
    Route::get('/search/blogs', [SearchController::class, 'searchBlogs']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/{id}/unread', [NotificationController::class, 'markAsUnread']);
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::post('/notifications/bulk-action', [NotificationController::class, 'bulkAction']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    // Security & Account Management
    Route::put('/user/password', [SecurityController::class, 'updatePassword']);
    Route::post('/user/two-factor-authentication', [SecurityController::class, 'enableTwoFactor']);
    Route::post('/user/confirmed-two-factor-authentication', [SecurityController::class, 'confirmTwoFactor']);
    Route::delete('/user/two-factor-authentication', [SecurityController::class, 'disableTwoFactor']);
    Route::get('/user/two-factor-recovery-codes', [SecurityController::class, 'getRecoveryCodes']);
    Route::post('/user/two-factor-recovery-codes', [SecurityController::class, 'regenerateRecoveryCodes']);
    Route::get('/user/sessions', [SecurityController::class, 'getBrowserSessions']);
    Route::delete('/user/other-browser-sessions', [SecurityController::class, 'logoutOtherSessions']);
    Route::delete('/user', [SecurityController::class, 'deleteAccount']);

    // Connected Accounts
    Route::get('/user/connected-accounts', [ConnectedAccountsController::class, 'index']);
    Route::delete('/user/connected-accounts/{id}', [ConnectedAccountsController::class, 'destroy']);
});

// Legacy route for compatibility
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');