<?php

namespace NH\Notification\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait LoadTemplate
{
    /**
     * @return object
     */
    private static function loadTemplates(): object
    {
        $viewsPath = resource_path('views/' . config('notification.mail_template_path'));

        $templates = [];

        if (File::exists($viewsPath)) {
            $templates = collect(File::allFiles($viewsPath))->mapWithKeys(function ($file) {
                $key = Str::replace(['.blade.php', '-'], ['', '_'], $file->getFilename());

                $view = Str::of($file->getPathname())->replace(resource_path('views/'), '')->replace(['.blade.php', DIRECTORY_SEPARATOR], ['', '.'])->value();

                return [$key => $view];
            })->toArray();
        }

        return (object)$templates;
    }
}