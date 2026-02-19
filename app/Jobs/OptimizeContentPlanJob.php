<?php

namespace App\Jobs;

use App\Models\ContentPlan;
use App\Services\MarketingIntelligence;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class OptimizeContentPlanJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ContentPlan $contentPlan
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(MarketingIntelligence $intelligence): void
    {
        try {
            // Analyze performance of published content from this plan
            $blogs = $this->contentPlan->blogs()
                ->where('is_active', true)
                ->get();

            $insights = [
                'avg_ctr' => 0,
                'avg_engagement' => 0,
                'best_performing_topics' => [],
                'optimal_posting_times' => [],
            ];

            foreach ($blogs as $blog) {
                $performance = $intelligence->analyzePerformance(Blog::class, $blog->id);
                
                $insights['avg_ctr'] += $performance['avg_ctr'];
                $insights['avg_engagement'] += $performance['avg_engagement'];

                if ($performance['avg_ctr'] > 0.05) { // High CTR
                    $insights['best_performing_topics'][] = $blog->title;
                }
            }

            if (count($blogs) > 0) {
                $insights['avg_ctr'] /= count($blogs);
                $insights['avg_engagement'] /= count($blogs);
            }

            // Update strategy data with insights
            $strategyData = $this->contentPlan->strategy_data ?? [];
            $strategyData['performance_insights'] = $insights;
            $strategyData['last_optimized'] = now()->toISOString();

            $this->contentPlan->update([
                'strategy_data' => $strategyData,
            ]);

            Log::info('Content plan optimized', [
                'content_plan_id' => $this->contentPlan->id,
                'insights' => $insights,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to optimize content plan', [
                'content_plan_id' => $this->contentPlan->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
