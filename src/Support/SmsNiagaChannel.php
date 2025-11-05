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

        $client = new Client([
            'base_uri' => config('notification.niaga_sms_base_url'),
            'timeout' => config('notification.sms_niaga_timeout'),
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config('notification.niaga_sms_api_token'),
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);

        try {
            $response = $client->post('/api/send', [
                'json' => [
                    'body' => $notify->message,
                    'phones' => is_array($notify->to) ? $notify->to : [$notify->to],
                    'sender_id' => config('notification.niaga_sms_sender_id'),
                ],
            ]);
            return 'success';
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }
}
