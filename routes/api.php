<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('notifications', [NotificationController::class, 'notifications']);
    Route::get('notification/read/{item}', [NotificationController::class, 'read']);
    Route::get('notification/delete/{item}', [NotificationController::class, 'delete']);
});