<?php

namespace App\Console\Commands;

use App\Services\ActivityLogService;
use Illuminate\Console\Command;

class CleanActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity-logs:clean {--days=365 : Number of days to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old activity logs';

    /**
     * Execute the console command.
     */
    public function handle(ActivityLogService $activityLogService)
    {
        $days = (int) $this->option('days');
        
        $this->info("Cleaning activity logs older than {$days} days...");
        
        $deletedCount = $activityLogService->cleanOldLogs($days);
        
        $this->info("Successfully deleted {$deletedCount} old activity logs.");
        
        return 0;
    }
}
