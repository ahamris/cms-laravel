<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Minimal media endpoint for the headless API.
 * Currently returns an empty list; extend to list storage files or a media table when needed.
 */
class MediaController extends Controller
{
    /**
     * List media assets (read-only). Placeholder for future media library.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->input('per_page', 12), 100));

        return response()->json([
            'data' => [],
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $perPage,
                'total' => 0,
                'from' => null,
                'to' => null,
            ],
        ]);
    }
}
