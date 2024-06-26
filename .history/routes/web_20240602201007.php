<?php

use App\Http\Controllers\GoalController;

Route::middleware(['auth'])->group(function () {
    Route::resource('goals', GoalController::class);
});
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

// ミドルウェアを適用する
Route::middleware(['auth'])->group(function () {
    Route::get('/', [GoalController::class, 'index'])->middleware('auth');
    Route::resource('goals', GoalController::class)->only(['index', 'store', 'update', 'destroy'])->middleware('auth');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

