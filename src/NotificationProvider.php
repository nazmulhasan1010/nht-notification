<?php

namespace NH\Notification;

use Illuminate\Support\ServiceProvider;
use NH\Notification\Console\Install;
use NH\Notification\Console\Uninstall;

class NotificationProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'notification');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views'),
        ], 'nh-templates');

        $this->publishes([
            __DIR__ . '/../resources/nht-scss' => public_path('assets/nht-scss'),
        ], 'nh-styles');

        $this->publishes([
            __DIR__ . '/../config/notification.php' => config_path('notification.php'),
        ], 'nh-config');

        $this->publishes([
            __DIR__ . '/../src/Support' => app_path('Broadcasting'),
        ], 'nh-sms-channel');

        $this->publishes([
            __DIR__ . '/../src/Controllers' => app_path('Http/Controllers'),
        ], 'nh-controllers');


        if ($this->app->runningInConsole()) {
            $this->commands([
                Install::class,
                Uninstall::class,
            ]);
        }
    }
}
