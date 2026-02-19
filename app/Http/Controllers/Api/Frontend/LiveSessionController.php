<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\LiveSessionListResource;
use App\Http\Resources\LiveSessionResource;
use App\Models\LiveSession;

class LiveSessionController extends Controller
{
    /**
     * List upcoming and past live sessions.
     */
    public function index()
    {
        $upcoming = LiveSession::active()->upcoming()->ordered()->with(['presenters'])->get();
        $past = LiveSession::completed()->ordered()->paginate(12);

        return response()->json([
            'upcoming' => LiveSessionListResource::collection($upcoming),
            'past' => LiveSessionListResource::collection($past),
            'past_meta' => [
                'current_page' => $past->currentPage(),
                'last_page' => $past->lastPage(),
                'per_page' => $past->perPage(),
                'total' => $past->total(),
            ],
        ]);
    }

    /**
     * Single live session by slug.
     */
    public function show(string $slug)
    {
        $session = LiveSession::where('slug', $slug)->where('is_active', true)->with(['presenters'])->firstOrFail();

        return new LiveSessionResource($session);
    }
}
