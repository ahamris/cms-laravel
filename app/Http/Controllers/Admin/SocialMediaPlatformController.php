<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\SocialMediaPlatform;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SocialMediaPlatformController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $platforms = SocialMediaPlatform::ordered()->get();
        
        return view('admin.social-media-platforms.index', compact('platforms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.social-media-platforms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:social_media_platforms',
            'icon' => 'nullable|string|max:255',
            'color' => 'required|string|size:7',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        SocialMediaPlatform::create($request->all());

        return redirect()->route('admin.social-media-platforms.index')
            ->with('success', 'Social media platform created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialMediaPlatform $socialMediaPlatform): View
    {
        return view('admin.social-media-platforms.show', compact('socialMediaPlatform'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialMediaPlatform $socialMediaPlatform): View
    {
        return view('admin.social-media-platforms.edit', compact('socialMediaPlatform'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocialMediaPlatform $socialMediaPlatform)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:social_media_platforms,slug,' . $socialMediaPlatform->id,
            'icon' => 'nullable|string|max:255',
            'color' => 'required|string|size:7',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $socialMediaPlatform->update($request->all());

        return redirect()->route('admin.social-media-platforms.index')
            ->with('success', 'Social media platform updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialMediaPlatform $socialMediaPlatform)
    {
        $socialMediaPlatform->delete();

        return redirect()->route('admin.social-media-platforms.index')
            ->with('success', 'Social media platform deleted successfully!');
    }

    /**
     * Toggle platform active status
     */
    public function toggleActive(SocialMediaPlatform $socialMediaPlatform)
    {
        $socialMediaPlatform->update(['is_active' => !$socialMediaPlatform->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $socialMediaPlatform->is_active,
            'message' => $socialMediaPlatform->is_active ? 'Platform activated successfully!' : 'Platform deactivated successfully!',
        ]);
    }
}
