<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Page;
use App\Models\Solution;
use App\Models\VacancyModule\Vacancy;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    /**
     * Sitemap as JSON for SPA routing / sitemap.
     */
    public function index()
    {
        $urls = [];

        $urls[] = ['url' => url('/'), 'loc' => '/', 'priority' => '1.0'];
        $urls[] = ['url' => route('about'), 'loc' => '/over-ons', 'priority' => '0.8'];
        $urls[] = ['url' => route('contact'), 'loc' => '/contact', 'priority' => '0.8'];
        $urls[] = ['url' => route('pricing'), 'loc' => '/prijzen', 'priority' => '0.9'];
        $urls[] = ['url' => route('page.index'), 'loc' => '/pagina', 'priority' => '0.9'];

        if (Route::has('blog')) {
            $urls[] = ['url' => route('blog'), 'loc' => '/artikelen', 'priority' => '0.9'];
        }

        foreach (Blog::where('is_active', true)->get(['slug']) as $blog) {
            $urls[] = ['url' => route('blog.show', $blog->slug), 'loc' => '/artikelen/'.$blog->slug, 'priority' => '0.7'];
        }

        if (Route::has('solutions.index')) {
            $urls[] = ['url' => route('solutions.index'), 'loc' => '/oplossing', 'priority' => '0.8'];
        }
        foreach (Solution::where('is_active', true)->get(['anchor']) as $s) {
            $urls[] = ['url' => route('solutions.show', $s->anchor), 'loc' => '/oplossing/'.$s->anchor, 'priority' => '0.7'];
        }

        foreach (Page::where('is_active', true)->get(['slug']) as $page) {
            $urls[] = ['url' => route('page.show', $page->slug), 'loc' => '/pagina/'.$page->slug, 'priority' => '0.6'];
        }

        $urls[] = ['url' => route('career.index'), 'loc' => '/careers', 'priority' => '0.8'];
        foreach (Vacancy::active()->get(['slug']) as $v) {
            $urls[] = ['url' => route('career.detail', $v->slug), 'loc' => '/careers/'.$v->slug, 'priority' => '0.7'];
        }

        return response()->json([
            'data' => $urls,
        ]);
    }
}
