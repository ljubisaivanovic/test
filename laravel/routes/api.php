<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;

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

//posts
Route::post('/posts', [PostController::class, 'store']);
Route::get('/posts', [PostController::class, 'index']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);

//comments
Route::post('/comments', [CommentController::class, 'store']);
Route::get('/comments', [CommentController::class, 'index']);
Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
