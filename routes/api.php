<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [LoginController::class, 'store']);

Route::prefix('dashboard')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'show']);
        });

        Route::prefix('post')->group(function (){
            Route::post('/', [PostController::class, 'store']);
            Route::patch('/{post_id}', [PostController::class, 'update']);
            Route::delete('/{post_id}', [PostController::class, 'delete']);
            Route::get('/{post_id}', [PostController::class, 'show']);
        });

        Route::get('/posts', [PostController::class, 'all']);
    });
});