<?php

namespace App\Console\Commands;

use App\Services\ContentScheduler;
use Illuminate\Console\Command;

class ProcessScheduledContentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:process-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all scheduled content items that are due for publication';

    /**
     * Execute the console command.
     */
    public function handle(ContentScheduler $scheduler): int
    {
        $this->info('Processing scheduled content items...');

        $processed = $scheduler->processDueItems();

        if ($processed > 0) {
            $this->info("Successfully processed {$processed} scheduled content item(s).");
        } else {
            $this->info('No scheduled content items due for processing.');
        }

        return Command::SUCCESS;
    }
}
