<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\Dashboard\StatisticsController;
use App\Http\Controllers\Api\PublicPostController;

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

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/posts', [PublicPostController::class, 'index']);      
    Route::get('/posts/{post}', [PublicPostController::class, 'show']);
});

Route::get('/meta/roles', [MetaController::class, 'roles']);

Route::prefix('dashboard/stats')->group(function () {
    Route::get('/posts', [StatisticsController::class, 'posts']);
    Route::get('/comments', [StatisticsController::class, 'comments']);
    Route::get('/users', [StatisticsController::class, 'users']);
});
