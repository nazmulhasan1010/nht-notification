<?php
use NH\Notification\Notifications\Notification;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('notifications', function () {
    return view('notifications');
});

Route::get('temp-notification', function () {
    $users = Auth::user();
    Notification::send([
        'sms' => [
            'phone' => '+18777804236',
            'message' => 'A new animal has been registered on your farm. Sms after issue fix',
        ],
    ]);

    Notification::sendPush([
        'sent_to_users' => $users,
        'data' => [
            'booking_id' => 1,
            'message' => 'Your booking has been confirmed.',
        ],
    ]);

    Notification::send([
        'mail-template' => [
            'template' => ['registration', 'Subject'],
            'subject' => 'Welcome to ALJ Harmony',
            'mail_to' => 'email@email.com',
            'mail_to_users' => $users,
            'data' => (object)[
                'user_name' => 'Anik Da',
                'role' => 'Agency Admin',
                'agency_name' => 'ALJ Harmony',
                'email' => 'example@email.com',
                'login_on' => route('customer.product'),
            ],
        ]
    ]);
});


Route::get('nh-notification/{page}', [NotificationController::class, 'get'])->name('notification.show-more');
Route::get('nh-notification-read/{item}', [NotificationController::class, 'read'])->name('nh-notification.read');
Route::delete('nh-notification/{id}', [NotificationController::class, 'delete'])->name('nh-notification.delete');