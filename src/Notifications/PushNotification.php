<?php

namespace NH\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PushNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public array $data;

    /**
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @param object $notifiable
     * @return string[]
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @param object $notifiable
     * @return array
     */
    public function toArray(object $notifiable): array
    {
        return $this->data;
    }
}
