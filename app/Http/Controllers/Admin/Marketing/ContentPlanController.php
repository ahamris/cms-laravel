<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\ContentPlan;
use App\Models\ContentPlanItem;
use App\Jobs\GenerateBlogContentJob;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ContentPlanController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $contentPlans = ContentPlan::with('intentBrief.user')
            ->latest()
            ->paginate(20);
        
        return view('admin.marketing.content-plans.index', compact('contentPlans'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ContentPlan $contentPlan): View
    {
        $contentPlan->load([
            'intentBrief.user',
            'items' => function($query) {
                $query->orderBy('scheduled_at');
            },
            'blogs',
        ]);

        // Group items by type
        $itemsByType = [
            'pillar' => $contentPlan->items->where('item_type', 'pillar'),
            'supporting' => $contentPlan->items->where('item_type', 'supporting'),
            'social' => $contentPlan->items->where('item_type', 'social'),
            'evergreen' => $contentPlan->items->where('item_type', 'evergreen'),
        ];

        return view('admin.marketing.content-plans.show', compact('contentPlan', 'itemsByType'));
    }

    /**
     * Approve content plan
     */
    public function approve(ContentPlan $contentPlan): JsonResponse
    {
        $contentPlan->approve();
        $contentPlan->update(['status' => 'active']);

        // Start generating content for approved plan
        $this->startContentGeneration($contentPlan);

        return response()->json([
            'success' => true,
            'message' => 'Content plan approved and content generation started.',
        ]);
    }

    /**
     * Generate content for plan items
     */
    public function generate(ContentPlan $contentPlan): JsonResponse
    {
        $this->startContentGeneration($contentPlan);

        return response()->json([
            'success' => true,
            'message' => 'Content generation started.',
        ]);
    }

    /**
     * Start content generation for plan items
     */
    protected function startContentGeneration(ContentPlan $contentPlan): void
    {
        // Get planned items ordered by priority
        $items = $contentPlan->items()
            ->where('status', 'planned')
            ->orderBy('priority', 'desc')
            ->get();

        foreach ($items as $item) {
            if (in_array($item->item_type, ['pillar', 'supporting'])) {
                GenerateBlogContentJob::dispatch($item);
            }
        }
    }

    /**
     * Update autopilot mode
     */
    public function updateAutopilotMode(Request $request, ContentPlan $contentPlan): JsonResponse
    {
        $validated = $request->validate([
            'autopilot_mode' => 'required|in:assisted,guided,full_autopilot',
        ]);

        $contentPlan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Autopilot mode updated.',
        ]);
    }

    /**
     * Update plan item
     */
    public function updateItem(Request $request, ContentPlan $contentPlan, ContentPlanItem $item): JsonResponse
    {
        $validated = $request->validate([
            'priority' => 'sometimes|integer|min:0|max:10',
            'scheduled_at' => 'sometimes|nullable|date',
            'status' => 'sometimes|in:planned,generating,draft,scheduled,published,failed',
        ]);

        $item->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Plan item updated.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContentPlan $contentPlan): RedirectResponse
    {
        $contentPlan->delete();

        return redirect()->route('admin.marketing.content-plans.index')
            ->with('success', 'Content plan deleted successfully.');
    }
}
