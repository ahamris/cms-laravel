<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Module;
use App\Models\Page;
use App\Models\Solution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Display search results
     */
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $results = [];
        $mostSearched = $this->getMostSearched();

        if (strlen($query) >= 2) {
            // Track search query
            $this->trackSearch($query);

            // Search across different models
            $results = [
                'solutions' => $this->searchSolutions($query),
                'modules' => $this->searchModules($query),
                'blogs' => $this->searchBlogs($query),
                'pages' => $this->searchPages($query),
            ];

            // Calculate total results
            $totalResults = collect($results)->sum(fn ($items) => $items->count());
        } else {
            $totalResults = 0;
        }

        return view('front.search.index', compact('query', 'results', 'totalResults', 'mostSearched'));
    }

    /**
     * Search in solutions
     */
    protected function searchSolutions($query)
    {
        return Solution::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('nav_title', 'like', "%{$query}%")
                    ->orWhere('subtitle', 'like', "%{$query}%")
                    ->orWhere('short_body', 'like', "%{$query}%")
                    ->orWhere('long_body', 'like', "%{$query}%");
            })
            ->orderBy('sort_order')
            ->limit(10)
            ->get();
    }

    /**
     * Search in modules
     */
    protected function searchModules($query)
    {
        return Module::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('short_body', 'like', "%{$query}%")
                    ->orWhere('long_body', 'like', "%{$query}%");
            })
            ->orderBy('sort_order')
            ->limit(10)
            ->get();
    }

    /**
     * Search in blog posts
     */
    protected function searchBlogs($query)
    {
        return Blog::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('short_body', 'like', "%{$query}%")
                    ->orWhere('long_body', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Search in pages
     */
    protected function searchPages($query)
    {
        return Page::where('is_active', true)
            ->whereAny(['title', 'short_body', 'long_body'], 'like', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Track search queries for analytics
     */
    protected function trackSearch($query)
    {
        try {
            DB::table('search_queries')->insert([
                'query' => $query,
                'results_count' => 0, // Will be updated later if needed
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Silently fail if table doesn't exist
        }
    }

    /**
     * Get most searched terms
     */
    public function getMostSearched()
    {
        return Cache::remember('most_searched_terms', 3600, function () {
            // Predefined popular search terms
            $predefined = [
                [
                    'term' => 'Boekhouden',
                    'icon' => 'fa-bookmark',
                    'url' => route('solutions.index'),
                ],
                [
                    'term' => 'BTW-aangifte',
                    'icon' => 'fa-calculator',
                    'url' => route('solutions.index'),
                ],
                [
                    'term' => 'Team beheer',
                    'icon' => 'fa-users',
                    'url' => route('solutions.index'),
                ],
                [
                    'term' => 'Rapporten',
                    'icon' => 'fa-chart-line',
                    'url' => route('solutions.index'),
                ],
                [
                    'term' => 'Facturatie',
                    'icon' => 'fa-file-invoice',
                    'url' => route('solutions.index'),
                ],
            ];

            // Try to get from database if table exists
            try {
                $fromDb = DB::table('search_queries')
                    ->select('query as term', DB::raw('COUNT(*) as count'))
                    ->where('created_at', '>=', now()->subDays(30))
                    ->groupBy('query')
                    ->orderBy('count', 'desc')
                    ->limit(5)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'term' => $item->term,
                            'icon' => 'fa-search',
                            'url' => route('search').'?q='.urlencode($item->term),
                        ];
                    })
                    ->toArray();

                return ! empty($fromDb) ? $fromDb : $predefined;
            } catch (\Exception $e) {
                return $predefined;
            }
        });
    }

    /**
     * AJAX search suggestions
     */
    public function suggestions(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'suggestions' => [],
                'mostSearched' => $this->getMostSearched(),
            ]);
        }

        $suggestions = [];

        // Get quick suggestions from different sources
        $solutions = Solution::where('is_active', true)
            ->where('title', 'like', "%{$query}%")
            ->limit(3)
            ->get(['title', 'anchor'])
            ->map(fn ($item) => [
                'title' => $item->title,
                'type' => 'Oplossing',
                'url' => route('solutions.show', $item->anchor),
                'icon' => 'fa-briefcase',
            ]);

        $blogs = Blog::where('is_active', true)
            ->where('title', 'like', "%{$query}%")
            ->limit(3)
            ->get(['title', 'slug'])
            ->map(fn ($item) => [
                'title' => $item->title,
                'type' => 'Artikel',
                'url' => route('blog.show', ['blog' => $item->slug]),
                'icon' => 'fa-newspaper',
            ]);

        $suggestions = $solutions->concat($blogs)->take(6);

        return response()->json([
            'suggestions' => $suggestions,
            'mostSearched' => $this->getMostSearched(),
        ]);
    }
}
