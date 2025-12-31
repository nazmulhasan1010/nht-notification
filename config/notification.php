<?php
return [
    'mail_template_path' => env('MAIL_TEMPLATE_PATH', 'emails'),
    'mail_subject_localize' => env('MAIL_SUBJECT_LOCALIZE', false),
    'sms_channel' => 'smsniagra', // twilio, smsniagra, custom
    'niaga_sms_base_url' => env('NIAGA_SMS_BASE_URL'),
    'niaga_sms_api_token' => env('NIAGA_SMS_API_TOKEN'),
    'sms_niaga_timeout' => env('SMS_NIAGA_TIMEOUT'),
    'niaga_sms_sender_id' => env('NIAGA_SMS_SENDER_ID'),

    'send_limit' => 500,
    'send_push_limit' => 200,
    'send_email_limit' => 200,
    'send_sms_limit' => 100,
    'api_prefix' => '/v1',
];
