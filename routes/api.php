<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('create', [LoginController::class, 'createUser']);
Route::post('login',  [LoginController::class, 'login']);
Route::group(['middleware' => 'auth:api'], function () {

    Route::get('list', [LoginController::class, 'userList']);
    Route::post('reset',  [LoginController::class, 'resetPassword']);
    Route::post('forgot', [LoginController::class, 'forgot']);
    Route::post('forgot-reset',  [LoginController::class, 'resetForgot']);
    Route::get('logout', [LoginController::class, 'userLogout']);
    Route::post('createblog',  [BlogController::class, 'createBlog']);
    Route::get('bloglist', [BlogController::class, 'blogListing']);
    Route::get('edit/{id}',  [BlogController::class, 'editBlog']);
    Route::post('update/{id}', [BlogController::class, 'updateBlog']);
    Route::delete('delete/{id}', [BlogController::class, 'deleteBlog']);
});
