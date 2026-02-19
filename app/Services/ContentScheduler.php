<?php

namespace App\Services;

use App\Jobs\PublishScheduledContentJob;
use App\Models\Blog;
use App\Models\ContentPlanItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class ContentScheduler
{
    /**
     * Calculate optimal posting time for blog
     */
    public function calculateOptimalBlogTime(Carbon $preferredDate = null): Carbon
    {
        $date = $preferredDate ?? now();

        // Default: Tuesday-Thursday 09:00 (industry best practice)
        $dayOfWeek = $date->dayOfWeek;

        // Adjust to Tuesday-Thursday if needed
        if ($dayOfWeek < 2) { // Monday or Sunday
            $date->addDays(2 - $dayOfWeek); // Move to Tuesday
        } elseif ($dayOfWeek > 4) { // Friday or Saturday
            $date->addDays(7 - $dayOfWeek + 2); // Move to next Tuesday
        }

        // Set to 09:00
        $date->setTime(9, 0);

        // Check historical data if available
        $optimalTime = $this->getOptimalTimeFromHistory('blog', $date);

        return $optimalTime ?? $date;
    }

    /**
     * Calculate optimal posting time for LinkedIn
     */
    public function calculateOptimalLinkedInTime(Carbon $preferredDate = null): Carbon
    {
        $date = $preferredDate ?? now();

        // Default: Tuesday/Wednesday 08:30
        $dayOfWeek = $date->dayOfWeek;

        if ($dayOfWeek < 2) {
            $date->addDays(2 - $dayOfWeek);
        } elseif ($dayOfWeek > 3) {
            $date->addDays(7 - $dayOfWeek + 2);
        }

        $date->setTime(8, 30);

        $optimalTime = $this->getOptimalTimeFromHistory('linkedin', $date);

        return $optimalTime ?? $date;
    }

    /**
     * Calculate optimal posting time for Twitter/X
     */
    public function calculateOptimalTwitterTime(Carbon $preferredDate = null): Carbon
    {
        $date = $preferredDate ?? now();

        // Default: Monday-Friday 12:00 or 17:00
        $dayOfWeek = $date->dayOfWeek;

        if ($dayOfWeek > 4) { // Weekend
            $date->addDays(7 - $dayOfWeek + 1); // Move to Monday
        }

        // Alternate between 12:00 and 17:00
        $hour = ($date->day % 2 === 0) ? 12 : 17;
        $date->setTime($hour, 0);

        $optimalTime = $this->getOptimalTimeFromHistory('twitter', $date);

        return $optimalTime ?? $date;
    }

    /**
     * Get optimal time from historical performance data.
     *
     * Default only: no Search Console or historical traffic integration. Always returns null,
     * so callers use the default day/time (e.g. Tue–Thu 09:00 for blog). Do not use for
     * real scheduling decisions until a data source (e.g. Google Search Console) is integrated.
     */
    protected function getOptimalTimeFromHistory(string $type, Carbon $defaultDate): ?Carbon
    {
        return null;
    }

    /**
     * Schedule a blog post
     */
    public function scheduleBlog(Blog $blog, Carbon $scheduledAt): void
    {
        // Update blog to be inactive until scheduled time
        $blog->update(['is_active' => false]);

        // Schedule job to publish at scheduled time
        PublishScheduledContentJob::dispatch($blog)
            ->delay($scheduledAt);

        Log::info('Blog scheduled', [
            'blog_id' => $blog->id,
            'scheduled_at' => $scheduledAt->toISOString(),
        ]);
    }

    /**
     * Schedule content plan item
     */
    public function schedulePlanItem(ContentPlanItem $item): void
    {
        if (!$item->scheduled_at) {
            // Calculate optimal time based on item type
            $optimalTime = match($item->item_type) {
                'pillar', 'supporting', 'evergreen' => $this->calculateOptimalBlogTime($item->contentPlan->start_date),
                'social' => $this->calculateOptimalLinkedInTime($item->contentPlan->start_date),
                default => now()->addDay(),
            };

            $item->update(['scheduled_at' => $optimalTime]);
        }

        $item->update(['status' => 'scheduled']);
    }

    /**
     * Process all due scheduled items
     */
    public function processDueItems(): int
    {
        $dueItems = ContentPlanItem::due()->get();
        $processed = 0;

        foreach ($dueItems as $item) {
            try {
                // Check autopilot mode
                $plan = $item->contentPlan;

                if ($plan->isFullAutopilot() || ($plan->isGuided() && $plan->status === 'approved')) {
                    // Auto-publish
                    $executionEngine = app(ExecutionEngine::class);
                    $executionEngine->publishContent($item);
                    $processed++;
                } elseif ($plan->isAssisted()) {
                    // Mark as ready for approval
                    $item->update(['status' => 'draft']);
                }

            } catch (Exception $e) {
                Log::error('Failed to process scheduled item', [
                    'item_id' => $item->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $processed;
    }

    /**
     * Get timezone-aware schedule
     */
    public function getTimezoneAwareSchedule(Carbon $date, string $timezone = null): Carbon
    {
        if ($timezone) {
            return $date->setTimezone($timezone);
        }

        // Use app timezone
        return $date->setTimezone(config('app.timezone', 'UTC'));
    }
}

