<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Page;
use App\Models\Solution;
use App\Models\VacancyModule\Vacancy;
use OpenApi\Attributes as OA;

class SitemapController extends Controller
{
    #[OA\Get(path: '/api/sitemap', summary: 'Sitemap', description: 'Sitemap as JSON: array of { loc, priority }. API paths only (from config); frontend builds full URL from its own origin.', tags: ['Sitemap'], responses: [
        new OA\Response(response: 200, description: 'API paths with loc and priority'),
    ])]
    public function index()
    {
        $urls = [];
        $entry = fn (string $loc, string $priority) => ['loc' => $loc, 'priority' => $priority];

        $urls[] = $entry(api_path('home'), '1.0');
        $urls[] = $entry(api_path('contact'), '0.8');
        $urls[] = $entry(api_path('pricing'), '0.9');
        $urls[] = $entry(api_path('pages'), '0.9');

        $urls[] = $entry(api_path('blog'), '0.9');
        foreach (Blog::where('is_active', true)->get(['slug']) as $blog) {
            $urls[] = $entry(api_path('blog_post', $blog->slug), '0.7');
        }

        $urls[] = $entry(api_path('solutions'), '0.8');
        foreach (Solution::where('is_active', true)->get(['anchor']) as $s) {
            $urls[] = $entry(api_path('solution', $s->anchor), '0.7');
        }

        foreach (Page::where('is_active', true)->get(['slug']) as $page) {
            $urls[] = $entry(api_path('page', $page->slug), '0.6');
        }

        $urls[] = $entry(api_path('vacancies'), '0.8');
        foreach (Vacancy::active()->get(['slug']) as $v) {
            $urls[] = $entry(api_path('vacancy', $v->slug), '0.7');
        }

        return response()->json([
            'data' => $urls,
        ]);
    }
}
