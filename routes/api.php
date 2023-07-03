<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\APIController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('attendance/start', [AttendanceController::class, 'start']);
Route::get('attendance/end', [AttendanceController::class, 'end']);
Route::get('attendance/calculate', [AttendanceController::class, 'calculate']);
Route::get('attendance/check-working-hours', [AttendanceController::class, 'checkWorkingHours']);
Route::get('/login-times', 'LoginTimeController@index')->name('login-times');
Route::get('/calculate-hours', 'App\Http\Controllers\APIController@calculateHours');


?>

