<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Legal;
use Illuminate\Http\JsonResponse;

class LegalController extends Controller
{
    /**
     * Get a single active legal page by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $legal = Legal::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return response()->json([
            'data' => [
                'id' => $legal->id,
                'title' => $legal->title,
                'slug' => $legal->slug,
                'body' => $legal->body,
                'meta_title' => $legal->meta_title,
                'meta_description' => $legal->meta_description,
                'keywords' => $legal->keywords,
                'image' => $legal->image ? asset($legal->image) : null,
                'url' => route('legal.show', $legal->slug),
                'current_version' => $legal->current_version,
                'versioning_enabled' => $legal->versioning_enabled,
                'created_at' => $legal->created_at?->toIso8601String(),
                'updated_at' => $legal->updated_at?->toIso8601String(),
            ],
        ]);
    }
}
