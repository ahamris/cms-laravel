<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeatureListResource;
use App\Http\Resources\FeatureResource;
use App\Models\Feature;
use Illuminate\Support\Str;

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
     * Single feature by anchor (slug of title).
     */
    public function show(string $anchor)
    {
        $feature = Feature::with('modules')
            ->active()
            ->get()
            ->first(fn ($f) => Str::slug($f->title) === $anchor);

        if (! $feature) {
            abort(404);
        }

        $feature->anchor = $anchor;

        return new FeatureResource($feature);
    }
}
