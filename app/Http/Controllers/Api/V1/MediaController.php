<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function __construct()
    {
        // Public API - no auth required
    }

    public function index(Request $request): JsonResponse
    {
        $query = Media::query();

        if ($request->filled('folder')) {
            $query->inFolder($request->input('folder'));
        }

        if ($request->filled('mime_type')) {
            $query->ofType($request->input('mime_type'));
        }

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('alt_text', 'like', $search)
                  ->orWhere('original_filename', 'like', $search);
            });
        }

        $perPage = max(1, min((int) $request->input('per_page', 24), 100));
        $media = $query->latest()->paginate($perPage);

        return response()->json([
            'data' => $media->through(fn (Media $item) => [
                'id'                => $item->id,
                'filename'          => $item->filename,
                'original_filename' => $item->original_filename,
                'url'               => $item->url,
                'mime_type'         => $item->mime_type,
                'size'              => $item->size,
                'width'             => $item->width,
                'height'            => $item->height,
                'alt_text'          => $item->alt_text,
                'title'             => $item->title,
                'folder'            => $item->folder,
            ])->items(),
            'meta' => [
                'current_page' => $media->currentPage(),
                'per_page'     => $media->perPage(),
                'total'        => $media->total(),
                'last_page'    => $media->lastPage(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $media = Media::findOrFail($id);

        return response()->json([
            'data' => [
                'id'                => $media->id,
                'filename'          => $media->filename,
                'original_filename' => $media->original_filename,
                'url'               => $media->url,
                'mime_type'         => $media->mime_type,
                'size'              => $media->size,
                'width'             => $media->width,
                'height'            => $media->height,
                'alt_text'          => $media->alt_text,
                'title'             => $media->title,
                'folder'            => $media->folder,
                'created_at'        => $media->created_at,
            ],
        ]);
    }
}
