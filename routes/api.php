<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\JournalController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\TelegramBotController;
use Illuminate\Support\Facades\Route;

Route::post('/telegram/webhook', [TelegramBotController::class, 'webhook']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);

    Route::get('/journal', [JournalController::class, 'show']);
    Route::post('/journal', [JournalController::class, 'update']);

    Route::post('/telegram/send/message', [\App\Http\Controllers\Api\V1\TelegramBotController::class, 'sendMessage']);
});

