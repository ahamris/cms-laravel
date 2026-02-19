<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\Solution;

class SolutionController extends Controller
{
    use SeoSetTrait;

    /**
     * Display solutions index page
     */
    public function index()
    {

        // Set SEO tags for solutions index
        $this->setSeoTags([
            'google_title' => 'Oplossingen - ' . get_setting('site_name'),
            'google_description' => 'Ontdek onze oplossingen voor jouw organisatie.',
            'google_image' => asset('images/solutions-og-image.jpg'),
        ]);

        $solutions = Solution::active()->ordered()
            ->with(['modules' => function ($q) {
                $q->where('is_active', true)->ordered()->with(['features' => function ($q) {
                    $q->where('is_active', true)->ordered();
                }]);
            }])
            ->get();

        return view('front.solutions.index', compact('solutions'));
    }
    /**
     * Display the specified resource.
     */
    public function show(Solution $solution)
    {
        // Check if solution is active
        if (! $solution->is_active) {
            abort(404);
        }

        $solution->load([
            'modules' => function ($q) {
                $q->where('is_active', true)->ordered()->with([
                    'features' => function ($q) {
                        $q->where('is_active', true)->ordered();
                    },
                ]);
            },
        ]);

        // Set SEO tags for solution
        $this->setSeoTags([
            'google_title' => $solution->meta_title ?: $solution->title,
            'google_description' => $solution->meta_description ?: $solution->short_body,
            'google_image' => get_image($solution->image, asset('images/solutions-og-image.jpg')),
            'keywords' => $solution->meta_keywords ?: null,
        ]);

        return view('front.solutions.show', compact('solution'));
    }
}
