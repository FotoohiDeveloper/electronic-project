<?php

use App\Http\Controllers\LoginController;
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

Route::middleware('check')->group(function (){
    Route::prefix('auth')->group(function () {
        Route::post('/register', [RegisterController::class, 'store']);
        Route::post('/login', [LoginController::class, 'store']);
    });
});

Route::prefix('dashboard')->group(function (){
    Route::middleware('auth:sanctum')->group(function (){
        Route::prefix('user')->group(function (){
            Route::get('/', [UserController::class, 'show']);
        });
    });
});