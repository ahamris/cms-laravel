<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\AcademyCategory;
use App\Models\AcademyVideo;
use App\Models\LiveSession;
use App\Models\Presenter;
use Illuminate\Http\Request;

class AcademyController extends Controller
{
    use SeoSetTrait;

    /**
     * Display the academy page.
     * When query param "q" has length >= 2, filters sessions and academy videos by search term.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Set SEO tags for academy page
        $this->setSeoTags([
            'google_title' => 'Academy - Live Sessies - ' . get_setting('site_name'),
            'google_description' => 'Bekijk onze live sessies, webinars en trainingen over OpenPublicatie.',
            'google_image' => asset('images/academy-og-image.jpg'),
        ]);

        $searchQuery = trim((string) $request->get('q', ''));
        $searchActive = strlen($searchQuery) >= 2;
        $likeTerm = $searchActive ? '%' . $searchQuery . '%' : null;

        // Get featured session (first active featured session; no search filter)
        $featuredSession = LiveSession::active()
            ->featured()
            ->with(['presenters'])
            ->first();

        // Get upcoming sessions for the agenda (optionally filtered by search)
        $upcomingSessions = LiveSession::active()
            ->upcoming()
            ->with(['presenters'])
            ->when($searchActive, function ($query) use ($likeTerm) {
                $query->where(function ($q) use ($likeTerm) {
                    $q->where('title', 'like', $likeTerm)
                        ->orWhere('description', 'like', $likeTerm)
                        ->orWhere('content', 'like', $likeTerm)
                        ->orWhereHas('presenters', function ($pq) use ($likeTerm) {
                            $pq->where('name', 'like', $likeTerm);
                        });
                });
            })
            ->ordered()
            ->get();

        // Get recently added academy videos for "Recent toegevoegd" section
        $recentVideos = AcademyVideo::active()
            ->with(['category'])
            ->latest()
            ->when($searchActive, function ($query) use ($likeTerm) {
                $query->where(function ($q) use ($likeTerm) {
                    $q->where('title', 'like', $likeTerm)
                        ->orWhere('description', 'like', $likeTerm);
                });
            })
            ->limit(6)
            ->get();

        // Get all active presenters
        $presenters = Presenter::active()->ordered()->get();

        // Academy categories are now handled by AcademySidebarComposer

        if ($searchActive) {
            // We still need categories for search filtering on the index page
            $academyCategories = \App\Models\AcademyCategory::active()
                ->ordered()
                ->withCount(['videos' => fn($q) => $q->active()])
                ->with(['videos' => fn($q) => $q->active()->ordered()])
                ->get();

            $searchNeedle = strtolower($searchQuery);
            $academyCategories = $academyCategories->map(function ($cat) use ($searchNeedle) {
                $cat->setRelation('videos', $cat->videos->filter(function ($video) use ($searchNeedle) {
                    return str_contains(strtolower($video->title ?? ''), $searchNeedle)
                        || str_contains(strtolower($video->description ?? ''), $searchNeedle);
                })->values());
                return $cat;
            })->filter(fn($cat) => $cat->videos->isNotEmpty())->values();
        } else {
            $academyCategories = collect(); // composer handles sidebar
        }

        $searchTotalResults = 0;
        if ($searchActive) {
            $searchTotalResults = $upcomingSessions->count() + $recentVideos->count()
                + $academyCategories->sum(fn($cat) => $cat->videos->count());
        }

        $heroImage = get_setting('hero_background_academy')
            ? get_image(get_setting('hero_background_academy'))
            : null;

        // Calculate Global Stats
        $allVideos = AcademyVideo::active()->get();
        $videoCount = $allVideos->count();
        $totalSeconds = $allVideos->sum('duration_seconds');

        $heroVideoCount = $videoCount . ' ' . ($videoCount === 1 ? 'lesson' : 'lessons');
        $heroDuration = null;

        if ($totalSeconds > 0) {
            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);

            $durationParts = [];
            if ($hours > 0) {
                $durationParts[] = $hours . ' hr';
            }
            if ($minutes > 0 || $hours === 0) {
                $durationParts[] = $minutes . ' min';
            }
            $heroDuration = implode(' ', $durationParts);
        }

        return view('front.academy.index', compact(
            'featuredSession',
            'upcomingSessions',
            'recentVideos',
            'presenters',
            'academyCategories',
            'searchQuery',
            'searchTotalResults',
            'heroImage',
            'heroVideoCount',
            'heroDuration'
        ));
    }

    /**
     * Display a list of all academy categories.
     *
     * @return \Illuminate\View\View
     */
    public function indexCategories()
    {
        $this->setSeoTags([
            'google_title' => 'Academy Categorien - ' . get_setting('site_name'),
            'google_description' => 'Bekijk alle onderwerpen ve categorieën in de OpenPublicatie Academy.',
            'google_image' => asset('images/academy-og-image.jpg'),
        ]);

        $academyCategories = AcademyCategory::active()
            ->ordered()
            ->withCount(['videos' => fn($q) => $q->active()])
            ->withCount(['videos as documentation_count' => fn($q) => $q->active()->plainDocumentation()])
            ->withSum(['videos' => fn($q) => $q->active()], 'duration_seconds')
            ->get();

        $title = 'Alle Categorieën';
        $subtitle = 'Ontdek al onze onderwerpen ve cursussen.';

        // Calculate Global Stats
        $allVideos = AcademyVideo::active()->get();
        $videoCount = $allVideos->count();
        $totalSeconds = $allVideos->sum('duration_seconds');

        $heroVideoCount = $videoCount . ' ' . ($videoCount === 1 ? 'lesson' : 'lessons');
        $heroDuration = null;

        if ($totalSeconds > 0) {
            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
            $durationParts = [];
            if ($hours > 0)
                $durationParts[] = $hours . ' hr';
            if ($minutes > 0 || $hours === 0)
                $durationParts[] = $minutes . ' min';
            $heroDuration = implode(' ', $durationParts);
        }

        $heroImage = get_setting('hero_background_academy')
            ? get_image(get_setting('hero_background_academy'))
            : null;

        return view('front.academy.categories.index', compact('academyCategories', 'title', 'subtitle', 'heroImage', 'heroVideoCount', 'heroDuration'));
    }

    /**
     * Show a single academy category.
     *
     * @return \Illuminate\View\View
     */
    public function showCategory(AcademyCategory $academyCategory)
    {
        if (!$academyCategory->is_active) {
            abort(404);
        }

        $this->setSeoTags([
            'google_title' => $academyCategory->name . ' - ' . get_setting('site_name'),
            'google_description' => \Illuminate\Support\Str::limit($academyCategory->description, 160),
            'google_image' => asset('images/academy-og-image.jpg'),
        ]);

        // Sidebar data
        $academyCategories = AcademyCategory::active()
            ->ordered()
            ->withCount(['videos' => fn($q) => $q->active()])
            ->get();

        $academyCategory->load([
            'chapters' => fn($q) => $q->ordered(),
            'videos' => fn($q) => $q->active()->ordered(),
        ]);

        $title = $academyCategory->name;
        $subtitle = $academyCategory->description;
        $heroImage = $academyCategory->image_path ? asset('storage/' . $academyCategory->image_path) : null;

        // Calculate Stats
        $videoCount = $academyCategory->videos->count();
        $totalSeconds = $academyCategory->videos->sum('duration_seconds');

        $heroVideoCount = $videoCount . ' ' . ($videoCount === 1 ? 'lesson' : 'lessons');
        $heroDuration = null;

        if ($totalSeconds > 0) {
            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);

            $durationParts = [];
            if ($hours > 0) {
                $durationParts[] = $hours . ' hr';
            }
            if ($minutes > 0 || $hours === 0) {
                $durationParts[] = $minutes . ' min';
            }
            $heroDuration = implode(' ', $durationParts);
        }

        return view('front.academy.category', compact('academyCategory', 'academyCategories', 'title', 'subtitle', 'heroImage', 'heroVideoCount', 'heroDuration'));
    }

    /**
     * Show a single academy video.
     *
     * @return \Illuminate\View\View
     */
    public function showVideo(AcademyVideo $academyVideo)
    {
        $academyVideo->load('category');
        if (!$academyVideo->is_active) {
            abort(404);
        }

        $this->setSeoTags([
            'google_title' => $academyVideo->title . ' - ' . get_setting('site_name'),
            'google_description' => \Illuminate\Support\Str::limit(strip_tags($academyVideo->description ?? $academyVideo->title), 160),
            'google_image' => $academyVideo->thumbnail_url ?: asset('images/academy-og-image.jpg'),
        ]);

        // Sidebar data
        $academyCategories = AcademyCategory::active()
            ->ordered()
            ->withCount(['videos' => fn($q) => $q->active()])
            ->get();

        // Get related videos from the same category
        $relatedVideos = AcademyVideo::active()
            ->where('academy_category_id', $academyVideo->academy_category_id)
            ->where('id', '!=', $academyVideo->id)
            ->ordered()
            ->limit(6)
            ->get();

        // Hero Data
        $heroImage = $academyVideo->thumbnail_url ?: ($academyVideo->category->image_path ? asset('storage/' . $academyVideo->category->image_path) : null);

        return view('front.academy.video', compact('academyVideo', 'relatedVideos', 'academyCategories', 'heroImage'));
    }
}
