<?php

namespace NH\Notification\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Uninstall extends Command
{
    protected $signature = 'nht-notification:remove {--force : Force delete without confirmation}';
    protected $description = 'Uninstall nh|notification (remove config, views, and broadcast channel)';

    public function handle(): int
    {
        $this->info('Uninstalling nh|Notification...');

        $fs = new Filesystem();

        // Files and directories to remove
        $paths = [
            config_path('notification.php'),
//            resource_path('views/emails'),
            app_path('Broadcasting/SmsChannel.php'),
            app_path('Broadcasting/SmsNiagaChannel.php'),
        ];

        foreach ($paths as $path) {
            if ($fs->exists($path)) {
                if ($this->option('force') || $this->confirm("Delete {$path}?", true)) {
                    $fs->delete($path);
                    $fs->deleteDirectory($path); // in case of directory
                    $this->line("🗑️  Removed: {$path}");
                }
            }
        }

        // Clear cached config, routes, and views
        $this->callSilent('config:clear');
        $this->callSilent('view:clear');
        $this->callSilent('cache:clear');

        $this->newLine();
        $this->info('✅ nh|Notification successfully uninstalled!');
        $this->line('All related config, templates, and channels have been removed.');

        return self::SUCCESS;
    }
}
