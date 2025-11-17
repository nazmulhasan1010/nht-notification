<?php

namespace NH\Notification\Console;

use Illuminate\Console\Command;
use NH\Notification\NotificationProvider;

class Install extends Command
{
    /**
     * @var string
     */
    protected $signature = 'nht-notification:published {--force : Overwrite any existing files}';

    /**
     * @var string
     */
    protected $description = 'Install nh|notification (publish config, views, styles, controllers, routes and broadcast channel)';

    /**
     * @return int
     */
    public function handle(): int
    {
        $this->info('Installing nh|notification...');

        $this->callSilent('vendor:publish', [
            '--provider' => NotificationProvider::class,
            '--tag' => 'nh-templates',
            '--force' => $this->option('force'),
        ]);

        $this->callSilent('vendor:publish', [
            '--provider' => NotificationProvider::class,
            '--tag' => 'nh-styles',
            '--force' => $this->option('force'),
        ]);

        $this->callSilent('vendor:publish', [
            '--provider' => NotificationProvider::class,
            '--tag' => 'nh-config',
            '--force' => $this->option('force'),
        ]);

        $this->callSilent('vendor:publish', [
            '--provider' => NotificationProvider::class,
            '--tag' => 'nh-sms-channel',
            '--force' => $this->option('force'),
        ]);

        $this->callSilent('vendor:publish', [
            '--provider' => NotificationProvider::class,
            '--tag' => 'nh-controllers',
            '--force' => $this->option('force'),
        ]);


        $this->info('nht-notification installed!');
        $this->info('Config: config/notification.php');
        $this->info('Views: resources/views/emails/');
        $this->info('Styles: public/assets/nht-scss/');
        $this->info('Broadcast channels: Broadcasting/');
        $this->info('Controllers: app/Http/Controllers/');
        return self::SUCCESS;
    }
}
