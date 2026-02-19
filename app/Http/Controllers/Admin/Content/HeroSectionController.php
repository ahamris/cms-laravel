<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\HeroSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeroSectionController extends AdminBaseController
{
    /**
     * Display a listing of hero sections.
     */
    public function index(): View
    {
        $heroSections = HeroSection::paginate(10);

        return view('admin.content.hero-sections.index', compact('heroSections'));
    }

    /**
     * Show the form for creating a new hero section.
     */
    public function create(): View
    {
        $availableRoutes = $this->getAvailableRoutes();
        $systemContent = $this->getSystemContent();
        return view('admin.content.hero-sections.create', compact('availableRoutes', 'systemContent'));
    }

    /**
     * Store a newly created hero section.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'top_header_icon' => 'nullable|string|max:255',
            'top_header_text' => 'nullable|string|max:255',
            'top_header_url' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'slogan' => 'nullable|string|max:255',
            'list_items.*' => 'string',
            'primary_button_text' => 'nullable|string|max:255',
            'primary_button_url' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:255',
            'card1_icon' => 'nullable|string|max:255',
            'card1_bgcolor' => 'nullable|in:bg-primary,bg-secondary',
            'card1_title' => 'nullable|string|max:255',
            'card1_description' => 'nullable|string|max:500',
            'card2_icon' => 'nullable|string|max:255',
            'card2_bgcolor' => 'nullable|in:bg-primary,bg-secondary',
            'card2_title' => 'nullable|string|max:255',
            'card2_description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hero-sections', 'public');
        }

        // Set is_active (toggle sends '1' when checked, '0' when unchecked)
        $validated['is_active'] = $request->input('is_active', '0') === '1';

        // Handle list_items - always process the array
        $listItems = $request->input('list_items', []);
        
        // Filter out empty items
        $listItems = array_filter($listItems, function($item) {
            return !empty(trim($item));
        });
        
        // Reindex array
        $validated['list_items'] = array_values($listItems);
                
        // If clear flag is present, force empty array
        if ($request->has('list_items_clear')) {
            $validated['list_items'] = [];
        }

        HeroSection::create($validated);

        return redirect()->route('admin.content.hero-section.index')
            ->with('success', 'Hero section created successfully.');
    }

    /**
     * Display the specified hero section.
     */
    public function show(HeroSection $heroSection): View
    {
        return view('admin.content.hero-sections.show', compact('heroSection'));
    }

    /**
     * Show the form for editing the specified hero section.
     */
    public function edit(HeroSection $heroSection): View
    {
        $availableRoutes = $this->getAvailableRoutes();
        $systemContent = $this->getSystemContent();
        return view('admin.content.hero-sections.edit', compact('heroSection', 'availableRoutes', 'systemContent'));
    }

    /**
     * Update the specified hero section.
     */
    public function update(Request $request, HeroSection $heroSection): RedirectResponse
    {
        $validated = $request->validate([
            'top_header_icon' => 'nullable|string|max:255',
            'top_header_text' => 'nullable|string|max:255',
            'top_header_url' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'slogan' => 'nullable|string|max:255',
            'list_items.*' => 'string',
            'primary_button_text' => 'nullable|string|max:255',
            'primary_button_url' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:255',
            'card1_icon' => 'nullable|string|max:255',
            'card1_bgcolor' => 'nullable|in:bg-primary,bg-secondary',
            'card1_title' => 'nullable|string|max:255',
            'card1_description' => 'nullable|string|max:500',
            'card2_icon' => 'nullable|string|max:255',
            'card2_bgcolor' => 'nullable|in:bg-primary,bg-secondary',
            'card2_title' => 'nullable|string|max:255',
            'card2_description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle image deletion
        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            // Delete old image from storage if exists
            if ($heroSection->image) {
                \Storage::disk('public')->delete($heroSection->image);
            }
            // Set image to null in database
            $validated['image'] = null;
        }
        // Handle image upload
        elseif ($request->hasFile('image')) {
            // Delete old image if exists
            if ($heroSection->image) {
                \Storage::disk('public')->delete($heroSection->image);
            }
            $validated['image'] = $request->file('image')->store('hero-sections', 'public');
        }

        // Set is_active (toggle sends '1' when checked, '0' when unchecked)
        $validated['is_active'] = $request->input('is_active', '0') === '1';

        // Handle list_items - always process the array
        $listItems = $request->input('list_items', []);
        
        // Filter out empty items
        $listItems = array_filter($listItems, function($item) {
            return !empty(trim($item));
        });
        
        // Reindex array
        $validated['list_items'] = array_values($listItems);
        
        // If clear flag is present, force empty array
        if ($request->has('list_items_clear')) {
            $validated['list_items'] = [];
        }

        $heroSection->update($validated);

        return redirect()->route('admin.content.hero-section.index')
            ->with('success', 'Hero section updated successfully.');
    }

    /**
     * Remove the specified hero section.
     */
    public function destroy(HeroSection $heroSection): RedirectResponse
    {
        // Delete associated image
        if ($heroSection->image) {
            \Storage::disk('public')->delete($heroSection->image);
        }

        $heroSection->delete();

        return redirect()->route('admin.content.hero-section.index')
            ->with('success', 'Hero section deleted successfully.');
    }

    /**
     * Toggle active status of hero section.
     */
    public function toggleActive(HeroSection $heroSection): JsonResponse
    {
        // Toggle the active status
        $heroSection->is_active = !$heroSection->is_active;
        $heroSection->save();

        return response()->json([
            'success' => true,
            'is_active' => $heroSection->is_active,
            'message' => $heroSection->is_active ? 'Hero section activated.' : 'Hero section deactivated.'
        ]);
    }

    /**
     * Update sort order of hero sections.
     */
    public function updateOrder(Request $request): JsonResponse
    {
        // In the simplified structure, we don't use sort_order
        // This method is kept for compatibility but doesn't change anything
        return response()->json([
            'message' => 'Sort order is not used in the current structure.',
        ]);
    }

    /**
     * Create default hero section (simplified approach).
     */
    public function createDefault(): RedirectResponse
    {
        // In the simplified structure, we use seeders for default data
        return redirect()->route('admin.content.hero-section.index')
            ->with('info', 'Please use seeders to create default hero sections.');
    }

    /**
     * Get available routes for URL selector.
     */
    private function getAvailableRoutes(): array
    {
        return \App\Models\MegaMenuItem::possibleMenuItems();
    }

    /**
     * Get system content for URL selector.
     */
    private function getSystemContent(): array
    {
        $systemContent = [];

        // Pages
        if (class_exists(\App\Models\Page::class)) {
            $pages = \App\Models\Page::where('is_active', true)->select('id', 'title', 'slug')->get();
            if ($pages->isNotEmpty()) {
                $systemContent['Pages'] = $pages->map(function ($page) {
                    return [
                        'title' => $page->title,
                        'url' => "/pages/{$page->slug}",
                        'type' => 'page'
                    ];
                })->toArray();
            }
        }

        // Solutions
        if (class_exists(\App\Models\Solution::class)) {
            $solutions = \App\Models\Solution::where('is_active', true)->select('id', 'title', 'slug')->get();
            if ($solutions->isNotEmpty()) {
                $systemContent['Solutions'] = $solutions->map(function ($solution) {
                    return [
                        'title' => $solution->title,
                        'url' => "/solutions/{$solution->slug}",
                        'type' => 'solution'
                    ];
                })->toArray();
            }
        }

        // Blog Posts
        if (class_exists(\App\Models\Blog::class)) {
            $blogs = \App\Models\Blog::where('is_active', true)->select('id', 'title', 'slug')->limit(20)->get();
            if ($blogs->isNotEmpty()) {
                $systemContent['Blog Posts'] = $blogs->map(function ($blog) {
                    return [
                        'title' => $blog->title,
                        'url' => "/artikelen/{$blog->slug}",
                        'type' => 'blog'
                    ];
                })->toArray();
            }
        }

        return $systemContent;
    }
}
