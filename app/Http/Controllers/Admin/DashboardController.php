<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Variable;
use App\Models\Blog;
use App\Models\CaseStudy;
use App\Models\Contact;
use App\Models\DailyPageView;
use App\Models\DailyStat;
use App\Models\HelpArticle;
use App\Models\Page;
use App\Models\Solution;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends AdminBaseController
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get user statistics
        $userStats = Cache::remember('dashboard.userStats', 600, function () {
            return [
                'total' => User::count(),
                'admins' => User::whereRelation('roles', 'name', Variable::ROLE_ADMIN)->count(),
            ];
        });

        // Get content statistics from existing models (SQL compatible with SQLite and MySQL).
        // Store only plain arrays in cache so drivers that JSON-encode or unserialize never leave
        // __PHP_Incomplete_Class rows that break array_column() on PHP 8.4+.
        $contentStatsRows = Cache::remember('dashboard.contentStatsQuery.v2', 600, function () {
            $driver = DB::getDriverName();
            if ($driver === 'sqlite') {
                return [
                    ['name' => 'blogs', 'count' => Blog::count()],
                    ['name' => 'pages', 'count' => Page::count()],
                    ['name' => 'solutions', 'count' => Solution::count()],
                    ['name' => 'case_studies', 'count' => CaseStudy::count()],
                    ['name' => 'help_articles', 'count' => HelpArticle::count()],
                    ['name' => 'contacts', 'count' => Contact::count()],
                ];
            }

            $rows = DB::select("
                (SELECT 'blogs' as name, COUNT(*) as count FROM blogs) UNION ALL
                (SELECT 'pages' as name, COUNT(*) as count FROM pages) UNION ALL
                (SELECT 'solutions' as name, COUNT(*) as count FROM solutions) UNION ALL
                (SELECT 'case_studies' as name, COUNT(*) as count FROM case_studies) UNION ALL
                (SELECT 'help_articles' as name, COUNT(*) as count FROM help_articles) UNION ALL
                (SELECT 'contacts' as name, COUNT(*) as count FROM contacts)
            ");

            return array_map(
                fn ($row) => ['name' => $row->name, 'count' => (int) $row->count],
                $rows
            );
        });
        $contentStats = array_column($contentStatsRows, 'count', 'name');

        $dailyPageViewStats = Cache::remember('dashboard.dailyPageViewStats', 300, function () {
            try {
                $today = Carbon::today();
                $yesterday = Carbon::yesterday();
                $weekAgo = Carbon::today()->subDays(7);

                // Get today's page views (real-time from DailyPageView)
                $todayPageViews = DailyPageView::forDate($today)
                    ->sum('views') ?? 0;

                // Get yesterday's stats safely
                $yesterdayStats = DailyStat::where('date', $yesterday)->first();
                $yesterdayPageViews = $yesterdayStats->total_page_views ?? 0;

                // Calculate percentage change
                $percentageChange = 0;
                if ($yesterdayPageViews > 0) {
                    $percentageChange = (($todayPageViews - $yesterdayPageViews) / $yesterdayPageViews) * 100;
                } elseif ($todayPageViews > 0) {
                    $percentageChange = 100; // 100% increase from 0
                }

                // Get week summary safely
                $weekSummary = DailyStat::getSummary($weekAgo, $today);

                return [
                    'today' => $todayPageViews,
                    'yesterday' => $yesterdayPageViews,
                    'percentage_change' => round($percentageChange, 1),
                    'week_total' => $weekSummary->total_page_views ?? 0,
                    'week_avg' => $weekSummary->total_page_views ? round($weekSummary->total_page_views / 7, 0) : 0,
                ];
            } catch (Exception $e) {

                // Return default values on error
                return [
                    'today' => 0,
                    'yesterday' => 0,
                    'percentage_change' => 0,
                    'week_total' => 0,
                    'week_avg' => 0,
                ];
            }
        });

        // Use daily page views as the main totalPageviews
        $totalPageviews = $dailyPageViewStats['today'];

        // Calculate monthly visitors from analytics data
        $monthlyVisitors = Cache::remember('dashboard.monthlyVisitors', 600, function () {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            // Get unique visitors for this month from daily stats
            $monthlyVisitorCount = DailyStat::dateRange($startOfMonth, $endOfMonth)
                ->sum('unique_visitors');

            return $monthlyVisitorCount;
        });

        return view('admin.index', compact(
            'userStats',
            'contentStats',
            'dailyPageViewStats',
            'totalPageviews',
            'monthlyVisitors'
        ));
    }

    /**
     * Perform a global search across relevant models.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json(['data' => []]);
        }

        // Search Solutions
        $solutions = Solution::query()
            ->where('title', 'like', "%{$query}%")
            ->select('id', 'title', 'created_at')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'solution_'.$item->id,
                    'title' => $item->title,
                    'url' => route('admin.solution.edit', $item->id),
                    'category' => 'Solution',
                    'icon' => 'fa-lightbulb',
                ];
            });

        // Search Blogs
        $blogs = Blog::query()
            ->where('title', 'like', "%{$query}%")
            ->select('id', 'title', 'created_at')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'blog_'.$item->id,
                    'title' => $item->title,
                    'url' => route('admin.blog.edit', $item->id),
                    'category' => 'Blog',
                    'icon' => 'fa-newspaper',
                ];
            });

        // Search Contacts
        $contacts = Contact::query()
            ->whereAny(['company_name', 'email', 'firstname', 'lastname'], 'like', "%{$query}%")
            ->select('id', 'company_name', 'firstname', 'lastname', 'email')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'contact_'.$item->id,
                    'title' => $item->company_name ?? sprintf('%s %s', $item->firstname, $item->lastname),
                    'url' => route('admin.contact.show', $item->id),
                    'category' => 'Contact',
                    'icon' => 'fa-user-alt',
                ];
            });

        $results = collect($solutions)
            ->merge($blogs)
            ->merge($contacts)
            ->sortByDesc('created_at')
            ->values();

        return response()->json(['data' => $results]);
    }
}
