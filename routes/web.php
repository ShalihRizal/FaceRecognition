<?php

use App\Http\Controllers\UserController;
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

Route::get('/',             [UserController::class, 'login'])->name('login');
Route::get('/login',         [UserController::class, 'login'])->name('login');
Route::get('/register',         [UserController::class, 'register'])->name('register');
Route::get('/forgot',         [UserController::class, 'forgot'])->name('forgot');
Route::post('/do_forgot',     [UserController::class, 'sendforgot'])->name('forgot');
Route::post('/do_login',     [UserController::class, 'authenticate']);

Route::view('/unauthorize', 'exceptions.unauthorize');
// Route::view('/', 			'layouts.landing');

Route::get('/logout',             [UserController::class, 'logout'])->middleware('auth');
Route::get('/profile',             [UserController::class, 'profile'])->middleware('auth');
Route::post('/changepassword',     [UserController::class, 'changepassword'])->middleware('auth');
