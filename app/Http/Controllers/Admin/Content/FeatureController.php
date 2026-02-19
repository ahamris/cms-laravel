<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\FeatureRequest;
use App\Models\Feature;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeatureController extends AdminBaseController
{
    /**
     * Display a listing of features.
     */
    public function index(): View
    {
        $features = Feature::ordered()->paginate(20);

        return view('admin.content.feature.index', compact('features'));
    }

    /**
     * Show the form for creating a new feature.
     */
    public function create(): View
    {
        $modules = Module::active()->ordered()->get();
        return view('admin.content.feature.create', compact('modules'));
    }

    /**
     * Store a newly created feature.
     */
    public function store(FeatureRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Set is_active (toggle sends '1' when checked, '0' when unchecked)
        $validated['is_active'] = $request->input('is_active', '0') === '1';

        $feature = Feature::create($validated);

        // Sync modules
        if ($request->has('modules')) {
            $feature->modules()->sync($request->input('modules', []));
        }

        // Log activity
        $this->logCreate($feature);

        // Check if user wants to continue editing
        if ($request->input('action') === 'save_and_stay') {
            return redirect()->route('admin.content.feature.edit', $feature)
                ->with('success', 'Feature created successfully! You can continue editing.');
        }

        return redirect()->route('admin.content.feature.index')
            ->with('success', 'Feature created successfully.');
    }

    /**
     * Display the specified feature.
     */
    public function show(Feature $feature): View
    {
        return view('admin.content.feature.show', compact('feature'));
    }

    /**
     * Show the form for editing the specified feature.
     */
    public function edit(Feature $feature): View
    {
        $modules = Module::active()->ordered()->get();
        return view('admin.content.feature.edit', compact('feature', 'modules'));
    }

    /**
     * Update the specified feature.
     */
    public function update(FeatureRequest $request, Feature $feature): RedirectResponse
    {
        $validated = $request->validated();

        // Set is_active (toggle sends '1' when checked, '0' when unchecked)
        $validated['is_active'] = $request->input('is_active', '0') === '1';

        $feature->update($validated);

        // Sync modules
        if ($request->has('modules')) {
            $feature->modules()->sync($request->input('modules', []));
        } else {
            $feature->modules()->sync([]);
        }

        // Log activity
        $this->logUpdate($feature);

        // Check if user wants to continue editing
        if ($request->input('action') === 'save_and_stay') {
            return redirect()->route('admin.content.feature.edit', $feature)
                ->with('success', 'Feature updated successfully! You can continue editing.');
        }

        return redirect()->route('admin.content.feature.index')
            ->with('success', 'Feature updated successfully.');
    }

    /**
     * Remove the specified feature.
     */
    public function destroy(Feature $feature)
    {
        // Log activity before deletion
        $this->logDelete($feature);

        $feature->delete();

        return redirect()->route('admin.content.feature.index')
            ->with('success', 'Feature deleted successfully.');
    }

    /**
     * Update sort order of features.
     */
    public function updateOrder(Request $request)
    {
        $order = $request->input('order', []);
        
        foreach ($order as $index => $id) {
            Feature::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        // Log activity
        $this->logOrderUpdate('Feature', count($order));

        return response()->json(['success' => true]);
    }
}
