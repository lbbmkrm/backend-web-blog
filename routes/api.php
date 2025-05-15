<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
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
        Route::get('/categories/{id}/blogs');
    });

    Route::middleware('auth:sanctum')->group(function () {
        //user
        Route::controller(UserController::class)->group(function () {

            Route::get('/users', 'index');
            Route::get('/users/{id}', 'show');
            Route::patch('/users/{id}', 'update');
            Route::post('/users/{id}/follow', 'follow');
            Route::delete('/users/{id}/follow', 'unfollow');
            Route::get('/users/{id}/followers', 'followers');
            Route::get('/users/{id}/following', 'following');
            Route::get('/users/{id}/likes', 'likes');
            Route::get('users/{id}/blogs', 'getBlogsByUser');
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
    });
});
