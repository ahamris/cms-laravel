<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AcademyCategory;
use App\Models\AcademyVideo;
use App\Models\LiveSession;
use App\Models\Presenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AcademyController extends Controller
{
    #[OA\Get(path: '/api/academy', summary: 'Academy index', description: 'Featured session, upcoming, recent videos, presenters, categories. Optional q= for search.', tags: ['Academy'], parameters: [
        new OA\Parameter(name: 'q', in: 'query', schema: new OA\Schema(type: 'string'), description: 'Search term'),
    ], responses: [
        new OA\Response(response: 200, description: 'Academy data'),
    ])]
    public function index(Request $request): JsonResponse
    {
        $searchQuery = trim((string) $request->get('q', ''));
        $searchActive = strlen($searchQuery) >= 2;
        $likeTerm = $searchActive ? '%'.$searchQuery.'%' : null;

        $featuredSession = LiveSession::active()->featured()->with(['presenters'])->first();
        $upcomingSessions = LiveSession::active()
            ->upcoming()
            ->with(['presenters'])
            ->when($searchActive, fn ($q) => $q->where(function ($q) use ($likeTerm) {
                $q->where('title', 'like', $likeTerm)
                    ->orWhere('description', 'like', $likeTerm)
                    ->orWhere('content', 'like', $likeTerm)
                    ->orWhereHas('presenters', fn ($pq) => $pq->where('name', 'like', $likeTerm));
            }))
            ->ordered()
            ->get();

        $recentVideos = AcademyVideo::active()
            ->with(['category'])
            ->latest()
            ->when($searchActive, fn ($q) => $q->where(function ($q) use ($likeTerm) {
                $q->where('title', 'like', $likeTerm)->orWhere('description', 'like', $likeTerm);
            }))
            ->limit(6)
            ->get();

        $presenters = Presenter::active()->ordered()->get();

        $academyCategories = AcademyCategory::active()
            ->ordered()
            ->withCount(['videos' => fn ($q) => $q->active()])
            ->with(['videos' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('title')])
            ->get();

        if ($searchActive) {
            $needle = strtolower($searchQuery);
            $academyCategories = $academyCategories->map(function ($cat) use ($needle) {
                $cat->setRelation('videos', $cat->videos->filter(fn ($v) => str_contains(strtolower($v->title ?? ''), $needle) || str_contains(strtolower($v->description ?? ''), $needle))->values());

                return $cat;
            })->filter(fn ($cat) => $cat->videos->isNotEmpty())->values();
        }

        $allVideos = AcademyVideo::active()->get();
        $videoCount = $allVideos->count();
        $totalSeconds = $allVideos->sum('duration_seconds');
        $heroDuration = $totalSeconds > 0 ? sprintf('%d hr %d min', (int) floor($totalSeconds / 3600), (int) floor(($totalSeconds % 3600) / 60)) : null;

        return response()->json([
            'data' => [
                'featured_session' => $featuredSession,
                'upcoming_sessions' => $upcomingSessions,
                'recent_videos' => $recentVideos,
                'presenters' => $presenters,
                'categories' => $academyCategories,
                'search_query' => $searchQuery,
                'stats' => [
                    'video_count' => $videoCount,
                    'total_duration_seconds' => $totalSeconds,
                    'hero_duration' => $heroDuration,
                ],
            ],
        ]);
    }

    #[OA\Get(path: '/api/academy/categories', summary: 'Academy categories', tags: ['Academy'], responses: [
        new OA\Response(response: 200, description: 'Categories with video counts'),
    ])]
    public function categories(): JsonResponse
    {
        $categories = AcademyCategory::active()
            ->ordered()
            ->withCount(['videos' => fn ($q) => $q->active()])
            ->withSum(['videos' => fn ($q) => $q->active()], 'duration_seconds')
            ->get();

        return response()->json(['data' => $categories]);
    }

    #[OA\Get(path: '/api/academy/category/{slug}', summary: 'Academy category by slug', tags: ['Academy'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Category with chapters and videos'),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function showCategory(string $slug): JsonResponse
    {
        $category = AcademyCategory::where('slug', $slug)->where('is_active', true)
            ->with(['chapters' => fn ($q) => $q->orderBy('sort_order'), 'videos' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('title')])
            ->firstOrFail();

        return response()->json(['data' => $category]);
    }

    #[OA\Get(path: '/api/academy/video/{slug}', summary: 'Academy video by slug', tags: ['Academy'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Video with related videos'),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function showVideo(string $slug): JsonResponse
    {
        $video = AcademyVideo::where('slug', $slug)->where('is_active', true)->with('category')->firstOrFail();
        $relatedVideos = AcademyVideo::active()
            ->where('academy_category_id', $video->academy_category_id)
            ->where('id', '!=', $video->id)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->limit(6)
            ->get();

        return response()->json([
            'data' => $video,
            'related_videos' => $relatedVideos,
        ]);
    }
}
