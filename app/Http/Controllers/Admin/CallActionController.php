<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\CallActionRequest;
use App\Models\CallAction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CallActionController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $callActions = CallAction::orderBy('sort_order')
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);

        return view('admin.content.call-action.index', compact('callActions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.content.call-action.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CallActionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['content']);
        CallAction::create($validated);

        return redirect()->route('admin.content.call-action.index')
                        ->with('success', 'Call Action created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CallAction $callAction): View
    {
        return view('admin.content.call-action.show', compact('callAction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CallAction $callAction): View
    {
        return view('admin.content.call-action.edit', compact('callAction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CallActionRequest $request, CallAction $callAction): RedirectResponse
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['content']);
        $callAction->update($validated);

        return redirect()->route('admin.content.call-action.index')
                        ->with('success', 'Call Action updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CallAction $callAction): RedirectResponse
    {
        $callAction->delete();

        return redirect()->route('admin.content.call-action.index')
                        ->with('success', 'Call Action deleted successfully.');
    }

    /**
     * Toggle the active status of a call action
     */
    public function toggleStatus(CallAction $callAction): RedirectResponse
    {
        $callAction->update(['is_active' => !$callAction->is_active]);

        $status = $callAction->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
                        ->with('success', "Call Action {$status} successfully.");
    }
}
