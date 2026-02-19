<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeatureController extends Controller
{
    use SeoSetTrait;
    /**
     * Display a listing of features
     */
    public function index(): View
    {
        // Set SEO tags for features index
        $this->setSeoTags([
            'google_title' => 'Functies - ' . get_setting('site_name'),
            'google_description' => 'Ontdek alle functies en mogelijkheden van OpenPublicatie.',
            'google_image' => asset('images/features-og-image.jpg'),
        ]);

        $features = Feature::with('modules')
            ->active()
            ->ordered()
            ->get()
            ->map(function ($feature) {
                // Add anchor field for URL generation
                $feature->anchor = \Illuminate\Support\Str::slug($feature->title);
                return $feature;
            });

        return view('front.features.index', compact('features'));
    }

    /**
     * Display the specified feature
     */
    public function show(Request $request, $anchor): View
    {
        // Find feature by generated anchor (slug of title)
        $feature = Feature::with('modules')
            ->active()
            ->get()
            ->first(function ($feature) use ($anchor) {
                return \Illuminate\Support\Str::slug($feature->title) === $anchor;
            });

        if (!$feature) {
            abort(404);
        }

        // Add anchor field
        $feature->anchor = $anchor;

        // Set SEO tags for feature
        $this->setSeoTags([
            'google_title' => $feature->title . ' - Functies - ' . get_setting('site_name'),
            'google_description' => $feature->description ?: 'Meer informatie over ' . $feature->title,
            'google_image' => get_image($feature->icon, asset('images/features-og-image.jpg')),
        ]);

        return view('front.features.show', compact('feature'));
    }
}
