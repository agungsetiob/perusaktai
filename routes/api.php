<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SimrsRoomController;


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

Route::middleware('throttle:19,1')->group(function () {
    Route::get('/simrs/installations', [SimrsRoomController::class, 'installations']);
    Route::get('/simrs/installations/{installation}/rooms', [SimrsRoomController::class, 'rooms']);
});
