<?php

namespace NH\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use App\Broadcasting\SmsChannel;
use App\Broadcasting\SmsNiagaChannel;

class SmsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $message;
    public string|array $phone;
    private $channels = [
        'twilio' => TwilioChannel::class,
        'smsniagra' => SmsNiagaChannel::class,
        'custom' => SmsChannel::class,
    ];

    /**
     * @param $notify
     */
    public function __construct($notify)
    {
        $this->message = $notify->message;
        $this->phone = $notify->phone;
    }

    /**
     * @param object $notifiable
     * @return class-string[]
     */
    public function via(object $notifiable): array
    {
        return [$this->channels[config('notification.sms_channel')] ?? null];
    }

    /**
     * @param object $notifiable
     * @return array
     */
    public function toCustomSms(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'to' => $this->phone,
        ];
    }

    /**
     * @param object $notifiable
     * @return VonageMessage
     */
    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage)->content($this->message);
    }

    /**
     * @param object $notifiable
     * @return TwilioSmsMessage
     */
    public function toTwilio(object $notifiable): TwilioSmsMessage
    {
        return (new TwilioSmsMessage())->content($this->message);
    }

}
