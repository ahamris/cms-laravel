<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Changelog;
use App\Models\Module;
use App\Models\Page;
use App\Models\Solution;
use App\Models\Legal;
use Carbon\Carbon;
use App\Models\StaticPage;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    /**
     * Generate and return the sitemap XML
     */
    public function index(): Response
    {
        $sitemap = Cache::remember('sitemap_xml', 60 * 60 * 24, function () {
            return $this->generateSitemap();
        });

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    /**
     * Generate the sitemap XML content
     */
    private function generateSitemap(): string
    {
        $urls = [];

        // Static pages
        $urls[] = $this->createUrl(url('/'), now(), 'daily', '1.0');
        $urls[] = $this->createUrl(route('about'), now(), 'monthly', '0.8');
        $urls[] = $this->createUrl(route('contact'), now(), 'monthly', '0.8');
        $urls[] = $this->createUrl(route('pricing'), now(), 'weekly', '0.9');
        $urls[] = $this->createUrl(route('page.index'), now(), 'weekly', '0.9');

        // Blog index
        if (Route::has('blog')) {
            $urls[] = $this->createUrl(route('blog'), now(), 'daily', '0.9');
        }

        // Blog posts
        $blogs = Blog::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        foreach ($blogs as $blog) {
            $urls[] = $this->createUrl(
                route('blog.show', ['blog' => $blog->slug]),
                $blog->updated_at,
                'weekly',
                '0.7'
            );
        }

        // Solutions
        if (Route::has('solution.index')) {
            $urls[] = $this->createUrl(route('solution.index'), now(), 'weekly', '0.8');
        }

        $solutions = Solution::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        foreach ($solutions as $solution) {
            if (Route::has('solution.show')) {
                $urls[] = $this->createUrl(
                    route('solution.show', $solution->slug),
                    $solution->updated_at,
                    'weekly',
                    '0.7'
                );
            }
        }

        // Pages
        $pages = Page::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        foreach ($pages as $page) {
            if (Route::has('page.show')) {
                $urls[] = $this->createUrl(
                    route('page.show', $page->slug),
                    $page->updated_at,
                    'monthly',
                    '0.6'
                );
            }
        }

        // Legal Pages
        $legalPages = Legal::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        foreach ($legalPages as $legalPage) {
            if (Route::has('legal.show')) {
                $urls[] = $this->createUrl(
                    route('legal.show', $legalPage->slug),
                    $legalPage->updated_at,
                    'monthly',
                    '0.6'
                );
            }
        }

        // Static Pages
        $staticPages = StaticPage::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        foreach ($staticPages as $staticPage) {
            if (Route::has('legal.show')) {
                $urls[] = $this->createUrl(
                    route('static.show', $staticPage->slug),
                    $staticPage->updated_at,
                    'monthly',
                    '0.6'
                );
            }
        }

        // Changelog
        if (Route::has('changelog.index')) {
            $urls[] = $this->createUrl(route('changelog.index'), now(), 'weekly', '0.7');
        }

        $changelogs = Changelog::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        foreach ($changelogs as $changelog) {
            if (Route::has('changelog.show')) {
                $urls[] = $this->createUrl(
                    route('changelog.show', $changelog->slug),
                    $changelog->updated_at,
                    'monthly',
                    '0.5'
                );
            }
        }

        // Modules
        if (Route::has('module.index')) {
            $urls[] = $this->createUrl(route('module.index'), now(), 'weekly', '0.8');
        }

        $modules = Module::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        foreach ($modules as $module) {
            if (Route::has('module.show')) {
                $urls[] = $this->createUrl(
                    route('module.show', $module->slug),
                    $module->updated_at,
                    'weekly',
                    '0.7'
                );
            }
        }

        // Generate XML
        return $this->buildXml($urls);
    }

    /**
     * Create a URL entry for the sitemap
     */
    private function createUrl(string $loc, $lastmod, string $changefreq, string $priority): array
    {
        return [
            'loc' => $loc,
            'lastmod' => $lastmod instanceof Carbon
                ? $lastmod->toW3cString()
                : now()->toW3cString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    /**
     * Build the XML structure
     */
    private function buildXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Clear the sitemap cache
     */
    public function clearCache(): JsonResponse
    {
        Cache::forget('sitemap_xml');

        return response()->json([
            'success' => true,
            'message' => 'Sitemap cache cleared successfully',
        ]);
    }
}
