<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\Feature;
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
            ->get();

        return view('front.features.index', compact('features'));
    }

    /**
     * Display the specified feature (resolved by anchor via route model binding).
     */
    public function show(Feature $feature): View
    {
        if (! $feature->is_active) {
            abort(404);
        }

        $feature->load('modules');

        // Set SEO tags for feature
        $this->setSeoTags([
            'google_title' => $feature->title . ' - Functies - ' . get_setting('site_name'),
            'google_description' => $feature->description ?: 'Meer informatie over ' . $feature->title,
            'google_image' => get_image($feature->icon, asset('images/features-og-image.jpg')),
        ]);

        return view('front.features.show', compact('feature'));
    }
}
