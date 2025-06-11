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
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
});

// Legacy route for compatibility
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');