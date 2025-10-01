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

Route::prefix('sysmodule')->group(function () {
    Route::get('/', 'SysModuleController@index');
    Route::get('/create', 'SysModuleController@create');
    Route::get('/show/{id}', 'SysModuleController@show');
    Route::get('/edit/{id}', 'SysModuleController@edit');
    Route::post('/store', 'SysModuleController@store');
    Route::post('/update/{id}', 'SysModuleController@update');
    Route::get('/delete/{id}', 'SysModuleController@destroy');
    Route::get('/getdata/{id}', 'SysModuleController@getdata');
});
