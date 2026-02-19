<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\LiveSessionRequest;
use App\Models\LiveSession;
use App\Models\Presenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LiveSessionController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     * Table is rendered by Livewire admin.table component.
     */
    public function index()
    {
        return view('admin.content.live-session.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $presenters = Presenter::active()->ordered()->get();

        return view('admin.content.live-session.create', compact('presenters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LiveSessionRequest $request)
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['content']);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('live-sessions', 'public');
        }

        $liveSession = LiveSession::create($validated);

        // Sync presenters if provided
        if ($request->has('presenters')) {
            $presenters = [];
            foreach ($request->presenters as $index => $presenterId) {
                $presenters[$presenterId] = [
                    'is_primary' => $index === 0, // First presenter is primary
                    'sort_order' => $index + 1,
                ];
            }
            $liveSession->presenters()->sync($presenters);
        }

        return redirect()
            ->route('admin.content.live-session.index')
            ->with('success', 'Live sessie succesvol aangemaakt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LiveSession $liveSession)
    {
        $liveSession->load(['presenters', 'registrations']);

        return view('admin.content.live-session.show', compact('liveSession'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LiveSession $liveSession)
    {
        $presenters = Presenter::active()->ordered()->get();
        $liveSession->load('presenters');

        return view('admin.content.live-session.edit', compact('liveSession', 'presenters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LiveSessionRequest $request, LiveSession $liveSession)
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['content']);

        // Handle thumbnail deletion
        if ($request->has('remove_thumbnail') && $request->input('remove_thumbnail') == '1') {
            if ($liveSession->thumbnail) {
                Storage::disk('public')->delete($liveSession->thumbnail);
            }
            $validated['thumbnail'] = null;
        }
        // Handle thumbnail upload
        elseif ($request->hasFile('thumbnail')) {
            if ($liveSession->thumbnail) {
                Storage::disk('public')->delete($liveSession->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('live-sessions', 'public');
        }

        $liveSession->update($validated);

        // Sync presenters if provided
        if ($request->has('presenters')) {
            $presenters = [];
            foreach ($request->presenters as $index => $presenterId) {
                $presenters[$presenterId] = [
                    'is_primary' => $index === 0, // First presenter is primary
                    'sort_order' => $index + 1,
                ];
            }
            $liveSession->presenters()->sync($presenters);
        } else {
            $liveSession->presenters()->detach();
        }

        return redirect()
            ->route('admin.content.live-session.index')
            ->with('success', 'Live sessie succesvol bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LiveSession $liveSession)
    {
        if ($liveSession->thumbnail) {
            Storage::disk('public')->delete($liveSession->thumbnail);
        }

        $liveSession->delete();

        return redirect()
            ->route('admin.content.live-session.index')
            ->with('success', 'Live sessie succesvol verwijderd.');
    }

    /**
     * Update the sort order of live sessions.
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:live_sessions,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            LiveSession::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Toggle the active status of a live session.
     */
    public function toggleStatus(LiveSession $liveSession)
    {
        $liveSession->update(['is_active' => !$liveSession->is_active]);

        $status = $liveSession->is_active ? 'geactiveerd' : 'gedeactiveerd';

        return redirect()
            ->back()
            ->with('success', "Live sessie succesvol {$status}.");
    }

    /**
     * Update session status based on current time.
     */
    public function updateSessionStatus(LiveSession $liveSession)
    {
        $liveSession->updateStatus();

        return redirect()
            ->back()
            ->with('success', 'Sessie status bijgewerkt.');
    }
}
