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
    Route::get('/blog/{blogId}', 'getSingleBlog');
});



Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', [UserController::class, 'getUsers']);

    Route::controller(BlogController::class)->group(function () {
        //Blog CRUD
        Route::post('blog/create',  'blogCreate');
        Route::put('blog/{blogId}/update',  'blogUpdate');
        Route::delete('blog/{blogId}/delete',  'blogDelete');

        //Blog Comment
        Route::post('blog/{blogId}/addComment', 'addComment');
        Route::delete('/blog/{blogId}/deleteComment', 'deleteComment');
    });
});
