<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
 */


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rota para buscar um usuário específico por ID
    Route::apiResource('user',\App\Http\Controllers\Api\UserController::class);
    
Route::apiResource('services', \App\Http\Controllers\Api\ServiceController::class);

Route::get('appointments', [\App\Http\Controllers\Api\AppointmentController::class, 'index']);
Route::post('appointments', [\App\Http\Controllers\Api\AppointmentController::class, 'store']);
Route::get('appointments/{id}', [\App\Http\Controllers\Api\AppointmentController::class, 'show']);
Route::put('appointments/{id}/confirm', [\App\Http\Controllers\Api\AppointmentController::class, 'confirm']);
Route::put('appointments/{id}/cancel', [\App\Http\Controllers\Api\AppointmentController::class, 'cancel']);
Route::delete('appointments/{id}', [\App\Http\Controllers\Api\AppointmentController::class, 'destroy']);


});
