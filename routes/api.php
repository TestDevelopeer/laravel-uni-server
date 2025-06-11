<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\TelegramBotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/telegram/webhook', [TelegramBotController::class, 'webhook']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth:sanctum');

