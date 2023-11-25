<?php

use App\Http\Controllers\Api\Article\ArticleCommentController;
use App\Http\Controllers\Api\Article\ArticleController;
use App\Http\Controllers\Api\Article\ArticleLikeController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Blog\BlogCommentController;
use App\Http\Controllers\Api\Blog\BlogController;
use App\Http\Controllers\Api\Blog\BlogLikeController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
    Route::post('/forget-password', [PasswordResetController::class, 'forgotPassword']);
});

Route::post('/images', [ImageController::class, 'store']);

Route::post('blogs', [BlogController::class, 'store']);
Route::get('blogs', [BlogController::class, 'index']);

Route::post('articles', [ArticleController::class, 'store']);
Route::get('articles', [ArticleController::class, 'index']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    Route::prefix('settings')->group(function () {
        Route::post('/email/change', [UserController::class, 'changeEmail']);
        Route::post('/email/change/confirm', [UserController::class, 'changeEmailConfirm']);
        Route::put('/profile/update', [UserController::class, 'updateProfile']);
    });

    Route::post('blogs/{blog}/likes', [BlogLikeController::class, 'store']);
    Route::post('blogs/{blog}/comments', [BlogCommentController::class, 'store']);

    Route::post('articles/{article}/likes', [ArticleLikeController::class, 'store']);
    Route::post('articles/{article}/comments', [ArticleCommentController::class, 'store']);
});
