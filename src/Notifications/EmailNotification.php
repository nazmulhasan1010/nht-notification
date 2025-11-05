<?php

namespace NH\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public object $data;
    public string $subject;
    public string $view;

    /**
     * @param $data
     * @param $subject
     * @param $view
     */
    public function __construct($data, $subject, $view = null)
    {
        $this->data = (object)$data;
        $this->subject = $subject;
        $this->view = $view;
    }

    /**
     * @param object $notifiable
     * @return string[]
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * @param object $notifiable
     * @return MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->view($this->view, [
            'user' => $notifiable,
            'data' => $this->data,
        ])->subject($this->subject);
    }
}
