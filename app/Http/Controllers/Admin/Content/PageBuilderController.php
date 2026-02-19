<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\WidgetRequest;
use App\Models\Widget;
use App\Models\MegaMenuItem;
use Illuminate\View\View;

class PageBuilderController extends AdminBaseController
{
    /**
     * Display a listing of available pages.
     */
    public function index(): View
    {
        $availablePages = Widget::getAvailablePages();
        
        // Get statistics for each page
        foreach ($availablePages as $key => &$page) {
            $sectionsCount = Widget::where('section_identifier', $key)->count();
            $page['sections_count'] = $sectionsCount;
            
            $lastWidget = Widget::where('section_identifier', $key)
                ->orderBy('updated_at', 'desc')
                ->first();
            
            $page['last_updated'] = $lastWidget 
                ? $lastWidget->updated_at->diffForHumans() 
                : 'Never';
        }
        
        // Get overall statistics
        $totalPages = count($availablePages);
        $totalSections = Widget::count();
        $activeSections = Widget::where('is_active', true)->count();
        $inactiveSections = Widget::where('is_active', false)->count();

        return view('admin.content.page-builder.pages', compact(
            'availablePages',
            'totalPages',
            'totalSections',
            'activeSections',
            'inactiveSections'
        ));
    }

    /**
     * Display widgets for a specific page type.
     */
    public function manage(string $pageType): View
    {
        $widgets = Widget::with([])
            ->where('section_identifier', $pageType)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.content.homepage-builder.index', compact('widgets', 'pageType'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $pageType = request()->get('page_type', 'homepage');
        $templates = Widget::getAvailableTemplates();
        $sections = Widget::getAvailableSections();
        $availableRoutes = MegaMenuItem::possibleMenuItems();
        $systemContent = MegaMenuItem::getSystemContent();

        return view('admin.content.homepage-builder.create', compact('templates', 'sections', 'pageType', 'availableRoutes', 'systemContent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WidgetRequest $request)
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['content']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('widgets', 'public');
            $validated['image'] = $imagePath;
        }

        // Get page type from validated data
        $pageType = $validated['section_identifier'] ?? 'homepage';

        // Set default sort order if not provided or is null/empty
        if (!isset($validated['sort_order']) || $validated['sort_order'] === null || $validated['sort_order'] === '') {
            $maxOrder = Widget::where('section_identifier', $pageType)
                ->max('sort_order');
            $validated['sort_order'] = ($maxOrder !== null) ? $maxOrder + 1 : 0;
        }

        $widget = Widget::create($validated);

        // Log activity
        $this->logCreate($widget);

        return redirect()->route('admin.content.page-builder.manage', ['pageType' => $pageType])
            ->with('success', 'Widget created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Widget $widget): View
    {
        return view('admin.content.homepage-builder.show', compact('widget'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Widget $widget): View
    {
        $templates = Widget::getAvailableTemplates();
        $sections = Widget::getAvailableSections();
        $availableRoutes = MegaMenuItem::possibleMenuItems();
        $systemContent = MegaMenuItem::getSystemContent();

        return view('admin.content.homepage-builder.edit', compact('widget', 'templates', 'sections', 'availableRoutes', 'systemContent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WidgetRequest $request, Widget $widget)
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['content']);

        // Handle image deletion
        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            // Delete old image from storage if exists
            if ($widget->image) {
                \Storage::disk('public')->delete($widget->image);
            }
            // Set image to null in database
            $validated['image'] = null;
        }
        // Handle image upload
        elseif ($request->hasFile('image')) {
            // Delete old image if exists
            if ($widget->image) {
                \Storage::disk('public')->delete($widget->image);
            }
            $imagePath = $request->file('image')->store('widgets', 'public');
            $validated['image'] = $imagePath;
        }

        $widget->update($validated);

        // Log activity
        $this->logUpdate($widget);

        $pageType = $widget->section_identifier ?? 'homepage';

        return redirect()->route('admin.content.page-builder.manage', ['pageType' => $pageType])
            ->with('success', 'Widget updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Widget $widget)
    {
        // Get page type before deletion
        $pageType = $widget->section_identifier ?? 'homepage';
        
        // Log activity before deletion
        $this->logDelete($widget);

        $widget->delete();

        return redirect()->route('admin.content.page-builder.manage', ['pageType' => $pageType])
            ->with('success', 'Widget deleted successfully!');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Widget $widget)
    {
        $oldStatus = $widget->is_active ? 'active' : 'inactive';
        $widget->update(['is_active' => !$widget->is_active]);
        $newStatus = $widget->is_active ? 'active' : 'inactive';

        // Log status change
        $this->logStatusChange($widget, $oldStatus, $newStatus);

        return redirect()->route('admin.content.homepage-builder.index')
            ->with('success', 'Widget status updated successfully!');
    }

    /**
     * Update sort order for multiple widgets
     */
    public function updateOrder()
    {
        $widgets = request('widgets', []);

        foreach ($widgets as $widgetData) {
            Widget::where('id', $widgetData['id'])
                ->update(['sort_order' => $widgetData['sort_order']]);
        }

        // Log activity
        $this->logOrderUpdate('Widget', count($widgets));

        return response()->json(['success' => true]);
    }

    /**
     * Duplicate a widget
     */
    public function duplicate(Widget $widget)
    {
        $newWidget = $widget->replicate();
        $newWidget->title = $widget->title . ' (Copy)';
        $newWidget->sort_order = Widget::where('section_identifier', $widget->section_identifier)
            ->max('sort_order') + 1;
        $newWidget->save();

        // Log activity
        $this->logAction("Duplicated Widget: {$widget->title}", $newWidget);

        return redirect()->route('admin.content.homepage-builder.index')
            ->with('success', 'Widget duplicated successfully!');
    }

    /**
     * Get template parameter options via AJAX
     */
    public function getTemplateOptions(string $template)
    {
        $options = Widget::getParameterOptions($template);
        return response()->json($options);
    }
}
