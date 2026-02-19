<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class NotifyDeployment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deployment-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify the application of a new deployment by updating the deployment timestamp.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Setting::setValue('deployment_timestamp', now()->timestamp);

        $this->info('Deployment timestamp updated successfully.');
    }
}
