<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\IntentBrief;
use App\Jobs\GenerateContentPlanJob;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class IntentBriefController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $intentBriefs = IntentBrief::with('user', 'contentPlan')
            ->latest()
            ->paginate(20);
        
        return view('admin.marketing.intent-briefs.index', compact('intentBriefs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.marketing.intent-briefs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_goal' => 'required|string|max:255',
            'audience' => 'required|string',
            'topic' => 'required|string',
            'tone' => 'required|in:expert,neutral,persuasive',
            'approval_level' => 'required|in:manual,auto_approve',
        ]);

        // Check if AI service is configured
        $hasActiveService = \App\Models\AIServiceSetting::getActiveServices()->isNotEmpty();
        if (!$hasActiveService) {
            return redirect()->back()
                ->withErrors(['error' => 'No AI service is configured. Please configure at least one AI service in Settings → AI Settings before creating intent briefs.'])
                ->withInput();
        }

        $intentBrief = IntentBrief::create([
            ...$validated,
            'user_id' => auth()->id(),
            'status' => 'draft',
        ]);

        // Generate content plan in background
        GenerateContentPlanJob::dispatch($intentBrief);

        return redirect()->route('admin.marketing.intent-briefs.show', $intentBrief)
            ->with('success', 'Intent brief created. Content plan is being generated...');
    }

    /**
     * Display the specified resource.
     */
    public function show(IntentBrief $intentBrief): View
    {
        $intentBrief->load('contentPlan.items');
        
        return view('admin.marketing.intent-briefs.show', compact('intentBrief'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IntentBrief $intentBrief): View
    {
        return view('admin.marketing.intent-briefs.edit', compact('intentBrief'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IntentBrief $intentBrief): RedirectResponse
    {
        $validated = $request->validate([
            'business_goal' => 'required|string|max:255',
            'audience' => 'required|string',
            'topic' => 'required|string',
            'tone' => 'required|in:expert,neutral,persuasive',
            'approval_level' => 'required|in:manual,auto_approve',
        ]);

        $intentBrief->update($validated);

        return redirect()->route('admin.marketing.intent-briefs.show', $intentBrief)
            ->with('success', 'Intent brief updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IntentBrief $intentBrief): RedirectResponse
    {
        $intentBrief->delete();

        return redirect()->route('admin.marketing.intent-briefs.index')
            ->with('success', 'Intent brief deleted successfully.');
    }

    /**
     * Generate content plan for intent brief
     */
    public function generatePlan(IntentBrief $intentBrief): JsonResponse
    {
        // Check if AI service is configured
        $hasActiveService = \App\Models\AIServiceSetting::getActiveServices()->isNotEmpty();
        if (!$hasActiveService) {
            return response()->json([
                'success' => false,
                'message' => 'No AI service is configured. Please configure at least one AI service in Settings → AI Settings.',
            ], 400);
        }

        if ($intentBrief->contentPlan) {
            return response()->json([
                'success' => false,
                'message' => 'Content plan already exists for this intent brief.',
            ], 400);
        }

        GenerateContentPlanJob::dispatch($intentBrief);

        return response()->json([
            'success' => true,
            'message' => 'Content plan generation started.',
        ]);
    }
}
