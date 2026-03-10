<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\FriendRequestController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\GroupMemberController;
use App\Http\Controllers\Api\GroupPostController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PostLikeController;
use App\Http\Controllers\Api\PostMediaController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImageController;
use App\Http\Controllers\Api\ProductReviewController;
use App\Http\Controllers\Api\SellerController;
use App\Http\Controllers\Api\SellerOrderController;
use App\Http\Controllers\Api\SportController;
use App\Http\Controllers\Api\UserFollowController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\UserSportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Sports
Route::get('/sports', [SportController::class, 'index']);

// Products (public browsing)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Product Categories
Route::get('/product-categories', [ProductCategoryController::class, 'index']);

// User profiles (public)
Route::get('/users/{user}/profile', [UserProfileController::class, 'show']);
Route::get('/users/{user}/followers', [UserFollowController::class, 'followers']);
Route::get('/users/{user}/following', [UserFollowController::class, 'following']);

// Groups (public listing)
Route::get('/groups', [GroupController::class, 'index']);
Route::get('/groups/{group}', [GroupController::class, 'show']);
Route::get('/groups/{group}/posts', [GroupPostController::class, 'index']);

// Posts (public feed)
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);
Route::get('/posts/{post}/comments', [CommentController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // --- Module 1: Users & Profile ---
    Route::put('/user/profile', [UserProfileController::class, 'update']);
    Route::get('/user/sports', [UserSportController::class, 'index']);
    Route::post('/user/sports', [UserSportController::class, 'store']);
    Route::delete('/user/sports/{sport}', [UserSportController::class, 'destroy']);

    // Sports reference data (admin)
    Route::post('/sports', [SportController::class, 'store']);
    Route::delete('/sports/{sport}', [SportController::class, 'destroy']);

    // --- Module 2: Social Graph ---
    Route::post('/users/{user}/follow', [UserFollowController::class, 'follow']);
    Route::delete('/users/{user}/follow', [UserFollowController::class, 'unfollow']);

    Route::get('/friend-requests', [FriendRequestController::class, 'index']);
    Route::post('/users/{user}/friend-request', [FriendRequestController::class, 'store']);
    Route::put('/friend-requests/{friendRequest}', [FriendRequestController::class, 'update']);
    Route::delete('/friend-requests/{friendRequest}', [FriendRequestController::class, 'destroy']);

    // --- Module 3: Posts & Media ---
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    Route::post('/posts/{post}/media', [PostMediaController::class, 'store']);
    Route::delete('/posts/{post}/media/{media}', [PostMediaController::class, 'destroy']);

    Route::post('/posts/{post}/like', [PostLikeController::class, 'store']);
    Route::delete('/posts/{post}/like', [PostLikeController::class, 'destroy']);

    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    // --- Module 4: Groups ---
    Route::post('/groups', [GroupController::class, 'store']);
    Route::put('/groups/{group}', [GroupController::class, 'update']);
    Route::delete('/groups/{group}', [GroupController::class, 'destroy']);

    Route::get('/groups/{group}/members', [GroupMemberController::class, 'index']);
    Route::post('/groups/{group}/join', [GroupMemberController::class, 'join']);
    Route::delete('/groups/{group}/leave', [GroupMemberController::class, 'leave']);
    Route::put('/groups/{group}/members/{user}', [GroupMemberController::class, 'update']);
    Route::delete('/groups/{group}/members/{user}', [GroupMemberController::class, 'destroy']);

    // --- Module 7: Marketplace ---
    Route::post('/product-categories', [ProductCategoryController::class, 'store']);
    Route::delete('/product-categories/{productCategory}', [ProductCategoryController::class, 'destroy']);

    Route::get('/seller/profile', [SellerController::class, 'show']);
    Route::post('/seller/register', [SellerController::class, 'register']);
    Route::put('/seller/profile', [SellerController::class, 'update']);
    Route::get('/seller/orders', [SellerOrderController::class, 'index']);
    Route::put('/seller/orders/{order}/status', [SellerOrderController::class, 'updateStatus']);

    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    Route::post('/products/{product}/images', [ProductImageController::class, 'store']);
    Route::delete('/products/{product}/images/{image}', [ProductImageController::class, 'destroy']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel']);

    Route::post('/order-items/{orderItem}/review', [ProductReviewController::class, 'store']);

    // --- Module 8: Notifications & Messaging ---
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);

    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::post('/conversations', [ConversationController::class, 'store']);
    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store']);
    Route::delete('/messages/{message}', [MessageController::class, 'destroy']);
});
