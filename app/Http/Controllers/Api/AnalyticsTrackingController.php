<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyPageView;
use App\Models\Guest;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Analytics tracking endpoints. track, batchTrack, guestActivity, and performance
 * are best-effort: on failure they log and return 200 with status 'error' so
 * client-side code does not break. stats() returns 500 on failure.
 * OpenAPI doc for guest-activity: App\OpenApi\AnalyticsPath (to avoid duplicate scan with Api\Frontend).
 */
class AnalyticsTrackingController extends Controller
{
    /**
     * Track page view via AJAX (best-effort; returns 200 with status 'error' on failure).
     */
    public function track(Request $request)
    {
        try {
            // Validate basic required fields
            $request->validate([
                'url' => 'required|string|max:500',
                'page_title' => 'nullable|string|max:255',
                'referrer' => 'nullable|string|max:500',
                'user_agent' => 'nullable|string|max:1000',
                'metadata' => 'nullable|array',
            ]);

            // Server-side filtering for routes we don't want to track
            if ($this->shouldSkipTracking($request)) {
                return response()->json(['status' => 'skipped'], 200);
            }

            // Prepare page view data
            $pageViewData = [
                'page_title' => $request->input('page_title'),
                'referrer' => $request->input('referrer'),
                'user_agent' => $request->input('user_agent'),
                'ip_address' => $request->getClientIp(),
                'metadata' => $request->input('metadata', []),
            ];

            // Record the page view asynchronously
            DailyPageView::recordView($request->input('url'), $pageViewData);

            return response()->json(['status' => 'tracked'], 200);

        } catch (Exception $e) {
            Log::warning('Analytics track failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['status' => 'error'], 200);
        }
    }

    /**
     * Batch track multiple page views (for SPA applications). Best-effort; returns 200 with status 'error' on failure.
     */
    public function batchTrack(Request $request)
    {
        try {
            $request->validate([
                'views' => 'required|array|max:10', // Limit to 10 views per batch
                'views.*.url' => 'required|string|max:500',
                'views.*.page_title' => 'nullable|string|max:255',
            ]);

            $tracked = 0;
            foreach ($request->input('views') as $view) {
                // Create a mock request for filtering
                $mockRequest = new Request();
                $mockRequest->merge(['url' => $view['url']]);
                $mockRequest->headers->set('referer', $view['referrer'] ?? null);
                
                // Skip if server-side filtering says to skip
                if ($this->shouldSkipTracking($mockRequest)) {
                    continue;
                }

                $pageViewData = [
                    'page_title' => $view['page_title'] ?? null,
                    'referrer' => $view['referrer'] ?? null,
                    'user_agent' => $request->userAgent(),
                    'ip_address' => $request->getClientIp(),
                    'metadata' => $view['metadata'] ?? [],
                ];

                DailyPageView::recordView($view['url'], $pageViewData);
                $tracked++;
            }

            return response()->json(['status' => 'tracked', 'count' => $tracked], 200);

        } catch (Exception $e) {
            Log::warning('Analytics batch-track failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['status' => 'error'], 200);
        }
    }

    /**
     * Track guest activity via AJAX (primary method). Best-effort; returns 200 with status 'error' on failure.
     * Call from SPAs (e.g. React) on load or periodically to keep web stats. Server filters bots and admin referrers.
     */
    public function guestActivity(Request $request)
    {
        try {
            // Server-side filtering for routes we don't want to track
            if ($this->shouldSkipGuestTracking($request)) {
                return response()->json(['status' => 'skipped'], 200);
            }

            // Update guest activity
            Guest::updateOrCreate([
                'ip_address' => $request->getClientIp(),
            ], [
                'ip_address' => $request->getClientIp(),
                'last_activity' => now(),
            ]);

            return response()->json(['status' => 'tracked'], 200);

        } catch (Exception $e) {
            Log::warning('Analytics guest-activity failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['status' => 'error'], 200);
        }
    }

    /**
     * Track performance metrics. Best-effort; returns 200 with status 'error' on failure.
     */
    public function performance(Request $request)
    {
        try {
            $request->validate([
                'url' => 'required|string|max:500',
                'metrics' => 'required|array',
            ]);

            // Store performance data (you can create a separate table for this)

            return response()->json(['status' => 'tracked'], 200);

        } catch (Exception $e) {
            Log::warning('Analytics performance failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['status' => 'error'], 200);
        }
    }

    /**
     * Get basic analytics stats (for client-side display). Returns 500 with status 'error' on failure.
     */
    public function stats(Request $request)
    {
        try {
            // Return basic public stats (cached)
            $stats = Cache::remember('public.analytics.stats', 300, function () {
                $today = Carbon::today();
                $todayViews = DailyPageView::forDate($today)->sum('views');

                return [
                    'today_views' => $todayViews,
                    'status' => 'active'
                ];
            });

            return response()->json($stats, 200);

        } catch (Exception $e) {
            Log::error('Analytics stats failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Determine if we should skip tracking for this request
     */
    private function shouldSkipTracking(Request $request): bool
    {
        $url = $request->input('url');
        $referer = $request->header('referer');
        
        // Skip admin routes
        if (str_starts_with($url, '/admin')) {
            return true;
        }
        
        // Skip API routes
        if (str_starts_with($url, '/api/')) {
            return true;
        }
        
        // Skip asset requests
        $assetPatterns = ['/assets/', '/css/', '/js/', '/images/', '/_debugbar/'];
        foreach ($assetPatterns as $pattern) {
            if (str_starts_with($url, $pattern)) {
                return true;
            }
        }
        
        // Skip if request is coming from admin panel
        if ($referer && str_contains($referer, '/admin')) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine if we should skip guest activity tracking
     */
    private function shouldSkipGuestTracking(Request $request): bool
    {
        $referer = $request->header('referer');
        
        // Skip if request is coming from admin panel
        if ($referer && str_contains($referer, '/admin')) {
            return true;
        }
        
        // Skip if user agent suggests it's a bot
        $userAgent = $request->userAgent();
        if ($userAgent) {
            $botPatterns = [
                'bot', 'crawler', 'spider', 'scraper', 'facebook', 'twitter',
                'google', 'bing', 'yahoo', 'baidu', 'duckduck', 'yandex'
            ];
            
            foreach ($botPatterns as $pattern) {
                if (str_contains(strtolower($userAgent), $pattern)) {
                    return true;
                }
            }
        }
        
        return false;
    }
}
