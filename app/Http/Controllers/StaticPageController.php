<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    use SeoSetTrait;
    
    /**
     * Display the specified static page.
     */
    public function show(StaticPage $staticPage): View
    {
        // Only show active static pages
        if (!$staticPage->is_active) {
            abort(404);
        }

        // Set SEO tags for static page
        $this->setSeoTags([
            'google_title' => $staticPage->meta_title ?: $staticPage->title,
            'google_description' => $staticPage->meta_description ?: $staticPage->body,
            'google_image' => get_image($staticPage->image, asset('images/static-og-image.jpg')),
        ]);

        return view('front.static.show', compact('staticPage'));
    }
}