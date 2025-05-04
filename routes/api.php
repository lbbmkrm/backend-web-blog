<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {
    Route::post('/login',  'login');
    Route::post('/register',  'register');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/me', 'me');
    });
});

Route::controller(BlogController::class)->group(function () {
    Route::get('/categories', 'categories');
    Route::get('/blogs', 'index');
    Route::get('/blogs/{id}', 'show');
});

Route::middleware('auth:sanctum')->group(function () {
    //user
    Route::controller(UserController::class)->group(function () {

        Route::get('/users/likes', 'likes');
        Route::get('/users', 'index');
        Route::get('/users/{id}', 'show');
        Route::post('/users/{id}/follow', 'follow');
        Route::delete('/users/{id}/unfollow', 'unFollow');
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
