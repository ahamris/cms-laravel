<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Page;
use App\Models\Solution;
use App\Models\StaticPage;
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
        $entry = fn (string $loc, string $priority, ?string $lastmod = null, string $changefreq = 'weekly') => array_filter([
            'loc'        => $loc,
            'priority'   => $priority,
            'lastmod'    => $lastmod,
            'changefreq' => $changefreq,
        ]);

        $urls[] = $entry(api_path('home'), '1.0', null, 'daily');
        $urls[] = $entry(api_path('contact'), '0.8');
        $urls[] = $entry(api_path('pricing'), '0.9');
        $urls[] = $entry(api_path('pages'), '0.9');

        $urls[] = $entry(api_path('blog'), '0.9', null, 'daily');
        foreach (Blog::where('is_active', true)->get(['slug', 'updated_at']) as $blog) {
            $urls[] = $entry(api_path('blog_post', $blog->slug), '0.7', $blog->updated_at?->toDateString());
        }

        $urls[] = $entry(api_path('solutions'), '0.8');
        foreach (Solution::where('is_active', true)->get(['anchor']) as $s) {
            $urls[] = $entry(api_path('solution', $s->anchor), '0.7');
        }

        foreach (Page::where('is_active', true)->get(['slug', 'updated_at']) as $page) {
            $urls[] = $entry(api_path('page', $page->slug), '0.6', $page->updated_at?->toDateString());
        }

        $urls[] = $entry(api_path('partners'), '0.8');
        $urls[] = $entry(api_path('tech_stack'), '0.8');

        foreach (StaticPage::where('is_active', true)->get(['slug']) as $static) {
            $urls[] = $entry(api_path('static_page', $static->slug), '0.6');
        }

        $urls[] = $entry(api_path('vacancies'), '0.8');
        foreach (Vacancy::active()->get(['slug']) as $v) {
            $urls[] = $entry(api_path('vacancy', $v->slug), '0.7');
        }

        return response()->json([
            'data' => $urls,
        ]);
    }

    /**
     * XML sitemap format.
     */
    public function xml()
    {
        $frontendUrl = rtrim(config('app.frontend_url', config('app.url')), '/');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach (Page::where('is_active', true)->get(['slug', 'updated_at']) as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($frontendUrl . '/' . $page->slug) . '</loc>';
            if ($page->updated_at) $xml .= '<lastmod>' . $page->updated_at->toDateString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq><priority>0.6</priority>';
            $xml .= '</url>' . "\n";
        }

        foreach (Blog::where('is_active', true)->get(['slug', 'updated_at']) as $blog) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($frontendUrl . '/blog/' . $blog->slug) . '</loc>';
            if ($blog->updated_at) $xml .= '<lastmod>' . $blog->updated_at->toDateString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq><priority>0.7</priority>';
            $xml .= '</url>' . "\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
