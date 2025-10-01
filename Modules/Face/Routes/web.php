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

Route::prefix('face')->group(function () {
    Route::get('/', 'FaceController@index');
    Route::get('/create', 'FaceController@create');
    Route::get('/show/{id}', 'FaceController@show');
    Route::get('/edit/{id}', 'FaceController@edit');
    Route::post('/store', 'FaceController@store');
    Route::post('/update/{id}', 'FaceController@update');
    Route::get('/delete/{id}', 'FaceController@destroy');
    Route::get('/getdata/{id}', 'FaceController@getdata');
});
