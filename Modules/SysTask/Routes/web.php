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

Route::prefix('systask')->group(function () {
    Route::get('/', 'SysTaskController@index');
    Route::get('/create', 'SysTaskController@create');
    Route::get('/show/{id}', 'SysTaskController@show');
    Route::get('/edit/{id}', 'SysTaskController@edit');
    Route::post('/store', 'SysTaskController@store');
    Route::post('/store-data', 'SysTaskController@storeDataTask');
    Route::post('/update/{id}', 'SysTaskController@update');
    Route::get('/delete/{id}', 'SysTaskController@destroy');
    Route::get('/getdata/{id}', 'SysTaskController@getdata');
});
