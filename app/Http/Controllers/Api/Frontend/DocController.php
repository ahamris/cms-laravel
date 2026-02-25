<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocPageResource;
use App\Http\Resources\DocVersionResource;
use App\Models\DocPage;
use App\Models\DocSection;
use App\Models\DocVersion;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DocController extends Controller
{
    #[OA\Get(path: '/api/docs', summary: 'List doc versions', description: 'Active doc versions with sections and pages tree.', tags: ['Docs'], responses: [
        new OA\Response(response: 200, description: 'Doc versions collection'),
    ])]
    public function index()
    {
        $versions = DocVersion::active()
            ->ordered()
            ->with(['activeSections' => function ($q) {
                $q->with(['version', 'activePages']);
            }])
            ->get();

        $banner = get_setting('hero_background_docs') ? get_image(get_setting('hero_background_docs')) : null;

        return DocVersionResource::collection($versions)->additional([
            'template' => 'docs-list',
            'banner' => $banner,
        ]);
    }

    #[OA\Get(path: '/api/docs/{version}', summary: 'Doc version by slug', tags: ['Docs'], parameters: [
        new OA\Parameter(name: 'version', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Version with sections and pages'),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function showVersion(string $version)
    {
        $versionModel = DocVersion::where('version', $version)
            ->where('is_active', true)
            ->first();
        if (! $versionModel) {
            return response()->json(['message' => 'Version not found.'], 404);
        }

        $versionModel->load(['activeSections' => function ($q) {
            $q->with(['version', 'activePages']);
        }]);

        return new DocVersionResource($versionModel);
    }

    #[OA\Get(path: '/api/docs/{version}/{section}/{page}', summary: 'Doc page', description: 'Single doc page by version, section, page slugs.', tags: ['Docs'], parameters: [
        new OA\Parameter(name: 'version', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'section', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'page', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Doc page'),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function showPage(string $version, string $section, string $page)
    {
        $versionModel = DocVersion::where('version', $version)->where('is_active', true)->first();
        if (! $versionModel) {
            return response()->json(['message' => 'Version not found.'], 404);
        }

        $sectionModel = DocSection::where('slug', $section)
            ->where('doc_version_id', $versionModel->id)
            ->where('is_active', true)
            ->first();
        if (! $sectionModel) {
            return response()->json(['message' => 'Section not found.'], 404);
        }

        $pageModel = DocPage::where('slug', $page)
            ->where('doc_section_id', $sectionModel->id)
            ->where('is_active', true)
            ->with(['section.version'])
            ->first();
        if (! $pageModel) {
            return response()->json(['message' => 'Page not found.'], 404);
        }

        return new DocPageResource($pageModel);
    }

    #[OA\Get(path: '/api/docs/search', summary: 'Search doc pages', description: 'Query: q (min 2 chars), version (optional).', tags: ['Docs'], parameters: [
        new OA\Parameter(name: 'q', in: 'query', required: true, schema: new OA\Schema(type: 'string', minLength: 2)),
        new OA\Parameter(name: 'version', in: 'query', schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Results with query and count'),
    ])]
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
            'template' => 'docs-search',
            'results' => $results,
            'query' => $query,
            'count' => $results->count(),
        ]);
    }
}
