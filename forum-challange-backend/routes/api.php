<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\JWTAuthController;
use App\Http\Controllers\TopicsController;
use App\Http\Controllers\CommentController;

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


// Rotte per la visualizzazione dei topic
Route::get('/topics', [TopicsController::class, 'index']);
Route::get('/topics/{topic}', [TopicsController::class, 'show']);

// Rotte per la creazione dei topic e dei commenti (richiede autenticazione)
Route::middleware('auth:web')->group(function () {
    //Rotte topics
    Route::post('/topics', [TopicsController::class, 'store']);
    Route::put('/topics/{topic}', [TopicsController::class, 'update']);
    Route::delete('/topics/{topic}', [TopicsController::class, 'delete']);

    //Rotte comments
    Route::post('/topics/{topic}/comments', [CommentController::class, 'store']);
    Route::put('/topics/{topic}/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/topics/{topic}/comments/{comment}', [CommentController::class, 'delete']);
});

Route::middleware('auth:api')->prefix('jwt')->group(function () {
    //Rotte topics
    Route::post('/topics', [TopicsController::class, 'store']);
    Route::put('/topics/{topic}', [TopicsController::class, 'update']);
    Route::delete('/topics/{topic}', [TopicsController::class, 'delete']);

    //Rotte comments
    Route::post('/topics/{topic}/comments', [CommentController::class, 'store']);
    Route::put('/topics/{topic}/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/topics/{topic}/comments/{comment}', [CommentController::class, 'delete']);
});


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
