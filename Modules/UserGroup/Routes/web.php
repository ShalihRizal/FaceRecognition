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
use Modules\UserGroup\Http\Controllers\UserGroupController;

Route::prefix('usergroup')->group(function () {
    Route::get('/', [UserGroupController::class, 'index']);
    Route::get('/create', [UserGroupController::class, 'create']);
    Route::get('/show/{id}', [UserGroupController::class, 'show']);
    Route::get('/edit/{id}', [UserGroupController::class, 'edit']);
    Route::post('/store', [UserGroupController::class, 'store']);
    Route::post('/update/{id}', [UserGroupController::class, 'update']);
    Route::get('/delete/{id}', [UserGroupController::class, 'destroy']);
    Route::get('/getdata/{id}', [UserGroupController::class, 'getdata']);
});
