<?php

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

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__ . '/auth.php';
