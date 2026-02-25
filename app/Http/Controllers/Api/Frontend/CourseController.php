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
            ->when($searchActive, fn ($q) => $q->where(fn ($q) => $q->whereAny(['title', 'description', 'content'], 'like', $likeTerm)
                ->orWhereHas('presenters', fn ($pq) => $pq->where('name', 'like', $likeTerm))))
            ->ordered()
            ->get();

        $recentVideos = CourseVideo::active()
            ->with(['category'])
            ->latest()
            ->when($searchActive, fn ($q) => $q->whereAny(['title', 'description'], 'like', $likeTerm))
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

        $banner = get_setting('hero_background_academy') ? get_image(get_setting('hero_background_academy')) : null;

        return response()->json([
            'template' => 'academy',
            'banner' => $banner,
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

    #[OA\Get(path: '/api/course/search', summary: 'Search course content', description: 'Search videos and live sessions. Throttled. Query: q, per_page.', tags: ['Academy'], parameters: [
        new OA\Parameter(name: 'q', in: 'query', required: true, schema: new OA\Schema(type: 'string', minLength: 2)),
        new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 20)),
    ], responses: [
        new OA\Response(response: 200, description: 'Search results (videos + live sessions)'),
        new OA\Response(response: 429, description: 'Too many requests'),
    ])]
    public function search(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('q', ''));
        $perPage = max(1, min((int) $request->input('per_page', 20), 50));

        if (strlen($query) < 2) {
            return response()->json([
                'data' => [],
                'template' => 'course-search',
                'query' => $query,
                'count' => 0,
                'meta' => ['current_page' => 1, 'last_page' => 1, 'per_page' => $perPage, 'total' => 0],
            ]);
        }

        $like = '%'.$query.'%';
        $videos = CourseVideo::active()->with('category')
            ->whereAny(['title', 'description'], 'like', $like)
            ->orderBy('title')
            ->limit((int) ceil($perPage / 2))
            ->get();
        $sessions = LiveSession::active()->with('presenters')
            ->where(fn ($q) => $q->whereAny(['title', 'description', 'content'], 'like', $like)
                ->orWhereHas('presenters', fn ($pq) => $pq->where('name', 'like', $like)))
            ->ordered()
            ->limit((int) ceil($perPage / 2))
            ->get();

        $videoArr = CourseVideoListResource::collection($videos)->toArray($request);
        $sessionArr = LiveSessionListResource::collection($sessions)->toArray($request);
        $data = array_merge(
            array_map(fn ($v) => array_merge($v, ['type' => 'video']), isset($videoArr['data']) ? $videoArr['data'] : $videoArr),
            array_map(fn ($s) => array_merge($s, ['type' => 'live_session']), isset($sessionArr['data']) ? $sessionArr['data'] : $sessionArr)
        );
        $total = count($data);

        return response()->json([
            'data' => $data,
            'template' => 'course-search',
            'query' => $query,
            'count' => $total,
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $perPage,
                'total' => $total,
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
            'template' => 'course-categories-list',
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
            'template' => 'course-category-detail',
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
            'template' => 'course-video-detail',
            'data' => (new CourseVideoResource($video))->toArray($request),
            'related_videos' => isset($relatedArr['data']) ? $relatedArr['data'] : $relatedArr,
        ]);
    }
}
