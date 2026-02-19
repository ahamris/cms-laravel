<?php

namespace App\Jobs;

use App\Models\IntentBrief;
use App\Services\StrategyEngine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateContentPlanJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public IntentBrief $intentBrief
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(StrategyEngine $strategyEngine): void
    {
        try {
            $strategyEngine->generateContentPlan($this->intentBrief);
        } catch (\Exception $e) {
            Log::error('Failed to generate content plan', [
                'intent_brief_id' => $this->intentBrief->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
