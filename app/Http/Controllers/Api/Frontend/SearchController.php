<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Solution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class SearchController extends Controller
{
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
