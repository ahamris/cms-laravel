<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\DocPage;
use App\Models\DocSection;
use App\Models\DocVersion;
use Illuminate\Http\Request;

class DocController extends Controller
{
    use SeoSetTrait;

    /**
     * Display the documentation index page (redirect to default version or show version list).
     */
    public function index()
    {
        $defaultVersion = DocVersion::getDefault();
        
        if ($defaultVersion) {
            return redirect()->route('docs.version', $defaultVersion->version);
        }

        $versions = DocVersion::active()->ordered()->get();

        $this->setSeoTags([
            'google_title' => 'Documentation - ' . get_setting('site_name'),
            'google_description' => 'Browse our documentation to learn how to use our platform.',
            'google_image' => get_setting('site_logo'),
        ]);

        return view('front.docs.index', compact('versions'));
    }

    /**
     * Display a version landing page or first section/page.
     */
    public function showVersion($version)
    {
        $version = DocVersion::where('version', $version)
            ->where('is_active', true)
            ->firstOrFail();

        $version->load(['activeSections.activePages']);

        // Get all versions for version selector
        $versions = DocVersion::active()->ordered()->get();

        // If version has sections, redirect to first section
        if ($version->activeSections->count() > 0) {
            $firstSection = $version->activeSections->first();
            
            // If section has pages, redirect to first page
            if ($firstSection->activePages->count() > 0) {
                $firstPage = $firstSection->activePages->first();
                return redirect()->route('docs.page', [
                    'version' => $version->version,
                    'section' => $firstSection->slug,
                    'page' => $firstPage->slug,
                ]);
            }
            
            // Otherwise redirect to section
            return redirect()->route('docs.section', [
                'version' => $version->version,
                'section' => $firstSection->slug,
            ]);
        }

        $this->setSeoTags([
            'google_title' => $version->name . ' Documentation - ' . get_setting('site_name'),
            'google_description' => 'Documentation for ' . $version->name,
            'google_image' => get_setting('site_logo'),
        ]);

        return view('front.docs.version', compact('version', 'versions'));
    }

    /**
     * Display a section page (redirect to first page in section if exists).
     */
    public function showSection($version, $section)
    {
        $version = DocVersion::where('version', $version)
            ->where('is_active', true)
            ->firstOrFail();

        $section = DocSection::where('slug', $section)
            ->where('doc_version_id', $version->id)
            ->where('is_active', true)
            ->firstOrFail();

        $section->load(['version', 'activePages']);

        // Load navigation structure
        $version->load(['activeSections' => function ($query) {
            $query->with(['activePages' => function ($query) {
                $query->ordered();
            }])->ordered();
        }]);

        // Get all versions for version selector
        $versions = DocVersion::active()->ordered()->get();

        // If section has pages, redirect to first page
        if ($section->activePages->count() > 0) {
            $firstPage = $section->activePages->first();
            return redirect()->route('docs.page', [
                'version' => $version->version,
                'section' => $section->slug,
                'page' => $firstPage->slug,
            ]);
        }

        $this->setSeoTags([
            'google_title' => $section->title . ' - ' . $version->name . ' Documentation - ' . get_setting('site_name'),
            'google_description' => $section->description ?: $section->title . ' documentation section',
            'google_image' => get_setting('site_logo'),
        ]);

        return view('front.docs.section', compact('version', 'section', 'versions'));
    }

    /**
     * Display an individual documentation page.
     */
    public function showPage($version, $section, $page)
    {
        $version = DocVersion::where('version', $version)
            ->where('is_active', true)
            ->firstOrFail();

        $section = DocSection::where('slug', $section)
            ->where('doc_version_id', $version->id)
            ->where('is_active', true)
            ->firstOrFail();

        $page = DocPage::where('slug', $page)
            ->where('doc_section_id', $section->id)
            ->where('is_active', true)
            ->firstOrFail();

        // Load navigation structure
        $version->load(['activeSections' => function ($query) {
            $query->with(['activePages' => function ($query) {
                $query->ordered();
            }])->ordered();
        }]);

        // Get all versions for version selector
        $versions = DocVersion::active()->ordered()->get();

        // Get next and previous pages
        $nextPage = $page->getNextPage();
        $previousPage = $page->getPreviousPage();

        // Set SEO tags
        $this->setSeoTags([
            'google_title' => ($page->meta_title ?: $page->title) . ' - ' . $version->name . ' Documentation - ' . get_setting('site_name'),
            'google_description' => $page->meta_description ?: strip_tags($page->content),
            'google_image' => get_setting('site_logo'),
        ]);

        return view('front.docs.page', compact('version', 'section', 'page', 'versions', 'nextPage', 'previousPage'));
    }

    /**
     * Search documentation pages.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $versionSlug = $request->input('version');

        if (empty($query)) {
            if ($request->expectsJson()) {
                return response()->json(['results' => []]);
            }
            return redirect()->route('docs.index');
        }

        // Build search query
        $searchQuery = DocPage::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            });

        // Filter by version if provided
        if ($versionSlug) {
            $version = DocVersion::where('version', $versionSlug)
                ->where('is_active', true)
                ->first();
            
            if ($version) {
                $searchQuery->whereHas('section', function ($q) use ($version) {
                    $q->where('doc_version_id', $version->id)
                      ->where('is_active', true);
                });
            }
        } else {
            // Only search in active versions
            $searchQuery->whereHas('section.version', function ($q) {
                $q->where('is_active', true);
            });
        }

        $results = $searchQuery->with(['section.version'])
            ->orderBy('title')
            ->limit(20)
            ->get()
            ->map(function ($page) use ($query) {
                return [
                    'id' => $page->id,
                    'title' => $this->highlightText($page->title, $query),
                    'url' => route('docs.page', [
                        'version' => $page->section->version->version,
                        'section' => $page->section->slug,
                        'page' => $page->slug,
                    ]),
                    'section' => $page->section->title,
                    'version' => $page->section->version->name,
                    'excerpt' => $this->getExcerpt($page->content, $query),
                ];
            });

        if ($request->expectsJson()) {
            return response()->json([
                'results' => $results,
                'query' => $query,
                'count' => $results->count(),
            ]);
        }

        // Get all versions for version selector
        $versions = DocVersion::active()->ordered()->get();

        $this->setSeoTags([
            'google_title' => 'Search: ' . $query . ' - Documentation - ' . get_setting('site_name'),
            'google_description' => 'Search results for: ' . $query,
            'google_image' => get_setting('site_logo'),
        ]);

        return view('front.docs.search', compact('results', 'query', 'versions', 'versionSlug'));
    }

    /**
     * Get excerpt from content highlighting search term.
     */
    private function getExcerpt($content, $query, $length = 150)
    {
        $text = strip_tags($content);
        $text = preg_replace('/\s+/', ' ', $text);
        
        $queryLower = strtolower($query);
        $textLower = strtolower($text);
        
        $pos = strpos($textLower, $queryLower);
        
        if ($pos !== false) {
            $start = max(0, $pos - 50);
            $excerpt = substr($text, $start, $length);
            
            if ($start > 0) {
                $excerpt = '...' . $excerpt;
            }
            
            if (strlen($text) > $start + $length) {
                $excerpt .= '...';
            }
            
            // Highlight the search term
            $excerpt = $this->highlightText($excerpt, $query);
            
            return $excerpt;
        }
        
        $excerpt = substr($text, 0, $length) . (strlen($text) > $length ? '...' : '');
        return $this->highlightText($excerpt, $query);
    }

    /**
     * Highlight search terms in text.
     */
    private function highlightText($text, $query)
    {
        if (empty($query) || empty($text)) {
            return $text;
        }
        
        return preg_replace('/(' . preg_quote($query, '/') . ')/i', '<mark>$1</mark>', $text);
    }
}
