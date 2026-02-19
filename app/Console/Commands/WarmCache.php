<?php

namespace App\Console\Commands;

use App\Models\DailyPageView;
use App\Models\DailyStat;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WarmCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warm {--pages=* : Specific pages to warm}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up application cache for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cache warming...');

        // Warm up configuration cache
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');

        // Warm up analytics cache
        $this->warmAnalyticsCache();

        // Warm up page cache
        $this->warmPageCache();

        $this->info('Cache warming completed successfully!');
    }

    private function warmAnalyticsCache()
    {
        $this->info('Warming analytics cache...');

        try {
            // Warm up dashboard analytics
            Cache::remember('dashboard.monthlyVisitors', 600, function () {
                return DailyStat::dateRange(
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                )->sum('unique_visitors');
            });

            // Warm up daily page view stats
            Cache::remember('dashboard.dailyPageViewStats', 300, function () {
                $today = Carbon::today();
                $yesterday = Carbon::yesterday();

                return [
                    'today' => DailyPageView::forDate($today)->sum('views'),
                    'yesterday' => DailyPageView::forDate($yesterday)->sum('views'),
                ];
            });

            $this->line('✓ Analytics cache warmed');
        } catch (Exception $e) {
            $this->error('Failed to warm analytics cache: ' . $e->getMessage());
        }
    }

    private function warmPageCache()
    {
        $this->info('Warming page cache...');

        $pages = $this->option('pages') ?: [
            '/',
            '/publications',
            '/contacts',
            '/about'
        ];

        $baseUrl = config('app.url');

        foreach ($pages as $page) {
            try {
                $url = $baseUrl . $page;
                $response = Http::timeout(10)->get($url);

                if ($response->successful()) {
                    $this->line("✓ Warmed: {$url}");
                } else {
                    $this->error("✗ Failed: {$url} (Status: {$response->status()})");
                }
            } catch (Exception $e) {
                $this->error("✗ Error warming {$page}: " . $e->getMessage());
            }
        }
    }
}
