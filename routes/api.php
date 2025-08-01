<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\LogRequestMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(LogRequestMiddleware::class)->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login',  'login');
        Route::post('/register',  'register');
        Route::post('/register/check-username', 'checkUsername');
        Route::post('/register/check-email', 'checkEmail');
        Route::post('/register/check-student-number', 'checkStudentNumber');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', 'logout');
            Route::get('/me', 'me');
        });
    });

    Route::controller(BlogController::class)->group(function () {
        Route::get('/blogs', 'index');
        Route::get('/blogs/{id}', 'show');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index');
        Route::get('/categories/{id}', 'show');
        Route::get('/categories/{id}/blogs', 'blogs');
    });

    Route::controller(TagController::class)->group(function () {
        Route::get('/tags', 'index');
        Route::get('/tags/{id}', 'show');
        Route::post('/tags',  'store')->middleware('auth:sanctum');
        Route::delete('/tags/{id}',  'destroy')->middleware('auth:sanctum');
    });

    Route::middleware('auth:sanctum')->group(function () {
        //user
        Route::controller(UserController::class)->group(function () {

            Route::get('/users', 'index');
            Route::get('/users/{id}', 'show');
            Route::patch('/users/{id}', 'update');
            Route::post('/users/{id}/follow', 'follow');
            Route::delete('/users/{id}/unfollow', 'unfollow');
            Route::get('/users/{id}/followers', 'followers');
            Route::get('/users/{id}/following', 'following');
            Route::get('/users/{id}/likes', 'likes');
            Route::get('/users/{id}/blogs', 'indexBlogsByUser');
        });

        Route::controller(BlogController::class)->group(function () {
            //Blog CRUD
            Route::post('/blogs',  'store');
            Route::patch('/blogs/{id}',  'update');
            Route::delete('/blogs/{id}',  'destroy');

            //Blog Comment
            Route::post('/blogs/{id}/comments', 'addComment');
            Route::delete('/blogs/{id}/comments/{commentId}', 'deleteComment');

            //Blog like
            Route::post('/blogs/{id}/likes', 'like');
            Route::delete('/blogs/{id}/likes', 'unlike');
        });


        Route::controller(BookmarkController::class)->group(function () {
            Route::get('/bookmarks', 'index');
            Route::post('/bookmarks', 'store');
            Route::delete('/bookmarks/{id}', 'destroy');
        });

        Route::controller(NotificationController::class)->group(function () {
            Route::get('/notifications', 'index');
            Route::patch('/notifications/{id}', 'markAsRead');
            Route::patch('/notifications', 'markAllAsRead');
        });
    });
});
