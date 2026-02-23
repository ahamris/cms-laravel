<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\ModuleRequest;
use App\Models\Feature;
use App\Models\Module;
use App\Models\Solution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModuleController extends AdminBaseController
{
    /**
     * Display a listing of modules.
     */
    public function index(): View
    {
        $modules = Module::ordered()->paginate(20);

        return view('admin.content.module.index', compact('modules'));
    }

    /**
     * Show the form for creating a new module.
     */
    public function create(): View
    {
        $features = Feature::active()->ordered()->get();
        $solutions = Solution::active()->ordered()->get();

        return view('admin.content.module.create', compact('features', 'solutions'));
    }

    /**
     * Store a newly created module.
     */
    public function store(ModuleRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

            // Always set list_items from request (empty array when all list items removed)
            $raw = $request->input('list_items', []);
            $listItems = is_array($raw) ? $raw : [];
            $listItems = array_values(array_filter(array_map(function ($item) {
                return is_string($item) ? trim($item) : '';
            }, $listItems), fn ($v) => $v !== ''));
            $validated['list_items'] = $listItems;

            // Handle image upload
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('modules', 'public');
            }

            // Set is_active (toggle sends '1' when checked, '0' when unchecked)
            $validated['is_active'] = $request->input('is_active', '0') === '1';

            $module = Module::create($validated);

            // Sync features
            if ($request->has('features')) {
                $module->features()->sync($request->input('features', []));
            }

            // Sync solutions
            if ($request->has('solutions')) {
                $module->solutions()->sync($request->input('solutions', []));
            }

        // Log activity
        $this->logCreate($module);

        // Check if user wants to continue editing
        if ($request->input('action') === 'save_and_stay') {
            return redirect()->route('admin.content.module.edit', $module)
                ->with('success', 'Module created successfully! You can continue editing.');
        }

        return redirect()->route('admin.content.module.index')
            ->with('success', 'Module created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create module: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified module.
     */
    public function show(Module $module): View
    {
        return view('admin.content.module.show', compact('module'));
    }

    /**
     * Show the form for editing the specified module.
     */
    public function edit(Module $module): View
    {
        $features = Feature::active()->ordered()->get();
        $solutions = Solution::active()->ordered()->get();

        return view('admin.content.module.edit', compact('module', 'features', 'solutions'));
    }

    /**
     * Update the specified module.
     */
    public function update(ModuleRequest $request, Module $module): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

            // Always set list_items from request (empty array when all list items removed)
            $raw = $request->input('list_items', []);
            $listItems = is_array($raw) ? $raw : [];
            $listItems = array_values(array_filter(array_map(function ($item) {
                return is_string($item) ? trim($item) : '';
            }, $listItems), fn ($v) => $v !== ''));
            $validated['list_items'] = $listItems;

            // Handle image deletion
            if ($request->has('remove_image') && $request->input('remove_image') == '1') {
                // Delete old image from storage if exists
                if ($module->image) {
                    \Storage::disk('public')->delete($module->image);
                }
                // Set image to null in database
                $validated['image'] = null;
            }
            // Handle image upload
            elseif ($request->hasFile('image')) {
                // Delete old image if exists
                if ($module->image) {
                    \Storage::disk('public')->delete($module->image);
                }
                $validated['image'] = $request->file('image')->store('modules', 'public');
            }

            // Set is_active (toggle sends '1' when checked, '0' when unchecked)
            $validated['is_active'] = $request->input('is_active', '0') === '1';

            $module->update($validated);

            // Sync features
            if ($request->has('features')) {
                $module->features()->sync($request->input('features', []));
            } else {
                $module->features()->sync([]);
            }

            // Sync solutions
            if ($request->has('solutions')) {
                $module->solutions()->sync($request->input('solutions', []));
            } else {
                $module->solutions()->sync([]);
            }

        // Log activity
        $this->logUpdate($module);

        // Check if user wants to continue editing
        if ($request->input('action') === 'save_and_stay') {
            return redirect()->route('admin.content.module.edit', $module)
                ->with('success', 'Module updated successfully! You can continue editing.');
        }

        return redirect()->route('admin.content.module.index')
            ->with('success', 'Module updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update module: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified module.
     */
    public function destroy(Module $module): RedirectResponse
    {
        // Log activity before deletion
        $this->logDelete($module);
        
        $module->delete();

        return redirect()->route('admin.content.module.index')
            ->with('success', 'Module deleted successfully.');
    }

    /**
     * Update sort order of modules.
     */
    public function updateOrder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $id) {
            Module::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        // Log activity
        $this->logOrderUpdate('Module', count($order));

        return response()->json(['success' => true]);
    }
}
