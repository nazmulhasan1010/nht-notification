<?php

// for SMS notification use the format
use App\Notifications\Notification;

Notification::send([
    'sms' => [
        'phone' => '+60135871622',
//            'sms_to_users' => '<users>', // if you want to sent a specific user

        // cun = customer name
        // agn = agency name

        // url = login url
        'message' => __('account_registration', [
            'cun' => 'Lukmanul Hakim Hasibuan',
            'agn' => 'ALJ Harmony',
            'url' => route('login')
        ]),
    ],
]);


// for Booking Confirmation
Notification::send([
    'sms' => [
        'phone' => '+60135871622',

        // cun = customer name
        // bid = booking id
        // pn = package name
        // bkd = booked date
        // url = package ShortURL
        'message' => __('notifications.booking_confirmation', [
            'cun' => 'Nazmul',
            'bid' => 'BK1001',
            'pn' => 'Cox’s Bazar Tour',
            'bkd' => '2025-08-15',
            'url' => 'https://tourmate.com/p/bk1001'
        ]),
    ],
]);


// for Verification Otp
Notification::send([
    'sms' => [
        'phone' => '+60135871622',

        // agn = agency name
        // otp = otp
        'message' => __('notifications.verification_otp', [
            'agn' => 'TourMate',
            'otp' => '459027'
        ]),
    ],
]);


// for News
Notification::send([
    'sms' => [
        'phone' => '+60135871622',

        // agn = agency name
        // url = package ShortURL
        'message' => __('notifications.news', [
            'agn' => 'TourMate',
            'url' => 'https://tourmate.com/packages'
        ]),
    ],
]);


// for new Booking alert
Notification::send([
    'sms' => [
        'phone' => '+60135871622',

        // cun = customer name
        // bid = booking id
        // pn = package name
        // bkd = booked date
        'message' => __('notifications.booking_alert', [
            'bid' => 'BK1001',
            'cun' => 'Nazmul',
            'pn' => 'Sundarbans Adventure',
            'bkd' => '2025-09-10'
        ]),
    ],
]);


// for Payment Confirmation
Notification::send([
    'sms' => [
        'phone' => '+60135871622',

        // amount = amount with currency name eg: RM 30
        // cun = customer name
        // bid = booking id
        'message' => __('notifications.payment_confirmation', [
            'amount' => 'RM 1,200',
            'bid' => 'BK1001',
            'cun' => 'Nazmul'
        ]),
    ],
]);

// OR

Notification::sendSms([
    'phone' => '+18777804236',
    'message' => __('notification.account_registration', [
        'cun' => 'Lukmanul Hakim Hasibuan',
        'agn' => 'ALJ Harmony',
        'url' => route('login')
    ]),
]);


$users = \App\Models\User::all();

//email notification  ----------------------------------------------------------------------------

Notification::sendMailWithTemplate([
    'template' => ['withdrawal notification', 'Subject'], // Subject = Email subject\
    'subject' => 'From subject key', // (Optional) if you use subject here ['withdrawal notification', 'Subject'] and active email localization
    'mail_to_users' => $users,
    'data' => [
        'user_name' => 'Anik Da',
        'amount' => 1000,
        'submitted_by' => 'Lukmanul Hakim Hasibuan',
        'status' => 'Pending',
        'transaction_view_on' => route('customer.product'),
    ],
]);

// Or

// registration notification
Notification::send([
    'mail-template' => [
        'template' => ['registration', 'Subject'], // Subject = Email subject
        'subject' => 'From subject key', // (Optional) if you use subject here ['registration', 'Subject'] and active email localization
        'mail_to' => 'email@email.com', // this parameter allow a array too like  'mail_to' => ['email@email.com', 'email2@email.com', 'email3@email.com'],
        'mail_to_users' => $users, // (Optional) if you want to sent to any user
        'data' => (object)[
            'user_name' => 'Anik Da',
            'role' => 'Agency Admin',
            'agency_name' => 'ALJ Harmony',
            'email' => 'example@email.com',
            'login_on' => route('customer.product'),
        ],
    ]
]);

// custom notification
Notification::send([
    'mail' => [
        'mail_to' => 'email@email.com', // this parameter allow a array too like  'mail_to' => ['email@email.com', 'email2@email.com', 'email3@email.com'],
        'mail_to_users' => $users, // (Optional) if you want to sent to any user
        'subject' => 'Email Subject',
        'view' => 'applications.agents.emails.withdrawal-notification', // view file path
        'data' => (object)[
            'user_name' => 'Anik Da',
            'amount' => 1000,
            'submitted_by' => 'Lukmanul Hakim Hasibuan',
            'status' => 'Pending',
            'transaction_view_on' => route('customer.product'),
        ],
    ]
]);

// OR

Notification::sendMail([
    'mail_to' => 'email@email.com',// this parameter allow a array too like  'mail_to' => ['email@email.com', 'email2@email.com', 'email3@email.com'],
    'mail_to_users' => $users, // (Optional) if you want to sent to any user
    'subject' => 'Subject',
    'view' => 'applications.agents.emails.withdrawal-notification',
    'data' => (object)[
        'user_name' => 'Anik Da',
        'amount' => 1000,
        'submitted_by' => 'Lukmanul Hakim Hasibuan',
        'status' => 'Pending',
        'transaction_view_on' => route('customer.product'),
    ],
]);


// push notification ----------------------------------------------------------------------------

Notification::send([
    'push' => [
        'sent_to_users' => $users,
        'data' => [
            'booking_id' => 1,
            'message' => 'Your booking has been confirmed.',
        ],
    ]
]);

// OR

Notification::sendPush([
    'sent_to_users' => $users,
    'data' => [
        'booking_id' => 1,
        'message' => 'Your booking has been confirmed.',
    ],
]);
