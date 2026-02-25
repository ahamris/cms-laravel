<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use App\Models\CourseVideo;
use App\Models\Blog;
use App\Models\Changelog;
use App\Models\DocPage;
use App\Models\Page;
use App\Models\Solution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use OpenApi\Attributes as OA;

class SearchController extends Controller
{
    public const SEARCH_TYPES = ['all', 'pages', 'blog', 'solutions', 'docs', 'course', 'changelog'];

    #[OA\Get(path: '/api/search', summary: 'Search', description: 'Full-text search across pages, blog, solutions, docs, course, and changelog. Use type to filter or "all" for combined results.', tags: ['Search'], parameters: [
        new OA\Parameter(name: 'q', in: 'query', required: true, schema: new OA\Schema(type: 'string', minLength: 2), description: 'Search query'),
        new OA\Parameter(name: 'type', in: 'query', schema: new OA\Schema(type: 'string', enum: ['all', 'pages', 'blog', 'solutions', 'docs', 'course', 'changelog'], default: 'all'), description: 'Content type to search'),
        new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 15, minimum: 1, maximum: 50)),
        new OA\Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', default: 1, minimum: 1)),
    ], responses: [
        new OA\Response(response: 200, description: 'Paginated search results', content: new OA\JsonContent(ref: '#/components/schemas/SearchResponse')),
    ])]
    public function index(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('q', ''));
        $type = $request->input('type', 'all');
        $perPage = max(1, min((int) $request->input('per_page', 15), 50));
        $page = max(1, (int) $request->input('page', 1));

        if (strlen($query) < 2) {
            return response()->json([
                'template' => 'search-result',
                'data' => [],
                'meta' => [
                    'query' => $query,
                    'total' => 0,
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => $perPage,
                ],
            ]);
        }

        $like = '%'.$query.'%';
        $types = $type === 'all' ? self::SEARCH_TYPES : [$type];
        $types = array_diff($types, ['all']);
        $results = collect();
        $totals = [];

        if (in_array('pages', $types)) {
            $items = Page::where('is_active', true)
                ->whereAny(['title', 'short_body', 'long_body'], 'like', $like)
                ->orderBy('title')
                ->when($type === 'all', fn ($q) => $q->limit(10))
                ->when($type !== 'all', fn ($q) => $q->offset(($page - 1) * $perPage)->limit($perPage))
                ->get();
            $totals['pages'] = Page::where('is_active', true)->whereAny(['title', 'short_body', 'long_body'], 'like', $like)->count();
            foreach ($items as $item) {
                $results->push($this->formatResult('page', $item->title, \Illuminate\Support\Str::limit(strip_tags($item->short_body ?? $item->long_body ?? ''), 160), route('api.pages.show', $item->slug), ['slug' => $item->slug]));
            }
        }

        if (in_array('blog', $types)) {
            $items = Blog::where('is_active', true)
                ->whereAny(['title', 'short_body', 'long_body'], 'like', $like)
                ->orderBy('title')
                ->when($type === 'all', fn ($q) => $q->limit(10))
                ->when($type !== 'all', fn ($q) => $q->offset(($page - 1) * $perPage)->limit($perPage))
                ->get();
            $totals['blog'] = Blog::where('is_active', true)->whereAny(['title', 'short_body', 'long_body'], 'like', $like)->count();
            foreach ($items as $item) {
                $results->push($this->formatResult('blog', $item->title, \Illuminate\Support\Str::limit(strip_tags($item->short_body ?? $item->long_body ?? ''), 160), route('api.blog.show', $item->slug), ['slug' => $item->slug]));
            }
        }

        if (in_array('solutions', $types)) {
            $items = Solution::where('is_active', true)
                ->whereAny(['title', 'short_body', 'long_body'], 'like', $like)
                ->orderBy('title')
                ->when($type === 'all', fn ($q) => $q->limit(10))
                ->when($type !== 'all', fn ($q) => $q->offset(($page - 1) * $perPage)->limit($perPage))
                ->get();
            $totals['solutions'] = Solution::where('is_active', true)->whereAny(['title', 'short_body', 'long_body'], 'like', $like)->count();
            foreach ($items as $item) {
                $results->push($this->formatResult('solution', $item->title, \Illuminate\Support\Str::limit(strip_tags($item->short_body ?? $item->long_body ?? ''), 160), route('api.solutions.show', $item->anchor), ['anchor' => $item->anchor]));
            }
        }

        if (in_array('docs', $types)) {
            $items = DocPage::where('is_active', true)
                ->whereAny(['title', 'content'], 'like', $like)
                ->whereHas('section', fn ($q) => $q->where('is_active', true))
                ->with(['section'])
                ->orderBy('title')
                ->when($type === 'all', fn ($q) => $q->limit(10))
                ->when($type !== 'all', fn ($q) => $q->offset(($page - 1) * $perPage)->limit($perPage))
                ->get();
            $totals['docs'] = DocPage::where('is_active', true)->whereAny(['title', 'content'], 'like', $like)->whereHas('section', fn ($q) => $q->where('is_active', true))->count();
            foreach ($items as $item) {
                $section = $item->section->slug ?? '';
                $url = Route::has('api.docs.page') ? route('api.docs.page', ['section' => $item->section->slug, 'page' => $item->slug]) : url("/api/docs/{$section}/{$item->slug}");
                $results->push($this->formatResult('doc', $item->title, \Illuminate\Support\Str::limit(strip_tags($item->content ?? ''), 160), $url, ['section' => $section, 'slug' => $item->slug]));
            }
        }

        if (in_array('course', $types)) {
            $videoQuery = CourseVideo::where('is_active', true)->whereAny(['title', 'description'], 'like', $like);
            $categoryQuery = CourseCategory::where('is_active', true)->whereAny(['name', 'description'], 'like', $like);
            $totals['course'] = $videoQuery->count() + $categoryQuery->count();
            $videos = $videoQuery->orderBy('title')
                ->when($type === 'all', fn ($q) => $q->limit(5))
                ->when($type !== 'all', fn ($q) => $q->offset(($page - 1) * $perPage)->limit($perPage))
                ->get();
            $categories = $categoryQuery->orderBy('name')
                ->when($type === 'all', fn ($q) => $q->limit(5))
                ->when($type !== 'all', fn ($q) => $q->offset(($page - 1) * $perPage)->limit($perPage))
                ->get();
            foreach ($videos as $item) {
                $results->push($this->formatResult('course_video', $item->title, \Illuminate\Support\Str::limit(strip_tags($item->description ?? ''), 160), route('api.course.video.show', $item->slug), ['slug' => $item->slug]));
            }
            foreach ($categories as $item) {
                $results->push($this->formatResult('course_category', $item->name, \Illuminate\Support\Str::limit(strip_tags($item->description ?? ''), 160), route('api.course.category.show', $item->slug), ['slug' => $item->slug]));
            }
        }

        if (in_array('changelog', $types)) {
            $items = Changelog::where('is_active', true)
                ->whereAny(['title', 'description', 'content'], 'like', $like)
                ->orderBy('date', 'desc')
                ->when($type === 'all', fn ($q) => $q->limit(10))
                ->when($type !== 'all', fn ($q) => $q->offset(($page - 1) * $perPage)->limit($perPage))
                ->get();
            $totals['changelog'] = Changelog::where('is_active', true)->whereAny(['title', 'description', 'content'], 'like', $like)->count();
            foreach ($items as $item) {
                $results->push($this->formatResult('changelog', $item->title, \Illuminate\Support\Str::limit(strip_tags($item->description ?? $item->content ?? ''), 160), url('/api/changelog/'.$item->slug), ['slug' => $item->slug]));
            }
        }

        if ($type === 'all') {
            $results = $results->shuffle()->take($perPage)->values();
            $total = array_sum($totals);
        } else {
            $total = (int) ($totals[array_key_first($totals)] ?? 0);
        }

        return response()->json([
            'template' => 'search-result',
            'data' => $results->all(),
            'meta' => [
                'query' => $query,
                'type' => $type,
                'total' => $total,
                'current_page' => $page,
                'last_page' => $total > 0 ? (int) ceil($total / $perPage) : 1,
                'per_page' => $perPage,
            ],
        ]);
    }

    private function formatResult(string $type, string $title, string $excerpt, string $url, array $meta = []): array
    {
        return array_filter([
            'type' => $type,
            'title' => $title,
            'excerpt' => $excerpt,
            'url' => $url,
            ...$meta,
        ]);
    }

    #[OA\Get(path: '/api/search/suggestions', summary: 'Search suggestions', description: 'Autocomplete suggestions (solutions, blog). Min 2 characters in q.', tags: ['Search'], parameters: [
        new OA\Parameter(name: 'q', in: 'query', required: true, schema: new OA\Schema(type: 'string', minLength: 2), description: 'Search query'),
    ], responses: [
        new OA\Response(response: 200, description: 'Suggestions and most searched', content: new OA\JsonContent(ref: '#/components/schemas/SearchSuggestionsResponse')),
    ])]
    public function suggestions(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'template' => 'search-suggestions',
                'suggestions' => [],
                'mostSearched' => $this->getMostSearched(),
            ]);
        }

        $solutions = Solution::where('is_active', true)
            ->where('title', 'like', "%{$query}%")
            ->limit(3)
            ->get(['title', 'anchor'])
            ->map(fn ($item) => [
                'title' => $item->title,
                'type' => 'Oplossing',
                'url' => url('/api/solutions/'.$item->anchor),
                'icon' => 'fa-briefcase',
            ]);

        $blogs = Blog::where('is_active', true)
            ->where('title', 'like', "%{$query}%")
            ->limit(3)
            ->get(['title', 'slug'])
            ->map(fn ($item) => [
                'title' => $item->title,
                'type' => 'Artikel',
                'url' => url('/api/blog/'.$item->slug),
                'icon' => 'fa-newspaper',
            ]);

        $suggestions = $solutions->concat($blogs)->take(6)->values();

        return response()->json([
            'template' => 'search-suggestions',
            'suggestions' => $suggestions,
            'mostSearched' => $this->getMostSearched(),
        ]);
    }

    protected function getMostSearched(): array
    {
        return Cache::remember('most_searched_terms', 3600, function () {
            $predefined = [
                ['term' => 'Boekhouden', 'icon' => 'fa-bookmark', 'url' => url('/api/solutions')],
                ['term' => 'BTW-aangifte', 'icon' => 'fa-calculator', 'url' => url('/api/solutions')],
                ['term' => 'Team beheer', 'icon' => 'fa-users', 'url' => url('/api/solutions')],
                ['term' => 'Rapporten', 'icon' => 'fa-chart-line', 'url' => url('/api/solutions')],
                ['term' => 'Facturatie', 'icon' => 'fa-file-invoice', 'url' => url('/api/solutions')],
            ];
            try {
                $fromDb = DB::table('search_queries')
                    ->select('query as term', DB::raw('COUNT(*) as count'))
                    ->where('created_at', '>=', now()->subDays(30))
                    ->groupBy('query')
                    ->orderBy('count', 'desc')
                    ->limit(5)
                    ->get()
                    ->map(fn ($item) => [
                        'term' => $item->term,
                        'icon' => 'fa-search',
                        'url' => url('/api/search/suggestions').'?q='.urlencode($item->term),
                    ])
                    ->toArray();

                return ! empty($fromDb) ? $fromDb : $predefined;
            } catch (\Exception $e) {
                return $predefined;
            }
        });
    }
}
