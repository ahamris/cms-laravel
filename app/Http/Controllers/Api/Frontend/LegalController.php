<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\LegalResource;
use App\Models\Legal;

class LegalController extends Controller
{
    /**
     * Get a single active legal page by slug.
     */
    public function show(string $slug)
    {
        $legal = Legal::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return new LegalResource($legal);
    }
}
