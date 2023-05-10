<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\JWTAuthController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('jwt')->group(function () {
    Route::post('login', [JWTAuthController::class, 'login']);
    Route::get('user', [JWTAuthController::class, 'user']);
    Route::post('register', [JWTAuthController::class, 'register']);
    Route::post('logout', [JWTAuthController::class, 'logout']);
    Route::post('refresh', [JWTAuthController::class, 'refresh']);
});
