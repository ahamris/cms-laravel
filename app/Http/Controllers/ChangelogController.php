<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\Changelog;
use App\Models\DocVersion;
use Illuminate\Http\Request;

class ChangelogController extends Controller
{
    use SeoSetTrait;
    /**
     * Display the changelog index page.
     */
    public function index(Request $request)
    {
        $perPage = 10;

        // For AJAX requests, use offset-based pagination
        if ($request->ajax()) {
            $offset = $request->get('offset', 0);
            
            $changelogs = Changelog::active()
                ->whereNotIn('status', ['api'])
                ->ordered()
                ->offset($offset)
                ->limit($perPage)
                ->get();

            $totalCount = Changelog::active()->whereNotIn('status', ['api'])->count();
            $hasMore = ($offset + $perPage) < $totalCount;
            $nextOffset = $hasMore ? $offset + $perPage : null;

            return response()->json([
                'data' => $changelogs,
                'has_more' => $hasMore,
                'next_offset' => $nextOffset,
                'html' => view('front.changelog.partials.changelog-item', ['changelogs' => $changelogs])->render(),
            ]);
        }

        // For initial page load, use regular pagination
        $changelogs = Changelog::active()
            ->whereNotIn('status', ['api'])
            ->ordered()
            ->paginate($perPage);

        // Set SEO tags for changelog index
        $this->setSeoTags([
            'google_title' => 'Changelog - ' . get_setting('site_name'),
            'google_description' => 'Bekijk alle updates, nieuwe features en verbeteringen van OpenPublicatie.',
            'google_image' => asset('images/changelog-og-image.jpg'),
        ]);

        return view('front.changelog.index', compact('changelogs'));
    }


    public function indexApi(Request $request)
    {
        $perPage = 10;

        // For AJAX requests, use offset-based pagination
        if ($request->ajax()) {
            $offset = $request->get('offset', 0);
            
            $changelogs = Changelog::active()
                ->byStatus('api')
                ->ordered()
                ->offset($offset)
                ->limit($perPage)
                ->get();

            $totalCount = Changelog::active()->byStatus('api')->count();
            $hasMore = ($offset + $perPage) < $totalCount;
            $nextOffset = $hasMore ? $offset + $perPage : null;

            return response()->json([
                'data' => $changelogs,
                'has_more' => $hasMore,
                'next_offset' => $nextOffset,
                'html' => view('front.changelog.partials.changelog-item', ['changelogs' => $changelogs])->render(),
            ]);
        }

        // For initial page load, use regular pagination
        $changelogs = Changelog::active()
            ->byStatus('api')
            ->ordered()
            ->paginate($perPage);

        // Get all versions for version selector (needed for docs layout)
        $versions = DocVersion::active()->ordered()->get();

        // Set SEO tags for changelog index
        $this->setSeoTags([
            'google_title' => 'Changelog - ' . get_setting('site_name'),
            'google_description' => 'Bekijk alle updates, nieuwe features en verbeteringen van OpenPublicatie.',
            'google_image' => asset('images/changelog-og-image.jpg'),
        ]);

        return view('front.changelog.index', compact('changelogs', 'versions'));
    }

    /**
     * Display the specified changelog entry.
     */
    public function show(Changelog $changelog)
    {
        // Get all versions for version selector (needed for docs layout)
        $versions = DocVersion::active()->ordered()->get();

        // Set SEO tags for changelog entry
        $this->setSeoTags([
            'google_title' => $changelog->title . ' - Changelog - ' . get_setting('site_name'),
            'google_description' => $changelog->description ?: strip_tags($changelog->content),
            'google_image' => asset('images/changelog-og-image.jpg'),
        ]);

        return view('front.changelog.show', compact('changelog', 'versions'));
    }
}
