<?php

namespace App\Broadcasting;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class SmsChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @throws ConnectionException
     */
    public function send($notifiable, Notification $notification): PromiseInterface|Response
    {
        $message = $notification->toCustomSms($notifiable);

        return Http::post('https://www.twilio.com', [
            'to' => $notifiable->routeNotificationFor('customSms'),
            'message' => $message,
            'api_key' => config('services.sms.api_key'),
        ]);
    }
}
