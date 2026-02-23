<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EventController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     * Table is rendered by Livewire admin.table component.
     */
    public function index(): View
    {
        return view('admin.event.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return view('admin.event.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

        // Handle image uploads
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($validated);

        return redirect()->route('admin.event.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): View
    {
        $event->load(['user']);

        return view('admin.event.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): View
    {
        $event->load(['user']);

        $users = User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return view('admin.event.edit', compact('event', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, Event $event)
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

        // Handle image uploads
        if ($request->hasFile('cover_image')) {
            // Delete old cover image if exists
            if ($event->cover_image) {
                Storage::disk('public')->delete($event->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        // Handle image deletion
        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            // Delete old image from storage if exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            // Set image to null in database
            $validated['image'] = null;
        }
        // Handle image upload
        elseif ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.event.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // Delete images if exist
        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('admin.event.index')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * Toggle event active status
     */
    public function toggleActive(Event $event)
    {
        $event->update(['is_active' => ! $event->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $event->is_active,
            'message' => $event->is_active ? 'Event activated successfully!' : 'Event deactivated successfully!',
        ]);
    }
}
