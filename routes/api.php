<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Rotas públicas e autenticadas da SmartSchedule API.
| Estruturadas por blocos de responsabilidade.
|--------------------------------------------------------------------------
*/

// ROTAS PÚBLICAS (sem autenticação)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//  ROTAS PROTEGIDAS (usuário autenticado)
Route::middleware('auth:sanctum')->group(function () {

    // Perfil do usuário autenticado
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Services (CRUD)
    Route::apiResource('services', ServiceController::class);

    // Appointments (agendamentos)
    Route::apiResource('appointments', AppointmentController::class)->except(['update']);
    // Rotas customizadas para confirmar e cancelar
    Route::patch('appointments/{appointment}/confirm', [AppointmentController::class, 'confirm']);
    Route::patch('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel']);

    // Notifications (histórico de notificações do usuário)
    Route::get('notifications', [NotificationController::class, 'index']);

    //ROTAS ADMINISTRATIVAS
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::apiResource('users', UserController::class)->except(['show', 'update', 'destroy']);
        Route::apiResource('users', UserController::class)->only(['show', 'update', 'destroy']);
    });
});
