<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeatureListResource;
use App\Http\Resources\FeatureResource;
use App\Models\Feature;

class FeatureController extends Controller
{
    /**
     * List active features (with anchor for URL).
     */
    public function index()
    {
        $features = Feature::with('modules')->active()->ordered()->get();

        return FeatureListResource::collection($features);
    }

    /**
     * Single feature by anchor (stored slug).
     */
    public function show(string $anchor)
    {
        $feature = Feature::with('modules')
            ->active()
            ->where('anchor', $anchor)
            ->firstOrFail();

        return new FeatureResource($feature);
    }
}
