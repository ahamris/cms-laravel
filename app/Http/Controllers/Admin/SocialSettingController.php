<?php

namespace App\Http\Controllers\Admin;

use App\Models\SocialSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SocialSettingController extends AdminBaseController
{
    /**
     * Display a listing of social settings
     */
    public function index()
    {
        $socialSettings = SocialSetting::orderBy('name')->get();
        
        // Get statistics
        $stats = [
            'total' => SocialSetting::count(),
        ];

        return view('admin.social-settings.index', compact('socialSettings', 'stats'));
    }

    /**
     * Show the form for creating a new social setting
     */
    public function create()
    {
        return view('admin.social-settings.create');
    }

    /**
     * Store a newly created social setting in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:social_settings,name',
            'url' => 'required|url|max:500',
            'icon' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        SocialSetting::create([
            'name' => $request->name,
            'url' => $request->url,
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.social-settings.index')
            ->with('success', 'Social setting created successfully.');
    }

    /**
     * Show the form for editing the specified social setting
     */
    public function edit(SocialSetting $socialSetting)
    {
        return view('admin.social-settings.edit', compact('socialSetting'));
    }

    /**
     * Update the specified social setting in storage
     */
    public function update(Request $request, SocialSetting $socialSetting)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:social_settings,name,'.$socialSetting->id,
            'url' => 'required|url|max:500',
            'icon' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $socialSetting->update([
            'name' => $request->name,
            'url' => $request->url,
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.social-settings.index')
            ->with('success', 'Social setting updated successfully.');
    }

    /**
     * Remove the specified social setting from storage
     */
    public function destroy(SocialSetting $socialSetting)
    {
        $socialSetting->delete();

        return redirect()->route('admin.social-settings.index')
            ->with('success', 'Social setting deleted successfully.');
    }

    /**
     * Clear social settings cache
     */
    public function clearCache()
    {
        SocialSetting::getCached(); // This will refresh the cache

        return response()->json([
            'success' => true,
            'message' => 'Social settings cache cleared successfully.',
        ]);
    }
}
