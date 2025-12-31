<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('api'. config('notification.api_prefix'))->group(function () {
    Route::get('notifications', [NotificationController::class, 'notifications']);
    Route::put('notification/read/{item}', [NotificationController::class, 'read']);
    Route::delete('notification/delete/{item}', [NotificationController::class, 'delete']);
});