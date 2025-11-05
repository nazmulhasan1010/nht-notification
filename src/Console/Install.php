<?php

namespace NH\Notification\Console;

use Illuminate\Console\Command;
use NH\Notification\NotificationProvider;

class Install extends Command
{
    protected $signature = 'nht-notification:published {--force : Overwrite any existing files}';
    protected $description = 'Install nh|notification (publish config, views, and broadcast channel)';

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
            '--tag' => 'nh-config',
            '--force' => $this->option('force'),
        ]);

        $this->callSilent('vendor:publish', [
            '--provider' => NotificationProvider::class,
            '--tag' => 'nh-sms-channel',
            '--force' => $this->option('force'),
        ]);


        $this->info('MyTool installed!');
        $this->line('Config: config/notification.php');
        $this->line('Views: resources/views/emails/');
        $this->line('Broadcast channel: Broadcasting/SmsChannel.php');
        return self::SUCCESS;
    }
}
