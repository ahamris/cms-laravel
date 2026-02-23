<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\ContentPlan;
use App\Models\ContentPlanItem;
use App\Models\Blog;
use App\Services\MarketingIntelligence;
use Illuminate\View\View;

class MarketingDashboardController extends AdminBaseController
{
    /**
     * Display the marketing dashboard.
     */
    public function index(MarketingIntelligence $intelligence): View
    {
        // Growth status
        $activePlans = ContentPlan::active()->count();
        $publishedBlogs = Blog::where('is_active', true)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
        $totalImpressions = \App\Models\ContentPerformance::where('measured_at', '>=', now()->subDays(30))
            ->sum('impressions') ?? 0;

        // Next publications
        $nextPublications = ContentPlanItem::where('status', 'scheduled')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->limit(10)
            ->with('contentPlan.intentBrief')
            ->get();

        // Items awaiting approval
        $awaitingApproval = ContentPlan::pendingApproval()
            ->with('intentBrief.user')
            ->get();

        // Recent performance
        $recentBlogs = Blog::where('is_active', true)
            ->where('created_at', '>=', now()->subDays(7))
            ->get();

        $performanceData = [];
        foreach ($recentBlogs as $blog) {
            $performance = $intelligence->analyzePerformance(Blog::class, $blog->id, 7);
            $performanceData[] = [
                'blog' => $blog,
                'performance' => $performance,
            ];
        }

        // Risks and suggestions
        $risks = [];
        $suggestions = [];

        // Check for plans without approval
        if ($awaitingApproval->count() > 0) {
            $risks[] = [
                'type' => 'approval',
                'message' => "{$awaitingApproval->count()} content plan(s) awaiting approval",
                'action' => route('admin.marketing.content-plans.index'),
            ];
        }

        // Check for low SEO scores
        $lowSeoBlogs = Blog::where('seo_score', '<', 70)
            ->where('is_active', true)
            ->count();
        
        if ($lowSeoBlogs > 0) {
            $suggestions[] = [
                'type' => 'seo',
                'message' => "{$lowSeoBlogs} blog post(s) need SEO improvement",
                'action' => route('admin.blog.index'),
            ];
        }

        // Check for failed items
        $failedItems = ContentPlanItem::where('status', 'failed')->count();
        if ($failedItems > 0) {
            $risks[] = [
                'type' => 'error',
                'message' => "{$failedItems} content item(s) failed to generate",
                'action' => route('admin.marketing.content-plans.index'),
            ];
        }

        return view('admin.marketing.dashboard', compact(
            'activePlans',
            'publishedBlogs',
            'totalImpressions',
            'nextPublications',
            'awaitingApproval',
            'performanceData',
            'risks',
            'suggestions'
        ));
    }
}
