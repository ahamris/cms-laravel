<?php

namespace App\Http\Controllers\Admin\Widgets;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\ModuleListWidget;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ModuleListWidgetController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $moduleListWidgets = ModuleListWidget::ordered()->paginate(20);

        return view('admin.content.module-list-widgets.index', compact('moduleListWidgets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.content.module-list-widgets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'modules' => 'required|array|min:1',
            'modules.*.name' => 'required|string|max:255',
            'modules.*.items' => 'required|array|min:1',
            'modules.*.items.*' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Set is_active (toggle sends '1' when checked, '0' when unchecked)
        $validated['is_active'] = $request->input('is_active', '0') === '1';

        ModuleListWidget::create($validated);

        return redirect()->route('admin.content.module-list-widgets.index')
            ->with('success', 'Module List Widget created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ModuleListWidget $moduleListWidget): View
    {
        return view('admin.content.module-list-widgets.show', compact('moduleListWidget'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModuleListWidget $moduleListWidget): View
    {
        return view('admin.content.module-list-widgets.edit', compact('moduleListWidget'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModuleListWidget $moduleListWidget): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'modules' => 'required|array|min:1',
            'modules.*.name' => 'required|string|max:255',
            'modules.*.items' => 'required|array|min:1',
            'modules.*.items.*' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Set is_active (toggle sends '1' when checked, '0' when unchecked)
        $validated['is_active'] = $request->input('is_active', '0') === '1';

        $moduleListWidget->update($validated);

        return redirect()->route('admin.content.module-list-widgets.index')
            ->with('success', 'Module List Widget updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModuleListWidget $moduleListWidget): RedirectResponse
    {
        $moduleListWidget->delete();

        return redirect()->route('admin.content.module-list-widgets.index')
            ->with('success', 'Module List Widget deleted successfully.');
    }

    /**
     * Toggle active status of module list widget.
     */
    public function toggleActive(ModuleListWidget $moduleListWidget)
    {
        $moduleListWidget->update(['is_active' => !$moduleListWidget->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $moduleListWidget->is_active,
            'message' => $moduleListWidget->is_active ? 'Module List Widget activated.' : 'Module List Widget deactivated.'
        ]);
    }
}
