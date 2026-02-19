<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocPageResource;
use App\Http\Resources\DocVersionResource;
use App\Models\DocPage;
use App\Models\DocSection;
use App\Models\DocVersion;
use Illuminate\Http\Request;

class DocController extends Controller
{
    /**
     * List active doc versions (with sections + pages tree).
     */
    public function index()
    {
        $versions = DocVersion::active()
            ->ordered()
            ->with(['activeSections' => function ($q) {
                $q->with(['version', 'activePages']);
            }])
            ->get();

        return DocVersionResource::collection($versions);
    }

    /**
     * Single version with sections and pages.
     */
    public function showVersion(string $version)
    {
        $versionModel = DocVersion::where('version', $version)
            ->where('is_active', true)
            ->firstOrFail();

        $versionModel->load(['activeSections' => function ($q) {
            $q->with(['version', 'activePages']);
        }]);

        return new DocVersionResource($versionModel);
    }

    /**
     * Single doc page by version, section, page slugs.
     */
    public function showPage(string $version, string $section, string $page)
    {
        $versionModel = DocVersion::where('version', $version)->where('is_active', true)->firstOrFail();
        $sectionModel = DocSection::where('slug', $section)
            ->where('doc_version_id', $versionModel->id)
            ->where('is_active', true)
            ->firstOrFail();

        $pageModel = DocPage::where('slug', $page)
            ->where('doc_section_id', $sectionModel->id)
            ->where('is_active', true)
            ->with(['section.version'])
            ->firstOrFail();

        return new DocPageResource($pageModel);
    }

    /**
     * Search doc pages (JSON).
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $versionSlug = $request->input('version');

        if (strlen($query) < 2) {
            return response()->json(['results' => [], 'query' => $query, 'count' => 0]);
        }

        $searchQuery = DocPage::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            });

        if ($versionSlug) {
            $version = DocVersion::where('version', $versionSlug)->where('is_active', true)->first();
            if ($version) {
                $searchQuery->whereHas('section', function ($q) use ($version) {
                    $q->where('doc_version_id', $version->id)->where('is_active', true);
                });
            }
        } else {
            $searchQuery->whereHas('section.version', fn ($q) => $q->where('is_active', true));
        }

        $results = $searchQuery->with(['section.version'])
            ->orderBy('title')
            ->limit(20)
            ->get()
            ->map(fn ($page) => [
                'id' => $page->id,
                'title' => $page->title,
                'url' => route('docs.page', [
                    'version' => $page->section->version->version,
                    'section' => $page->section->slug,
                    'page' => $page->slug,
                ]),
                'section' => $page->section->title,
                'version' => $page->section->version->name,
                'excerpt' => \Illuminate\Support\Str::limit(strip_tags($page->content), 150),
            ]);

        return response()->json([
            'results' => $results,
            'query' => $query,
            'count' => $results->count(),
        ]);
    }
}
