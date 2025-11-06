<?php

namespace App\Broadcasting;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use JsonException;

class SmsNiagaChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }


    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function send($notifiable, Notification $notification): string
    {
        $notify = (object)$notification->toCustomSms($notifiable);

        try {
            Http::withHeaders(['Authorization' => 'Bearer ' . config('notification.niaga_sms_api_token')])->timeout(config('notification.sms_niaga_timeout'))->baseUrl(config('notification.niaga_sms_base_url'))->acceptJson()->asJson()->post('/api/send', [
                'body' => $notify->message,
                'phones' => (array)$notify->to,
                'sender_id' => config('notification.niaga_sms_sender_id'),
            ]);

            return 'success';
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }
}
