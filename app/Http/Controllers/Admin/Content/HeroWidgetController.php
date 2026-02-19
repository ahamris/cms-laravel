<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\HeroMediaWidget;
use App\Models\MegaMenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HeroWidgetController extends AdminBaseController
{
    /**
     * Display a listing of hero media widgets.
     */
    public function index(): View
    {
        $heroWidgets = HeroMediaWidget::orderBy('created_at', 'desc')->get();
        return view('admin.content.hero-widget.index', compact('heroWidgets'));
    }

    /**
     * Show the form for creating a new hero media widget.
     */
    public function create(): View
    {
        // Get available routes and system content for URL selector
        $availableRoutes = MegaMenuItem::possibleMenuItems();
        $systemContent = MegaMenuItem::getSystemContent();

        return view('admin.content.hero-widget.create', compact('availableRoutes', 'systemContent'));
    }

    /**
     * Store a newly created hero media widget in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Top Header
            'top_header_icon' => 'nullable|string|max:255',
            'top_header_text' => 'nullable|string|max:255',
            'top_header_url' => 'nullable|string|max:500',
            'top_header_text_color' => 'nullable|string|max:7',
            'top_header_bg_color' => 'nullable|string|max:7',
            
            // Title & Subtitle
            'title' => 'nullable|string|max:255',
            'title_color' => 'nullable|string|max:7',
            'subtitle' => 'nullable|string|max:1000',
            'subtitle_color' => 'nullable|string|max:7',
            
            // Slogan
            'slogan' => 'nullable|string|max:255',
            'slogan_color' => 'nullable|string|max:7',
            
            // List Items
            'list_items' => 'nullable|array',
            'list_items.*' => 'nullable|string|max:255',
            'list_item_color' => 'nullable|string|max:7',
            'list_item_icon' => 'nullable|string|max:255',
            
            // Primary Button
            'primary_button_text' => 'nullable|string|max:255',
            'primary_button_url' => 'nullable|string|max:500',
            'primary_button_text_color' => 'nullable|string|max:7',
            'primary_button_bg_color' => 'nullable|string|max:7',
            'primary_button_icon' => 'nullable|string|max:255',
            
            // Secondary Button
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:500',
            'secondary_button_text_color' => 'nullable|string|max:7',
            'secondary_button_bg_color' => 'nullable|string|max:7',
            'secondary_button_border_color' => 'nullable|string|max:7',
            'secondary_button_icon' => 'nullable|string|max:255',
            
            // Component Settings
            'component_type' => 'nullable|string|max:255',
            'height' => 'nullable|integer|min:0',
            'full_height' => 'boolean',
            
            // Background
            'background_type' => 'required|string|in:image,video',
            'video_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            
            'is_active' => 'boolean',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('hero-widgets', 'public');
                $validated['image'] = $path;
            }

            // Ensure is_active is set (toggle sends '1' when checked, '0' when unchecked)
            $validated['is_active'] = $request->input('is_active', '0') === '1';

            $heroWidget = HeroMediaWidget::create($validated);

            $this->logActivity('Hero Media Widget created', $heroWidget->id);

            return redirect()->route('admin.content.hero-widget.index')
                ->with('success', 'Hero media widget created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for editing the hero widget.
     */
    public function edit(HeroMediaWidget $heroWidget): View
    {
        // Get available routes and system content for URL selector
        $availableRoutes = MegaMenuItem::possibleMenuItems();
        $systemContent = MegaMenuItem::getSystemContent();

        return view('admin.content.hero-widget.edit', compact('heroWidget', 'availableRoutes', 'systemContent'));
    }

    /**
     * Update the hero widget in storage.
     */
    public function update(Request $request, HeroMediaWidget $heroWidget): RedirectResponse
    {
        
        $validated = $request->validate([
            // Top Header
            'top_header_icon' => 'nullable|string|max:255',
            'top_header_text' => 'nullable|string|max:255',
            'top_header_url' => 'nullable|string|max:500',
            'top_header_text_color' => 'nullable|string|max:7',
            'top_header_bg_color' => 'nullable|string|max:7',
            
            // Title & Subtitle
            'title' => 'nullable|string|max:255',
            'title_color' => 'nullable|string|max:7',
            'subtitle' => 'nullable|string|max:1000',
            'subtitle_color' => 'nullable|string|max:7',
            
            // Slogan
            'slogan' => 'nullable|string|max:255',
            'slogan_color' => 'nullable|string|max:7',
            
            // List Items
            'list_items' => 'nullable|array',
            'list_items.*' => 'nullable|string|max:255',
            'list_item_color' => 'nullable|string|max:7',
            'list_item_icon' => 'nullable|string|max:255',
            
            // Primary Button
            'primary_button_text' => 'nullable|string|max:255',
            'primary_button_url' => 'nullable|string|max:500',
            'primary_button_text_color' => 'nullable|string|max:7',
            'primary_button_bg_color' => 'nullable|string|max:7',
            'primary_button_icon' => 'nullable|string|max:255',
            
            // Secondary Button
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:500',
            'secondary_button_text_color' => 'nullable|string|max:7',
            'secondary_button_bg_color' => 'nullable|string|max:7',
            'secondary_button_border_color' => 'nullable|string|max:7',
            'secondary_button_icon' => 'nullable|string|max:255',
            
            // Component Settings
            'component_type' => 'nullable|string|max:255',
            'height' => 'nullable|integer|min:0',
            'full_height' => 'boolean',
            
            // Background
            'background_type' => 'required|string|in:image,video',
            'video_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            
            'is_active' => 'boolean',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($heroWidget->image && Storage::disk('public')->exists($heroWidget->image)) {
                    Storage::disk('public')->delete($heroWidget->image);
                }
                
                $path = $request->file('image')->store('hero-widgets', 'public');
                $validated['image'] = $path;
            } else {
                // Keep existing image if not uploading new one
                unset($validated['image']);
            }

            // Ensure is_active is set (toggle sends '1' when checked, '0' when unchecked)
            $validated['is_active'] = $request->input('is_active', '0') === '1';

            $heroWidget->update($validated);

            $this->logActivity('Hero Widget updated', $heroWidget->id);

            return redirect()->route('admin.content.hero-widget.index')
                ->with('success', 'Hero media widget updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified hero media widget from storage.
     */
    public function destroy(HeroMediaWidget $heroWidget): RedirectResponse
    {
        try {
            
            // Delete associated image if exists
            if ($heroWidget->image && Storage::disk('public')->exists($heroWidget->image)) {
                Storage::disk('public')->delete($heroWidget->image);
            }
            
            $heroWidget->delete();

            $this->logActivity('Hero Media Widget deleted', $heroWidget->id);

            return redirect()->route('admin.content.hero-widget.index')
                ->with('success', 'Hero media widget deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.content.hero-widget.index')
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}
