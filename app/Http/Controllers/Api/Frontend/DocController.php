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

    #[OA\Get(path: '/api/docs/search', summary: 'Search doc pages', description: 'Query: q (min 2 chars).', tags: ['Docs'], parameters: [
        new OA\Parameter(name: 'q', in: 'query', required: true, schema: new OA\Schema(type: 'string', minLength: 2)),
    ], responses: [
        new OA\Response(response: 200, description: 'Results with query and count'),
    ])]
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json(['results' => [], 'query' => $query, 'count' => 0]);
        }

        $results = DocPage::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })
            ->whereHas('section', fn ($q) => $q->where('is_active', true))
            ->with(['section'])
            ->orderBy('title')
            ->limit(20)
            ->get()
            ->map(fn ($page) => [
                'id' => $page->id,
                'title' => $page->title,
                'url' => route('api.docs.page', [
                    'section' => $page->section->slug,
                    'page' => $page->slug,
                ]),
                'section' => $page->section->title,
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
