<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseCategoryListResource;
use App\Http\Resources\CourseCategoryResource;
use App\Http\Resources\CourseVideoListResource;
use App\Http\Resources\CourseVideoResource;
use App\Http\Resources\LiveSessionListResource;
use App\Http\Resources\LiveSessionResource;
use App\Models\CourseCategory;
use App\Models\CourseVideo;
use App\Models\LiveSession;
use App\Models\Presenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CourseController extends Controller
{
    #[OA\Get(path: '/api/course', summary: 'Course index', description: 'Featured session, upcoming, recent videos, presenters, categories. Optional q= for search.', tags: ['Academy'], parameters: [
        new OA\Parameter(name: 'q', in: 'query', schema: new OA\Schema(type: 'string'), description: 'Search term'),
    ], responses: [
        new OA\Response(response: 200, description: 'Course data', content: new OA\JsonContent(ref: '#/components/schemas/CourseIndexResponse')),
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

        $recentVideos = CourseVideo::active()
            ->with(['category'])
            ->latest()
            ->when($searchActive, fn ($q) => $q->where(function ($q) use ($likeTerm) {
                $q->where('title', 'like', $likeTerm)->orWhere('description', 'like', $likeTerm);
            }))
            ->limit(6)
            ->get();

        $presenters = Presenter::active()->ordered()->get();

        $courseCategories = CourseCategory::active()
            ->ordered()
            ->withCount(['videos' => fn ($q) => $q->active()])
            ->with(['videos' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('title')])
            ->get();

        if ($searchActive) {
            $needle = strtolower($searchQuery);
            $courseCategories = $courseCategories->map(function ($cat) use ($needle) {
                $cat->setRelation('videos', $cat->videos->filter(fn ($v) => str_contains(strtolower($v->title ?? ''), $needle) || str_contains(strtolower($v->description ?? ''), $needle))->values());

                return $cat;
            })->filter(fn ($cat) => $cat->videos->isNotEmpty())->values();
        }

        $allVideos = CourseVideo::active()->get();
        $videoCount = $allVideos->count();
        $totalSeconds = $allVideos->sum('duration_seconds');
        $heroDuration = $totalSeconds > 0 ? sprintf('%d hr %d min', (int) floor($totalSeconds / 3600), (int) floor(($totalSeconds % 3600) / 60)) : null;

        $upcomingArr = LiveSessionListResource::collection($upcomingSessions)->toArray($request);
        $recentVideosArr = CourseVideoListResource::collection($recentVideos)->toArray($request);
        $categoriesArr = CourseCategoryListResource::collection($courseCategories)->toArray($request);

        return response()->json([
            'data' => [
                'featured_session' => $featuredSession ? (new LiveSessionResource($featuredSession))->toArray($request) : null,
                'upcoming_sessions' => isset($upcomingArr['data']) ? $upcomingArr['data'] : $upcomingArr,
                'recent_videos' => isset($recentVideosArr['data']) ? $recentVideosArr['data'] : $recentVideosArr,
                'presenters' => $presenters->map(fn ($p) => [
                    'id' => $p->id,
                    'name' => $p->name ?? $p->title,
                    'avatar' => get_image($p->avatar ?? null),
                    'sort_order' => $p->sort_order ?? 0,
                ])->values()->all(),
                'categories' => isset($categoriesArr['data']) ? $categoriesArr['data'] : $categoriesArr,
                'search_query' => $searchQuery,
                'stats' => [
                    'video_count' => $videoCount,
                    'total_duration_seconds' => $totalSeconds,
                    'hero_duration' => $heroDuration,
                ],
            ],
        ]);
    }

    #[OA\Get(path: '/api/course/categories', summary: 'Course categories', tags: ['Academy'], responses: [
        new OA\Response(response: 200, description: 'Categories with video counts', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/CourseCategoryListItem')),
        ])),
    ])]
    public function categories(Request $request): JsonResponse
    {
        $categories = CourseCategory::active()
            ->ordered()
            ->withCount(['videos' => fn ($q) => $q->active()])
            ->withSum(['videos' => fn ($q) => $q->active()], 'duration_seconds')
            ->get();

        $categoriesArr = CourseCategoryListResource::collection($categories)->toArray($request);

        return response()->json([
            'data' => isset($categoriesArr['data']) ? $categoriesArr['data'] : $categoriesArr,
        ]);
    }

    #[OA\Get(path: '/api/course/category/{slug}', summary: 'Course category by slug', tags: ['Academy'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Category with chapters and videos', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', ref: '#/components/schemas/CourseCategory'),
        ])),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function showCategory(Request $request, string $slug): JsonResponse
    {
        $category = CourseCategory::where('slug', $slug)->where('is_active', true)
            ->with(['courses' => fn ($q) => $q->orderBy('sort_order'), 'videos' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('title')])
            ->first();
        if (! $category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        return response()->json([
            'data' => (new CourseCategoryResource($category))->toArray($request),
        ]);
    }

    #[OA\Get(path: '/api/course/video/{slug}', summary: 'Course video by slug', tags: ['Academy'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Video with related videos', content: new OA\JsonContent(ref: '#/components/schemas/CourseVideoResponse')),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function showVideo(Request $request, string $slug): JsonResponse
    {
        $video = CourseVideo::where('slug', $slug)->where('is_active', true)->with('category')->first();
        if (! $video) {
            return response()->json(['message' => 'Video not found.'], 404);
        }

        $relatedVideos = CourseVideo::active()
            ->where('course_category_id', $video->course_category_id)
            ->where('id', '!=', $video->id)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->limit(6)
            ->get();

        $relatedArr = CourseVideoListResource::collection($relatedVideos)->toArray($request);

        return response()->json([
            'data' => (new CourseVideoResource($video))->toArray($request),
            'related_videos' => isset($relatedArr['data']) ? $relatedArr['data'] : $relatedArr,
        ]);
    }
}
