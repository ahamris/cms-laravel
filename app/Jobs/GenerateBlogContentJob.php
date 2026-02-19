<?php

namespace App\Jobs;

use App\Models\ContentPlanItem;
use App\Services\ExecutionEngine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateBlogContentJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ContentPlanItem $planItem
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ExecutionEngine $executionEngine): void
    {
        try {
            $executionEngine->generateBlogContent($this->planItem);
        } catch (\Exception $e) {
            Log::error('Failed to generate blog content', [
                'plan_item_id' => $this->planItem->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
