<?php

namespace App\Console\Commands;

use App\Jobs\UpdateContentPerformanceJob;
use Illuminate\Console\Command;

class UpdateContentPerformanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:update-performance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update content performance metrics (CTR, impressions, engagement, rankings)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Updating content performance metrics...');

        // Dispatch the job to update performance
        UpdateContentPerformanceJob::dispatch();

        $this->info('Content performance update job dispatched.');

        return Command::SUCCESS;
    }
}
