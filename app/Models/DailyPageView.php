<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @mixin IdeHelperDailyPageView
 */
class DailyPageView extends BaseModel
{
    protected $connection = 'sqlite';

    protected $fillable = [
        'date',
        'url',
        'page_title',
        'views',
        'unique_visitors',
        'referrer',
        'user_agent',
        'ip_address',
        'metadata',
    ];

    protected $casts = [
        'date' => 'date',
        'metadata' => 'array',
        'views' => 'integer',
        'unique_visitors' => 'integer',
    ];

    /**
     * Scope for a specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope for most viewed pages
     */
    public function scopeMostViewed($query, $limit = 10)
    {
        return $query->orderBy('views', 'desc')->limit($limit);
    }

    /**
     * Scope for specific URL
     */
    public function scopeForUrl($query, $url)
    {
        return $query->where('url', $url);
    }

    /**
     * Get or create a record for today and URL
     */
    public static function recordView($url, $data = [])
    {
        $today = Carbon::today();

        $record = static::firstOrCreate(
            [
                'date' => $today,
                'url' => $url,
            ],
            array_merge([
                'page_title' => $data['page_title'] ?? null,
                'views' => 1,
                'unique_visitors' => 1,
                'referrer' => $data['referrer'] ?? null,
                'user_agent' => $data['user_agent'] ?? null,
                'ip_address' => $data['ip_address'] ?? null,
                'metadata' => $data['metadata'] ?? null,
            ], $data)
        );

        // If record already exists, increment views
        if (! $record->wasRecentlyCreated) {
            $record->increment('views');

            // Update unique visitors if this is a new IP for this URL today
            if (isset($data['ip_address']) && $data['ip_address'] !== $record->ip_address) {
                $record->increment('unique_visitors');
            }
        }

        return $record;
    }

    /**
     * Get popular pages for a date range
     */
    public static function getPopularPages($startDate = null, $endDate = null, $limit = 10)
    {
        $query = static::query();

        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        } elseif ($startDate) {
            $query->forDate($startDate);
        }

        return $query->selectRaw('url, page_title, SUM(views) as total_views, SUM(unique_visitors) as total_unique_visitors')
            ->groupBy('url', 'page_title')
            ->orderBy('total_views', 'desc')
            ->limit($limit)
            ->get();
    }
}
