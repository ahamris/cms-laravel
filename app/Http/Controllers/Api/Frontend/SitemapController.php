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
    #[OA\Get(path: '/api/sitemap', summary: 'Sitemap', description: 'Sitemap as JSON: array of { loc, priority }. Paths only (no domain); frontend builds full URL from its own origin.', tags: ['Sitemap'], responses: [
        new OA\Response(response: 200, description: 'Paths with loc and priority'),
    ])]
    public function index()
    {
        $urls = [];
        $entry = fn (string $loc, string $priority) => ['loc' => $loc, 'priority' => $priority];

        $urls[] = $entry('/', '1.0');
        $urls[] = $entry('/over-ons', '0.8');
        $urls[] = $entry('/contact', '0.8');
        $urls[] = $entry('/prijzen', '0.9');
        $urls[] = $entry('/pagina', '0.9');

        $urls[] = $entry('/artikelen', '0.9');
        foreach (Blog::where('is_active', true)->get(['slug']) as $blog) {
            $urls[] = $entry('/artikelen/'.$blog->slug, '0.7');
        }

        $urls[] = $entry('/oplossing', '0.8');
        foreach (Solution::where('is_active', true)->get(['anchor']) as $s) {
            $urls[] = $entry('/oplossing/'.$s->anchor, '0.7');
        }

        foreach (Page::where('is_active', true)->get(['slug']) as $page) {
            $urls[] = $entry('/pagina/'.$page->slug, '0.6');
        }

        $urls[] = $entry('/careers', '0.8');
        foreach (Vacancy::active()->get(['slug']) as $v) {
            $urls[] = $entry('/careers/'.$v->slug, '0.7');
        }

        return response()->json([
            'data' => $urls,
        ]);
    }
}
