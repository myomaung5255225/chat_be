<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\ChatsController;

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

Route::middleware('apiauth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('/user', [AuthController::class, "getUser"]);
        Route::get('/users', [AuthController::class, 'getUsers']);
        Route::post("/logout", [AuthController::class, 'logoutUser']);
    });
    Route::prefix('chat')->group(function () {
        Route::get('/messages/{userId}', [ChatsController::class, 'getMessages']);
        Route::post('/message', [ChatsController::class, 'sentMessage']);
    });
});

Route::post("/register", [AuthController::class, 'registerUser']);
Route::post("/login", [AuthController::class, 'loginUser']);
