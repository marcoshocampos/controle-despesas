<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DespesasController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('despesas', DespesasController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});