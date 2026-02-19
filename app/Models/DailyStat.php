<?php

namespace App\Models;

use App\Models\SentEmail;
use Carbon\Carbon;

/**
 * @mixin IdeHelperDailyStat
 */
class DailyStat extends BaseModel
{
    protected $connection = 'sqlite';

    protected $fillable = [
        'date',
        'total_page_views',
        'unique_visitors',
        'unique_pages',
        'new_users',
        'active_users',
        'guest_visitors',
        'new_publications',
        'new_contacts',
        'new_tickets',
        'new_woo_requests',
        'emails_sent',
        'emails_failed',
        'referrer_stats',
        'popular_pages',
        'browser_stats',
        'device_stats',
        'avg_session_duration',
        'bounce_rate',
    ];

    protected $casts = [
        'date' => 'date',
        'referrer_stats' => 'array',
        'popular_pages' => 'array',
        'browser_stats' => 'array',
        'device_stats' => 'array',
        'avg_session_duration' => 'decimal:2',
        'bounce_rate' => 'decimal:2',
    ];

    /**
     * Scope for a specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Get or create today's stats record
     */
    public static function today()
    {
        return static::updateOrCreate(
            ['date' => Carbon::today()],
            [] // Empty array for attributes to update/create with defaults
        );
    }

    /**
     * Get stats for a specific date
     */
    public static function forDate($date)
    {
        $dateString = Carbon::parse($date)->toDateString();

        // Use updateOrCreate to handle the unique constraint properly
        return static::updateOrCreate(
            ['date' => $dateString],
            [] // Empty array for attributes to update/create with defaults
        );
    }

    /**
     * Increment a specific stat for today
     */
    public static function incrementStat($field, $amount = 1)
    {
        $stat = static::today();
        $stat->increment($field, $amount);

        return $stat;
    }

    /**
     * Update JSON stats for today
     */
    public static function updateJsonStat($field, $data)
    {
        $stat = static::today();
        $stat->update([$field => $data]);

        return $stat;
    }

    /**
     * Generate daily stats from various sources
     */
    public static function generateDailyStats($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();
        $dateString = $date->toDateString();

        // Calculate page view stats from DailyPageView
        $pageViewStats = DailyPageView::forDate($dateString)
            ->selectRaw('
                SUM(views) as total_views,
                SUM(unique_visitors) as unique_visitors,
                COUNT(DISTINCT url) as unique_pages
            ')
            ->first();

        // Prepare all statistics data
        $statsData = [
            'total_page_views' => $pageViewStats->total_views ?? 0,
            'unique_visitors' => $pageViewStats->unique_visitors ?? 0,
            'unique_pages' => $pageViewStats->unique_pages ?? 0,
            'new_users' => User::whereDate('created_at', $dateString)->count(),
            'new_publications' => Publication::whereDate('created_at', $dateString)->count(),
            'new_contacts' => Contact::whereDate('created_at', $dateString)->count(),
            'new_tickets' => Ticket::whereDate('created_at', $dateString)->count(),
            'new_woo_requests' => WooRequest::whereDate('created_at', $dateString)->count(),
        ];

        // Use updateOrCreate to handle the constraint properly
        // Convert date string to Carbon date for proper comparison
        $dateCarbon = Carbon::parse($dateString)->startOfDay();

        $stat = static::updateOrCreate(
            ['date' => $dateCarbon],
            $statsData
        );

        // Calculate email statistics
        $emailStats = SentEmail::whereDate('created_at', $dateString)
            ->selectRaw('
                SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END) as sent,
                SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed
            ')
            ->first();

        $stat->update([
            'emails_sent' => $emailStats->sent ?? 0,
            'emails_failed' => $emailStats->failed ?? 0,
        ]);

        // Generate popular pages stats
        $popularPages = DailyPageView::forDate($dateString)
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get(['url', 'page_title', 'views'])
            ->toArray();

        $stat->update([
            'popular_pages' => $popularPages,
        ]);

        return $stat;
    }

    /**
     * Get stats summary for date range
     */
    public static function getSummary($startDate, $endDate)
    {
        return static::dateRange($startDate, $endDate)
            ->selectRaw('
                SUM(total_page_views) as total_page_views,
                SUM(unique_visitors) as total_unique_visitors,
                SUM(new_users) as total_new_users,
                SUM(new_publications) as total_new_publications,
                SUM(new_contacts) as total_new_contacts,
                SUM(new_tickets) as total_new_tickets,
                SUM(new_woo_requests) as total_new_woo_requests,
                SUM(emails_sent) as total_emails_sent,
                SUM(emails_failed) as total_emails_failed,
                AVG(avg_session_duration) as avg_session_duration,
                AVG(bounce_rate) as avg_bounce_rate
            ')
            ->first();
    }
}
