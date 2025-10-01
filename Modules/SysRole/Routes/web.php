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

Route::prefix('sysrole')->group(function() {
    Route::get('/', 'SysRoleController@index');
    Route::get('/create', 'SysRoleController@create');
    Route::get('/show/{id}', 'SysRoleController@show');
    Route::get('/edit/{id}', 'SysRoleController@edit');
    Route::post('/store', 'SysRoleController@store');
    Route::post('/update/{id}', 'SysRoleController@update');
    Route::get('/delete/{id}', 'SysRoleController@destroy');
    Route::get('/getdata/{id}', 'SysRoleController@getdata');
});
