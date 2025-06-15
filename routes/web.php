<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TelegramBotController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/settings', [SettingController::class, 'show'])->name('dashboard');
    Route::patch('/settings/{setting}', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/telegram/set/webhook', [TelegramBotController::class, 'setWebhook'])->name('telegram.set.webhook');

    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');


});

require __DIR__ . '/auth.php';
