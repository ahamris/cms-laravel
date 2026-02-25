<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocPageResource;
use App\Http\Resources\DocSectionResource;
use App\Models\DocPage;
use App\Models\DocSection;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DocController extends Controller
{
    #[OA\Get(path: '/api/docs', summary: 'List doc sections', description: 'Active doc sections with pages tree.', tags: ['Docs'], responses: [
        new OA\Response(response: 200, description: 'Doc sections collection'),
    ])]
    public function index()
    {
        $sections = DocSection::active()
            ->ordered()
            ->with('activePages')
            ->get();

        $banner = get_setting('hero_background_docs') ? get_image(get_setting('hero_background_docs')) : null;

        return DocSectionResource::collection($sections)->additional([
            'template' => 'docs-list',
            'banner' => $banner,
        ]);
    }

    #[OA\Get(path: '/api/docs/{section}/{page}', summary: 'Doc page', description: 'Single doc page by section and page slugs.', tags: ['Docs'], parameters: [
        new OA\Parameter(name: 'section', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'page', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Doc page'),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function showPage(string $section, string $page)
    {
        $sectionModel = DocSection::where('slug', $section)
            ->where('is_active', true)
            ->first();
        if (! $sectionModel) {
            return response()->json(['message' => 'Section not found.'], 404);
        }

        $pageModel = DocPage::where('slug', $page)
            ->where('doc_section_id', $sectionModel->id)
            ->where('is_active', true)
            ->with(['section'])
            ->first();
        if (! $pageModel) {
            return response()->json(['message' => 'Page not found.'], 404);
        }

        return new DocPageResource($pageModel);
    }

    #[OA\Get(path: '/api/docs/search', summary: 'Search doc pages', description: 'Search in title and content. Throttled. Query: q (min 2 chars), per_page.', tags: ['Docs'], parameters: [
        new OA\Parameter(name: 'q', in: 'query', required: true, schema: new OA\Schema(type: 'string', minLength: 2)),
        new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 20)),
    ], responses: [
        new OA\Response(response: 200, description: 'Search results'),
        new OA\Response(response: 429, description: 'Too many requests'),
    ])]
    public function search(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        $perPage = max(1, min((int) $request->input('per_page', 20), 50));

        if (strlen($query) < 2) {
            return response()->json([
                'data' => [],
                'template' => 'docs-search',
                'query' => $query,
                'count' => 0,
                'meta' => ['current_page' => 1, 'last_page' => 1, 'per_page' => $perPage, 'total' => 0],
            ]);
        }

        $paginator = DocPage::where('is_active', true)
            ->whereAny(['title', 'content'], 'like', "%{$query}%")
            ->whereHas('section', fn ($q) => $q->where('is_active', true))
            ->with(['section'])
            ->orderBy('title')
            ->paginate($perPage);

        $data = $paginator->getCollection()->map(fn ($page) => [
            'id' => $page->id,
            'title' => $page->title,
            'url' => route('api.docs.page', [
                'section' => $page->section->slug,
                'page' => $page->slug,
            ]),
            'section' => $page->section->title,
            'excerpt' => \Illuminate\Support\Str::limit(strip_tags($page->content), 150),
        ])->all();

        return response()->json([
            'data' => $data,
            'template' => 'docs-search',
            'query' => $query,
            'count' => $paginator->total(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }
}
