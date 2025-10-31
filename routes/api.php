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
    Route::get('appointments', [AppointmentController::class, 'index']);
    Route::post('appointments', [AppointmentController::class, 'store']);
    Route::get('appointments/{id}', [AppointmentController::class, 'show']);
    Route::put('appointments/{id}/confirm', [AppointmentController::class, 'confirm']);
    Route::put('appointments/{id}/cancel', [AppointmentController::class, 'cancel']);
    Route::delete('appointments/{id}', [AppointmentController::class, 'destroy']);

    // Notifications (histórico de notificações do usuário)
    Route::get('notifications', [NotificationController::class, 'index']);

    //ROTAS ADMINISTRATIVAS
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::apiResource('users', UserController::class);
        // Exemplo: Route::get('services', [ServiceController::class, 'index']);
    });
});
