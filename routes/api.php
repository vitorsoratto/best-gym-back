<?php

use App\Http\Controllers\CheckinController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;

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

Route::prefix('/user')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/profile', [UserController::class, 'profile'])->middleware('auth:api');
    Route::put('/', [UserController::class, 'update_user'])->middleware('auth:api');
});

Route::middleware('auth:api')->prefix('/gym')->group(function () {
    Route::get('/', [GymController::class, 'get_all']);
    Route::post('/', [GymController::class, 'store']);
    Route::put('/{id}', [GymController::class, 'update']);
    Route::delete('/{id}', [GymController::class, 'destroy']);
});

Route::middleware('auth:api')->prefix('/checkin')->group(function () {
    Route::post('/', [CheckinController::class, 'checkin']);
    Route::get('/', [CheckinController::class, 'checkin_history']);
});
