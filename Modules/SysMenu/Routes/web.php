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

Route::prefix('sysmenu')->group(function() {
    Route::get('/', 'SysMenuController@index');
    Route::get('/create', 'SysMenuController@create');
    Route::get('/show/{id}', 'SysMenuController@show');
    Route::get('/edit/{id}', 'SysMenuController@edit');
    Route::post('/store', 'SysMenuController@store');
    Route::post('/update/{id}', 'SysMenuController@update');
    Route::get('/delete/{id}', 'SysMenuController@destroy');
    Route::get('/getdata/{id}', 'SysMenuController@getdata');
});
