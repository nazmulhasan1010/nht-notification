<?php

use NH\Notification\Notifications\Notification;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('nht-notifications', function () {
        return view('notifications');
    });

    Route::get('temp-sms-notification/{phone}/{count?}', function ($phone, $count = 1) {
        Notification::send([
            'sms' => [
                'phone' => $phone,
                'message' => 'This is a test SMS from NHT Notification.',
            ],
        ]);

        return response()->view('nht-notification::temp', ['data' => (object)[
            'type' => 'SMS',
            'count' => $count
        ]], 200);
    });

    Route::get('temp-email-notification/{email}/{count?}', function ($email, $count = 1) {
        $count = max(1, min((int) $count, 10));
        for ($i = 0; $i < $count; $i++) {
            Notification::send([
                'mail-template' => [
                    'template' => ['registration', 'Subject'],
                    'subject' => 'Welcome NHT Notification',
                    'mail_to' => $email,
                    'data' => (object)[
                        'user_name' => 'User Name',
                        'role' => 'Agency Admin',
                        'agency_name' => 'Agency name',
                        'email' => 'example@email.com',
                        'login_on' => '[page url]',
                    ],
                ]
            ]);
        }

        return response()->view('nht-notification::temp', ['data' => (object)[
            'type' => 'Email',
            'count' => $count
        ]], 200);
    });

    Route::get('temp-push-notification/{count?}', function ($count = 1) {
        $users = Auth::user();
        $count = max(1, min((int)$count, 10));
        for ($i = 0; $i < $count; $i++) {
            Notification::sendPush([
                'sent_to_users' => $users,
                'data' => [
                    'type' => 'info',
                    'title' => 'Notification title here',
                    'message' => 'Notification long message here...',
                    'link' => 'any link',
                ]
            ]);
        }

        return response()->view('nht-notification::temp', ['data' => (object)[
            'type' => 'Push',
            'count' => $count
        ]], 200);
    });

    Route::get('nh-notification/{page}', [NotificationController::class, 'get'])->name('notification.show-more');
    Route::get('nh-notification-read/{item}', [NotificationController::class, 'read'])->name('nh-notification.read');
    Route::delete('nh-notification/{id}', [NotificationController::class, 'delete'])->name('nh-notification.delete');
});