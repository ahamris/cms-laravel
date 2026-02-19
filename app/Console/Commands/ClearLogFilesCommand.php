<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear specific log files from the storage directory.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $logFiles = [
            storage_path('logs/model-prunes.log'),
            storage_path('logs/translation-imports.log'),
        ];

        foreach ($logFiles as $logFile) {
            if (File::exists($logFile)) {
                File::put($logFile, '');
                $this->info("Cleared: {" . basename($logFile) . "}");
            }
        }

        $this->info('Log files cleared successfully.');
        return Command::SUCCESS;
    }
}
