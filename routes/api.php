<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {
    Route::post('/login',  'login');
    Route::post('/register',  'register');
});

Route::controller(BlogController::class)->group(function () {
    Route::get('/categories', 'getCategories');
    Route::get('/blogs', 'getAllBlogs');
    Route::get('/blogs/{blogId}', 'getSingleBlog');
});

Route::middleware('auth:sanctum')->group(function () {
    //user
    Route::controller(UserController::class)->group(function () {

        Route::get('/users/likes', 'likes');
        Route::get('/users', 'getAllUsers');
        Route::get('/users/{userId}', 'getUser');
        Route::post('/users/{userId}/follow', 'follow');
        Route::delete('/users/{userId}/unfollow', 'unFollow');
    });

    Route::controller(BlogController::class)->group(function () {
        //Blog CRUD
        Route::post('/blogs/create',  'blogCreate');
        Route::put('/blogs/{blogId}/update',  'blogUpdate');
        Route::delete('/blogs/{blogId}/delete',  'blogDelete');

        //Blog Comment
        Route::post('/blogs/{blogId}/addComment', 'addComment');
        Route::delete('/blogs/{blogId}/deleteComment', 'deleteComment');

        //Blog like
        Route::post('/blogs/{blogId}/like', 'like');
        Route::delete('/blogs/{blogId}/unlike', 'unlike');
    });
});
