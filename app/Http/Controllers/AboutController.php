<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutController extends Controller
{
    use SeoSetTrait;
    /**
     * Display the about us page.
     */
    public function __invoke(): View
    {
        $about = About::where('is_active', true)->first();
        
        if (!$about) {
            abort(404, 'About page not found');
        }

        // Set SEO tags for about page
        $this->setSeoTags([
            'google_title' => $about->meta_title ?: $about->title,
            'google_description' => $about->meta_description ?: $about->short_body,
            'google_image' => get_image($about->image, asset('images/about-og-image.jpg')),
        ]);

        return view('front.about.index', compact('about'));
    }
}
