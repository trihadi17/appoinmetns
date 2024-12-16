<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AppointmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
     // AUTH
     Route::get('user', [AuthController::class, 'getUser']);
     Route::post('logout', [AuthController::class, 'logout']);
     Route::post('refresh', [AuthController::class, 'refresh']);

     // APPOINTMENT
    Route::get('appointment', [AppointmentController::class,'index']);
    Route::post('appointment', [AppointmentController::class,'store']);
    Route::get('other', [AppointmentController::class,'otherUsers']);
     
});

