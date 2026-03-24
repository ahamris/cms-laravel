<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:newsletters,email',
        ]);

        $newsletter = Newsletter::create([
            'email' => $validated['email'],
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Newsletter subscription created successfully.',
            'data' => [
                'id' => $newsletter->id,
                'email' => $newsletter->email,
                'is_active' => $newsletter->is_active,
            ],
        ], 201);
    }
}
