<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\RobotsTxt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RobotsTxtController extends AdminBaseController
{
    /**
     * Display the robots.txt editor
     */
    public function index(): View
    {
        $robotsTxt = RobotsTxt::where('is_active', true)->first();

        if (!$robotsTxt) {
            $robotsTxt = new RobotsTxt();
            $robotsTxt->content = RobotsTxt::getDefaultContent();
        }

        return view('admin.settings.robots-txt.index', compact('robotsTxt'));
    }

    /**
     * Update the robots.txt content
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        // Deactivate all existing robots.txt entries
        RobotsTxt::query()->update(['is_active' => false]);

        // Create or update the active robots.txt
        $robotsTxt = RobotsTxt::where('is_active', true)->first();

        if ($robotsTxt) {
            $robotsTxt->update($validated);
        } else {
            RobotsTxt::create(array_merge($validated, ['is_active' => true]));
        }

        return redirect()
            ->route('admin.settings.robots-txt.index')
            ->with('success', 'Robots.txt updated successfully');
    }

    /**
     * Reset to default content
     */
    public function reset(): RedirectResponse
    {
        // Deactivate all existing robots.txt entries
        RobotsTxt::query()->update(['is_active' => false]);

        // Create new default entry
        RobotsTxt::create([
            'content' => RobotsTxt::getDefaultContent(),
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.settings.robots-txt.index')
            ->with('success', 'Robots.txt reset to default successfully');
    }

    /**
     * Clear cache
     */
    public function clearCache(): RedirectResponse
    {
        RobotsTxt::clearCache();

        return redirect()
            ->route('admin.settings.robots-txt.index')
            ->with('success', 'Robots.txt cache cleared successfully');
    }
}
