<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketsController;

Route::post('/login', [AuthController::class, 'login']);

// User routes
Route::post('/tickets', [TicketsController::class, 'store']);
Route::get('/tickets/{ref_id}', [TicketsController::class, 'show']);

// Agent routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/agent/tickets', [TicketsController::class, 'index']);
    Route::get('/agent/tickets/{reference}', [TicketsController::class, 'showAgent']);
    Route::post('/agent/tickets/{reference}/reply', [TicketsController::class, 'reply']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
