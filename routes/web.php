<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginTimeController;

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

Route::get('/', function () {
    $ip = request()->ip();
    $email = request()->input('email');
    return view('welcome', compact('ip', 'email'));
});
Route::get('/login-times', 'LoginTimeController@index')->name('login-times');
Route::get('/login-time', [LoginTimeController::class, 'index']);


