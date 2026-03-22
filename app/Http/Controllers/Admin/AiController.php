<?php

namespace App\Http\Controllers\Admin;

use App\Services\AIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiController extends AdminBaseController
{
    public function __construct(
        private AIService $aiService
    ) {
        parent::__construct();
    }

    public function generatePage(Request $request): JsonResponse
    {
        $request->validate([
            'topic'       => 'required|string|max:500',
            'tone'        => 'nullable|string|max:30',
            'language'    => 'nullable|string|max:5',
            'block_types' => 'nullable|array',
        ]);

        $result = $this->aiService->generatePageBlocks(
            $request->input('topic'),
            $request->input('tone', 'professional'),
            $request->input('language', 'nl'),
            $request->input('block_types', ['hero', 'text', 'cta'])
        );

        if (!$result['success']) {
            return response()->json(['error' => $result['error'] ?? 'Generation failed.'], 422);
        }

        return response()->json(['data' => $result['blocks']]);
    }

    public function generateArticle(Request $request): JsonResponse
    {
        $request->validate([
            'topic'    => 'required|string|max:500',
            'type'     => 'nullable|string|max:20',
            'category' => 'nullable|string|max:100',
            'tone'     => 'nullable|string|max:30',
            'language' => 'nullable|string|max:5',
            'length'   => 'nullable|integer|min:200|max:5000',
        ]);

        $result = $this->aiService->generateArticle(
            $request->input('topic'),
            $request->input('type', 'article'),
            $request->input('category'),
            $request->input('tone', 'informative'),
            $request->input('language', 'nl'),
            $request->input('length', 1000)
        );

        if (!$result['success']) {
            return response()->json(['error' => $result['error'] ?? 'Generation failed.'], 422);
        }

        return response()->json(['data' => $result['article']]);
    }

    public function optimizeSeo(Request $request): JsonResponse
    {
        $request->validate([
            'content' => 'required|string|min:50',
        ]);

        $result = $this->aiService->optimizeSEO($request->input('content'));

        if (!$result['success']) {
            return response()->json(['error' => $result['error'] ?? 'Optimization failed.'], 422);
        }

        return response()->json(['data' => $result['seo']]);
    }

    public function draftReply(Request $request): JsonResponse
    {
        $request->validate([
            'message'  => 'required|string',
            'tone'     => 'nullable|string|max:30',
            'language' => 'nullable|string|max:5',
        ]);

        $draft = $this->aiService->draftReply(
            $request->input('message'),
            $request->input('tone', 'professional'),
            $request->input('language', 'nl')
        );

        if (empty($draft)) {
            return response()->json(['error' => 'Draft generation failed.'], 422);
        }

        return response()->json(['data' => ['draft' => $draft]]);
    }

    public function contentPlan(Request $request): JsonResponse
    {
        $request->validate([
            'topic'    => 'required|string|max:500',
            'items'    => 'nullable|integer|min:1|max:20',
            'language' => 'nullable|string|max:5',
        ]);

        $result = $this->aiService->generateContentPlan(
            $request->input('topic'),
            $request->input('items', 5),
            $request->input('language', 'nl')
        );

        if (!$result['success']) {
            return response()->json(['error' => $result['error'] ?? 'Plan generation failed.'], 422);
        }

        return response()->json(['data' => $result['plan']]);
    }
}
