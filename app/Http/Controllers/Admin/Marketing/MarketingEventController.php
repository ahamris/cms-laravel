<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\MarketingEvent;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class MarketingEventController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $marketingEvents = MarketingEvent::orderBy('start_date', 'desc')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.marketing.marketing-event.index', compact('marketingEvents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.marketing.marketing-event.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:marketing_events,slug',
            'description' => 'nullable|string',
            'type' => 'required|in:webinar,workshop,conference,meetup,online_event',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'meeting_url' => 'nullable|url',
            'max_attendees' => 'nullable|integer|min:1',
            'speakers' => 'nullable|array',
            'speakers.*' => 'string',
            'agenda' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'registration_open' => 'boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('marketing-events', 'public');
        }

        MarketingEvent::create($validated);

        return redirect()->route('admin.marketing.marketing-event.index')
            ->with('success', 'Marketing event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MarketingEvent $marketingEvent): View
    {
        return view('admin.marketing.marketing-event.show', compact('marketingEvent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MarketingEvent $marketingEvent): View
    {
        return view('admin.marketing.marketing-event.edit', compact('marketingEvent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MarketingEvent $marketingEvent): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:marketing_events,slug,' . $marketingEvent->id,
            'description' => 'nullable|string',
            'type' => 'required|in:webinar,workshop,conference,meetup,online_event',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'meeting_url' => 'nullable|url',
            'max_attendees' => 'nullable|integer|min:1',
            'speakers' => 'nullable|array',
            'speakers.*' => 'string',
            'agenda' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'registration_open' => 'boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($marketingEvent->featured_image) {
                Storage::disk('public')->delete($marketingEvent->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('marketing-events', 'public');
        }

        $marketingEvent->update($validated);

        return redirect()->route('admin.marketing.marketing-event.index')
            ->with('success', 'Marketing event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MarketingEvent $marketingEvent): RedirectResponse
    {
        // Delete associated image
        if ($marketingEvent->featured_image) {
            Storage::disk('public')->delete($marketingEvent->featured_image);
        }

        $marketingEvent->delete();

        return redirect()->route('admin.marketing.marketing-event.index')
            ->with('success', 'Marketing event deleted successfully.');
    }

    /**
     * Toggle the featured status of a marketing event.
     */
    public function toggleFeatured(MarketingEvent $marketingEvent): JsonResponse
    {
        $marketingEvent->update(['is_featured' => !$marketingEvent->is_featured]);

        return response()->json([
            'success' => true,
            'is_featured' => $marketingEvent->is_featured,
        ]);
    }
}
