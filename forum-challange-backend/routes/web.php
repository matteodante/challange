<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\TopicsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(TopicsController::class)->group(function () {
    Route::get('topics', 'index');
    Route::post('topic', 'store');
    Route::get('topic/{id}', 'show');
    Route::put('topic/{id}', 'update');
    Route::delete('topic/{id}', 'destroy');
});


// Rotte per la visualizzazione dei topic
Route::get('/topics', [TopicsController::class, 'index']);
Route::get('/topics/{topic}', [TopicsController::class, 'show']);

// Rotte per la creazione dei topic e dei commenti (richiede autenticazione)
Route::middleware('auth')->group(function () {
    //Rotte topics
    Route::post('/topics', [TopicsController::class, 'store']);
    Route::put('/topics/{id}', [TopicsController::class, 'update']);
    Route::delete('/topics/{id}', [TopicsController::class, 'destroy']);

    //Rotte comments
    Route::post('/topics/{topic}/comments', [CommentController::class, 'store']);
    Route::put('/topics/{topic}/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/topics/{topic}/comments/{id}', [CommentController::class, 'destroy']);
});

require __DIR__ . '/auth.php';
