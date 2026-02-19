<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\DailyPageView;
use App\Models\DailyStat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends AdminBaseController
{
    /**
     * Display detailed analytics dashboard
     */
    public function index(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        // Get comprehensive analytics data
        $analytics = Cache::remember(
            "analytics.detailed.{$startDate->format('Y-m-d')}.{$endDate->format('Y-m-d')}",
            300, // 5 minutes cache
            function () use ($startDate, $endDate) {
                return $this->getAnalyticsData($startDate, $endDate);
            }
        );

        return view('admin.analytics.index', compact('analytics', 'startDate', 'endDate'));
    }

    /**
     * Get analytics data for the specified date range
     */
    private function getAnalyticsData(Carbon $startDate, Carbon $endDate)
    {
        // Overview statistics
        $overview = DailyStat::getSummary($startDate, $endDate);

        // Daily page views trend
        $dailyTrend = DailyStat::dateRange($startDate, $endDate)
            ->orderBy('date')
            ->get(['date', 'total_page_views', 'unique_visitors', 'unique_pages'])
            ->map(function ($stat) {
                return [
                    'date' => $stat->date->format('Y-m-d'),
                    'date_formatted' => $stat->date->format('M d'),
                    'page_views' => $stat->total_page_views ?? 0,
                    'unique_visitors' => $stat->unique_visitors ?? 0,
                    'unique_pages' => $stat->unique_pages ?? 0,
                ];
            });

        // Popular pages
        $popularPages = DailyPageView::dateRange($startDate, $endDate)
            ->selectRaw('
                url, 
                page_title,
                SUM(views) as total_views,
                SUM(unique_visitors) as total_unique_visitors,
                COUNT(DISTINCT strftime("%Y-%m-%d", date)) as active_days
            ')
            ->groupBy('url', 'page_title')
            ->orderBy('total_views', 'desc')
            ->limit(20)
            ->get();

        // URL-based daily statistics
        // Use SQLite-compatible date function
        $urlStats = DailyPageView::dateRange($startDate, $endDate)
            ->selectRaw('
                url,
                page_title,
                strftime("%Y-%m-%d", date) as date,
                SUM(views) as daily_views,
                SUM(unique_visitors) as daily_unique_visitors
            ')
            ->groupBy('url', 'page_title', DB::raw('strftime("%Y-%m-%d", date)'))
            ->orderBy('date', 'desc')
            ->orderBy('daily_views', 'desc')
            ->get()
            ->groupBy('url');

        // Browser statistics
        $browserStats = DailyPageView::dateRange($startDate, $endDate)
            ->whereNotNull('metadata')
            ->get()
            ->pluck('metadata')
            ->filter()
            ->groupBy('browser')
            ->map(function ($group, $browser) {
                return [
                    'browser' => $browser ?: 'Unknown',
                    'count' => $group->count(),
                    'percentage' => 0, // Will be calculated below
                ];
            })
            ->sortByDesc('count')
            ->take(10);

        // Calculate browser percentages
        $totalBrowserViews = $browserStats->sum('count');
        if ($totalBrowserViews > 0) {
            $browserStats = $browserStats->map(function ($stat) use ($totalBrowserViews) {
                $stat['percentage'] = round(($stat['count'] / $totalBrowserViews) * 100, 1);
                return $stat;
            });
        }

        // Device statistics
        $deviceStats = DailyPageView::dateRange($startDate, $endDate)
            ->whereNotNull('metadata')
            ->get()
            ->pluck('metadata')
            ->filter()
            ->groupBy(function ($metadata) {
                return $metadata['is_mobile'] ?? false ? 'Mobile' : 'Desktop';
            })
            ->map(function ($group, $device) {
                return [
                    'device' => $device,
                    'count' => $group->count(),
                    'percentage' => 0,
                ];
            });

        // Calculate device percentages
        $totalDeviceViews = $deviceStats->sum('count');
        if ($totalDeviceViews > 0) {
            $deviceStats = $deviceStats->map(function ($stat) use ($totalDeviceViews) {
                $stat['percentage'] = round(($stat['count'] / $totalDeviceViews) * 100, 1);
                return $stat;
            });
        }

        // Top referrers
        $topReferrers = DailyPageView::dateRange($startDate, $endDate)
            ->whereNotNull('referrer')
            ->selectRaw('referrer, COUNT(*) as count')
            ->groupBy('referrer')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($referrer) {
                return [
                    'referrer' => $this->formatReferrer($referrer->referrer),
                    'count' => $referrer->count,
                ];
            });

        // Hourly distribution (for current day or last day in range)
        // Use SQLite-compatible strftime function instead of MySQL HOUR()
        $hourlyStats = DailyPageView::whereDate('date', $endDate)
            ->selectRaw('CAST(strftime("%H", created_at) AS INTEGER) as hour, COUNT(*) as views')
            ->groupBy(DB::raw('CAST(strftime("%H", created_at) AS INTEGER)'))
            ->orderBy('hour')
            ->get()
            ->pluck('views', 'hour');

        // Fill missing hours with 0
        $hourlyDistribution = [];
        for ($i = 0; $i < 24; $i++) {
            $hourlyDistribution[] = [
                'hour' => $i,
                'hour_formatted' => sprintf('%02d:00', $i),
                'views' => $hourlyStats->get($i, 0),
            ];
        }

        return [
            'overview' => $overview,
            'daily_trend' => $dailyTrend,
            'popular_pages' => $popularPages,
            'url_stats' => $urlStats,
            'browser_stats' => $browserStats->values(),
            'device_stats' => $deviceStats->values(),
            'top_referrers' => $topReferrers,
            'hourly_distribution' => $hourlyDistribution,
        ];
    }

    /**
     * Get date range from request or default to last 7 days
     */
    private function getDateRange(Request $request)
    {
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))
            : Carbon::today()->subDays(6);
            
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : Carbon::today();

        return [
            'start' => $startDate,
            'end' => $endDate,
        ];
    }

    /**
     * Format referrer URL for display
     */
    private function formatReferrer($referrer)
    {
        if (empty($referrer)) {
            return 'Direct';
        }

        $parsed = parse_url($referrer);
        $domain = $parsed['host'] ?? $referrer;
        
        // Remove www prefix
        $domain = preg_replace('/^www\./', '', $domain);
        
        return $domain;
    }

    /**
     * Get analytics data for a specific URL
     */
    public function urlDetails(Request $request, $encodedUrl)
    {
        $url = base64_decode($encodedUrl);
        $dateRange = $this->getDateRange($request);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        $urlAnalytics = Cache::remember(
            "analytics.url.{$encodedUrl}.{$startDate->format('Y-m-d')}.{$endDate->format('Y-m-d')}",
            300,
            function () use ($url, $startDate, $endDate) {
                return $this->getUrlAnalyticsData($url, $startDate, $endDate);
            }
        );

        return view('admin.analytics.url-details', compact('urlAnalytics', 'url', 'startDate', 'endDate'));
    }

    /**
     * Get analytics data for a specific URL
     */
    private function getUrlAnalyticsData($url, Carbon $startDate, Carbon $endDate)
    {
        // Daily stats for this URL
        $dailyStats = DailyPageView::forUrl($url)
            ->dateRange($startDate, $endDate)
            ->orderBy('date')
            ->get(['date', 'views', 'unique_visitors', 'page_title'])
            ->map(function ($stat) {
                return [
                    'date' => $stat->date->format('Y-m-d'),
                    'date_formatted' => $stat->date->format('M d'),
                    'views' => $stat->views,
                    'unique_visitors' => $stat->unique_visitors,
                    'page_title' => $stat->page_title,
                ];
            });

        // Summary stats
        $summary = DailyPageView::forUrl($url)
            ->dateRange($startDate, $endDate)
            ->selectRaw('
                SUM(views) as total_views,
                SUM(unique_visitors) as total_unique_visitors,
                AVG(views) as avg_daily_views,
                COUNT(DISTINCT strftime("%Y-%m-%d", date)) as active_days
            ')
            ->first();

        return [
            'daily_stats' => $dailyStats,
            'summary' => $summary,
        ];
    }
}
