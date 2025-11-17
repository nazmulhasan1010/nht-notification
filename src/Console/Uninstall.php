<?php

namespace NH\Notification\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Uninstall extends Command
{
    /**
     * @var string
     */
    protected $signature = 'nht-notification:remove {--force : Force delete without confirmation}';

    /**
     * @var string
     */
    protected $description = 'Uninstall nh|notification (remove config, views, styles, controllers, routes and broadcast channel)';

    /**
     * @return int
     */
    public function handle(): int
    {
        $this->info('Uninstalling nh|Notification...');

        $fs = new Filesystem();

        $paths = [
            config_path('notification.php'),
            app_path('Broadcasting/SmsChannel.php'),
            app_path('Broadcasting/SmsNiagaChannel.php'),
            app_path('Http/Controllers/NotificationController.php'),
        ];

        foreach ($paths as $path) {
            if ($fs->exists($path)) {
                if ($this->option('force') || $this->confirm("Delete {$path}?", true)) {
                    $fs->delete($path);
                    $fs->deleteDirectory($path);
                    $this->line("Removed: {$path}");
                }
            }
        }

        $this->callSilent('config:clear');
        $this->callSilent('view:clear');
        $this->callSilent('cache:clear');

        $this->newLine();
        $this->info('✅ nh|Notification successfully uninstalled!');
        $this->line('All related config, templates, and channels have been removed.');

        return self::SUCCESS;
    }
}
