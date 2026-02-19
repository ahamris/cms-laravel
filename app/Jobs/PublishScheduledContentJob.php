<?php

namespace App\Jobs;

use App\Models\Blog;
use App\Services\ExecutionEngine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class PublishScheduledContentJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Blog $blog
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ExecutionEngine $executionEngine): void
    {
        try {
            // Activate the blog
            $this->blog->update(['is_active' => true]);

            // Find related plan item and mark as published
            $planItem = \App\Models\ContentPlanItem::where('related_content_id', $this->blog->id)
                ->where('related_content_type', Blog::class)
                ->first();

            if ($planItem) {
                $planItem->markAsPublished();
            }

            Log::info('Scheduled blog published', [
                'blog_id' => $this->blog->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to publish scheduled content', [
                'blog_id' => $this->blog->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
