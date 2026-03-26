<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SyncFontAwesomeProCommand extends Command
{
    protected $signature = 'fontawesome:sync-pro';

    protected $description = 'Copy Font Awesome Pro 6.5.2 from vendor into public/assets/fontawesome-pro (SVG+JS kit; optional webfonts if present)';

    public function handle(): int
    {
        $src = base_path('vendor/fontawesome-pro-6.5.2-web');
        if (! is_dir($src)) {
            $this->error('vendor/fontawesome-pro-6.5.2-web not found.');

            return self::FAILURE;
        }

        $dest = public_path('assets/fontawesome-pro');
        File::ensureDirectoryExists($dest.'/css');
        File::ensureDirectoryExists($dest.'/js');

        $pairs = [
            $src.'/css/svg-with-js.min.css' => $dest.'/css/svg-with-js.min.css',
            $src.'/js/all.min.js' => $dest.'/js/all.min.js',
        ];

        foreach ($pairs as $from => $to) {
            if (! is_file($from)) {
                $this->error("Missing: {$from}");

                return self::FAILURE;
            }
            File::copy($from, $to);
            $this->line('Copied '.basename($from));
        }

        $webfonts = $src.'/webfonts';
        if (is_dir($webfonts)) {
            File::copyDirectory($webfonts, $dest.'/webfonts');
            $this->info('Copied webfonts/ (you may use all.min.css + brands if you point layouts at those files).');
        } else {
            $this->comment('No webfonts/ in vendor package — using SVG+JS (no separate font files required).');
        }

        return self::SUCCESS;
    }
}
