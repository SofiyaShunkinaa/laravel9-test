<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);      
    Route::get('/posts/{post}', [PostController::class, 'show']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    Route::get('/analytics/posts', [AnalyticsController::class, 'posts']);
    Route::get('/analytics/users', [AnalyticsController::class, 'users']);
});

Route::get('/meta/roles', [MetaController::class, 'roles']);
