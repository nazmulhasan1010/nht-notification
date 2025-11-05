<?php

namespace NH\Notification;

use Illuminate\Support\ServiceProvider;
use NH\Notification\Console\Install;
use NH\Notification\Console\Uninstall;

class NotificationProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'notification');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/emails'),
        ], 'nh-templates');

        $this->publishes([
            __DIR__ . '/../config/notification.php' => config_path('notification.php'),
        ], 'nh-config');

        $this->publishes([
            __DIR__ . '/../src/Support' => app_path('Broadcasting'),
        ], 'nh-sms-channel');


        if ($this->app->runningInConsole()) {
            $this->commands([
                Install::class,
                Uninstall::class,
            ]);
        }
    }
}
