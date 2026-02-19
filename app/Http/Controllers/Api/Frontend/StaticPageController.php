<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\StaticPageResource;
use App\Models\StaticPage;

class StaticPageController extends Controller
{
    /**
     * Get a single active static page by slug.
     */
    public function show(string $slug)
    {
        $staticPage = StaticPage::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return new StaticPageResource($staticPage);
    }
}
