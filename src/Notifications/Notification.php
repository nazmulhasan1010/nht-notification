<?php

namespace NH\Notification\Notifications;

use NH\Notification\Notifications\EmailNotification;
use NH\Notification\Notifications\PushNotification;
use NH\Notification\Notifications\SmsNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification as LaraNotify;
use Illuminate\Support\Str;


class Notification
{
    /**
     * @var object
     */
    protected object $views;

    public function __construct()
    {
        $viewsPath = resource_path('views/' . config('notification.mail_template_path'));

        $templates = [];
        if (File::exists($viewsPath)) {
            $files = File::allFiles($viewsPath);

            $templates = collect($files)->mapWithKeys(function ($file) {
                $key = Str::replace(['.blade.php', '-'], ['', '_'], $file->getFilename());
                $view = Str::of($file->getPathname())->replace(resource_path('views/'), '')->replace(['.blade.php', DIRECTORY_SEPARATOR], ['', '.'])->value();

                return [$key => $view];
            })->toArray();
        }

        $this->views = (object)$templates;
    }

    /**
     * @param $notifications
     * @return int
     */
    public static function send($notifications): int
    {
        $own = new self();
        foreach ($notifications as $channel => $notification) {
            $notify = (object)$notification;

            if ($channel === 'mail') {

                $email = new EmailNotification($notify->data, $notify->subject, $notify->view);
                if (property_exists($notify, 'mail_to')) {
                    $own->mailTo($notify, $email);
                }

                if (property_exists($notify, 'mail_to_users')) {
                    $own->mailToUsers($notify, $email);
                }
            }

            if ($channel === 'mail-template') {
                $email = $own->template($notify->template, $notify->data, $notify->subject ?? null);

                if (property_exists($notify, 'mail_to')) {
                    $own->mailTo($notify, $email);
                }

                if (property_exists($notify, 'mail_to_users')) {
                    $own->mailToUsers($notify, $email);
                }
            }

            if ($channel === 'sms') {
                $sms = new SmsNotification($notify);

                if (property_exists($notify, 'phone')) {
                    $own->smsTo($notify, $sms);
                }

                if (property_exists($notify, 'sms_to_users')) {
                    $own->smsToUsers($notify, $sms);
                }
            }

            if ($channel === 'push') {
                $push = new PushNotification($notify->data);
                $own->sentUserToPush($notify, $push);
            }
        }

        return true;
    }

    /**
     * @param $notification
     * @return int
     */
    public static function sendPush($notification): int
    {
        $own = new self();
        $notify = (object)$notification;

        $push = new PushNotification($notify->data);
        $own->sentUserToPush($notify, $push);

        return true;
    }

    /**
     * @param $notification
     * @return int
     */
    public static function sendMail($notification): int
    {
        $own = new self();
        $notify = (object)$notification;

        $email = new EmailNotification($notify->data, $notify->subject, $notify->view);
        if (property_exists($notify, 'mail_to')) {
            $own->mailTo($notify, $email);
        }

        if (property_exists($notify, 'mail_to_users')) {
            $own->mailToUsers($notify, $email);
        }

        return true;
    }

    /**
     * @param $notification
     * @return true|int
     */
    public static function sendMailWithTemplate($notification): true|int
    {
        $own = new self();
        $notify = (object)$notification;

        $email = $own->template($notify->template, $notify->data, $notify->subject ?? null);

        if (property_exists($notify, 'mail_to')) {
            $own->mailTo($notify, $email);
        }

        if (property_exists($notify, 'mail_to_users')) {
            $own->mailToUsers($notify, $email);
        }

        return true;
    }

    /**
     * @param $notification
     * @return int
     */
    public static function sendSms($notification): int
    {
        $own = new self();
        $notify = (object)$notification;

        $sms = new SmsNotification($notify);

        if (property_exists($notify, 'phone')) {
            $own->smsTo($notify, $sms);
        }

        if (property_exists($notify, 'sms_to_users')) {
            $own->smsToUsers($notify, $sms);
        }

        return true;
    }

    /**
     * @param $pref
     * @param $data
     * @param null $ds
     * @return \Notifications\EmailNotification
     */
    protected function template($pref, $data, $ds = null): EmailNotification
    {
        $lc = config('notification.mail_subject_localize');
        $vn = Str::of($pref[0])->replace(['-', '.', '/', ' '], '_');
        $subject = $lc ? __('notification.' . $pref[0], ['dyv' => $pref[1] ?? null]) : $ds ?? $pref[1];
        return new EmailNotification($data, $subject, $this->views->{$vn});
    }

    /**
     * @param $notify
     * @param $template
     * @return void
     */
    protected function mailTo($notify, $template): void
    {
        if (is_array($notify->mail_to)) {
            foreach ($notify->mail_to as $ml) {
                LaraNotify::route('mail', $ml)->notify($template);
            }
        } else {
            LaraNotify::route('mail', $notify->mail_to)->notify($template);
        }
    }

    /**
     * @param $notify
     * @param $template
     * @return void
     */
    protected function mailToUsers($notify, $template): void
    {
        LaraNotify::send($notify->mail_to_users, $template);
    }

    /**
     * @param $notify
     * @param $sms
     * @return void
     */
    protected function smsTo($notify, $sms): void
    {
        if (is_array($notify->phone)) {
            foreach ($notify->phone as $phone) {
                LaraNotify::route(config('notification.sms_channel'), $phone)->notify($sms);
            }
        } else {
            LaraNotify::route(config('notification.sms_channel'), $notify->phone)->notify($sms);
        }
    }

    /**
     * @param $notify
     * @param $sms
     * @return void
     */
    protected function smsToUsers($notify, $sms): void
    {
        LaraNotify::send($notify->sms_to_users, $sms);
    }

    /**
     * @param $notify
     * @param $push
     * @return void
     */
    protected function sentUserToPush($notify, $push): void
    {
        LaraNotify::send($notify->sent_to_users, $push);
    }

    /**
     * @param null $user
     * @return object
     */
    public static function get($user = null): object
    {
        $fiu = $user ?? Auth::user();
        return (object)[
            'all' => $fiu?->notifications->sortByDesc('created_at'),
            'unread' => $fiu?->unreadNotifications->sortByDesc('created_at')
        ];
    }
}

