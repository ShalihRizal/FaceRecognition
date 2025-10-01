<?php

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

use Illuminate\Support\Facades\Route;
use Modules\Configuration\Http\Controllers\ConfigurationController;

Route::prefix('configuration')->group(function () {
    Route::get('/', [ConfigurationController::class, 'index']);
    Route::post('/store', [ConfigurationController::class, 'store']);
    Route::get('/edit/{id}', [ConfigurationController::class, 'edit']);
    Route::post('/update/{id}', [ConfigurationController::class, 'update']);
    Route::get('/delete/{id}', [ConfigurationController::class, 'destroy']);
    Route::get('/getdata/{id}', [ConfigurationController::class, 'getdata']);
});
