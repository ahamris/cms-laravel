<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Page;
use App\Models\Solution;
use App\Models\VacancyModule\Vacancy;
use Illuminate\Support\Facades\Route;
use OpenApi\Attributes as OA;

class SitemapController extends Controller
{
    #[OA\Get(path: '/api/sitemap', summary: 'Sitemap', description: 'Sitemap as JSON: array of { url, loc, priority } for SPA routing.', tags: ['Sitemap'], responses: [
        new OA\Response(response: 200, description: 'URLs with loc and priority'),
    ])]
    public function index()
    {
        $urls = [];
        $link = fn (string $path, string $loc, string $priority) => ['url' => url($path), 'loc' => $loc, 'priority' => $priority];
        $routeLink = fn (string $name, string $loc, string $priority, array $params = []) => Route::has($name)
            ? ['url' => route($name, $params), 'loc' => $loc, 'priority' => $priority]
            : $link($loc, $loc, $priority);

        $urls[] = $link('/', '/', '1.0');
        $urls[] = $routeLink('about', '/over-ons', '0.8');
        $urls[] = $routeLink('contact', '/contact', '0.8');
        $urls[] = $routeLink('pricing', '/prijzen', '0.9');
        $urls[] = $routeLink('page.index', '/pagina', '0.9');

        if (Route::has('blog')) {
            $urls[] = ['url' => route('blog'), 'loc' => '/artikelen', 'priority' => '0.9'];
        } else {
            $urls[] = $link('/artikelen', '/artikelen', '0.9');
        }

        foreach (Blog::where('is_active', true)->get(['slug']) as $blog) {
            $urls[] = Route::has('blog.show')
                ? ['url' => route('blog.show', $blog->slug), 'loc' => '/artikelen/'.$blog->slug, 'priority' => '0.7']
                : $link('/artikelen/'.$blog->slug, '/artikelen/'.$blog->slug, '0.7');
        }

        if (Route::has('solutions.index')) {
            $urls[] = ['url' => route('solutions.index'), 'loc' => '/oplossing', 'priority' => '0.8'];
        } else {
            $urls[] = $link('/oplossing', '/oplossing', '0.8');
        }
        foreach (Solution::where('is_active', true)->get(['anchor']) as $s) {
            $urls[] = Route::has('solutions.show')
                ? ['url' => route('solutions.show', $s->anchor), 'loc' => '/oplossing/'.$s->anchor, 'priority' => '0.7']
                : $link('/oplossing/'.$s->anchor, '/oplossing/'.$s->anchor, '0.7');
        }

        foreach (Page::where('is_active', true)->get(['slug']) as $page) {
            $urls[] = Route::has('page.show')
                ? ['url' => route('page.show', $page->slug), 'loc' => '/pagina/'.$page->slug, 'priority' => '0.6']
                : $link('/pagina/'.$page->slug, '/pagina/'.$page->slug, '0.6');
        }

        if (Route::has('career.index')) {
            $urls[] = ['url' => route('career.index'), 'loc' => '/careers', 'priority' => '0.8'];
        } else {
            $urls[] = $link('/careers', '/careers', '0.8');
        }
        foreach (Vacancy::active()->get(['slug']) as $v) {
            $urls[] = Route::has('career.detail')
                ? ['url' => route('career.detail', $v->slug), 'loc' => '/careers/'.$v->slug, 'priority' => '0.7']
                : $link('/careers/'.$v->slug, '/careers/'.$v->slug, '0.7');
        }

        return response()->json([
            'data' => $urls,
        ]);
    }
}
