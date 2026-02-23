<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\LegalRequest;
use App\Models\Legal;
use App\Models\LegalPageVersion;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LegalController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     * Table is rendered by Livewire admin.table component.
     */
    public function index(): View
    {
        return view('admin.content.legal.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.content.legal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LegalRequest $request)
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['body', 'faqs.*.answer']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('legal-pages', 'public');
            $validated['image'] = $imagePath;
        }

        // Set initial versioning
        $validated['current_version'] = 1;
        $validated['versioning_enabled'] = $request->input('versioning_enabled', true);

        $legal = Legal::create($validated);

        return redirect()->route('admin.content.legal.index')
            ->with('success', 'Legal page created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Legal $legal): View
    {
        return view('admin.content.legal.show', compact('legal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Legal $legal): View
    {
        return view('admin.content.legal.edit', compact('legal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LegalRequest $request, Legal $legal)
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['body', 'faqs.*.answer']);

        // Create version snapshot before update if versioning is enabled
        $versionNotes = $request->input('version_notes');
        if ($legal->versioning_enabled) {
            // Refresh to get latest state
            $legal->refresh();
            // Check if there are actual changes (excluding versioning fields and timestamps)
            $versioningFields = ['current_version', 'versioning_enabled', 'updated_at', 'created_at'];
            $hasChanges = false;
            foreach ($validated as $key => $value) {
                if (!in_array($key, $versioningFields)) {
                    $currentValue = $legal->getAttribute($key);
                    // Handle array/JSON comparison
                    if (is_array($value) || is_array($currentValue)) {
                        if (json_encode($value) !== json_encode($currentValue)) {
                            $hasChanges = true;
                            break;
                        }
                    } elseif ($currentValue != $value) {
                        $hasChanges = true;
                        break;
                    }
                }
            }
            
            if ($hasChanges) {
                $legal->createVersion($versionNotes);
            }
        }

        // Handle image deletion
        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            // Delete old image from storage if exists
            if ($legal->image) {
                \Storage::disk('public')->delete($legal->image);
            }
            // Set image to null in database
            $validated['image'] = null;
        }
        // Handle image upload
        elseif ($request->hasFile('image')) {
            // Delete old image if exists
            if ($legal->image && \Storage::disk('public')->exists($legal->image)) {
                \Storage::disk('public')->delete($legal->image);
            }

            $imagePath = $request->file('image')->store('legal-pages', 'public');
            $validated['image'] = $imagePath;
        }

        // Update versioning settings if provided
        if ($request->has('versioning_enabled')) {
            $validated['versioning_enabled'] = $request->input('versioning_enabled', true);
        }

        $legal->update($validated);

        return redirect()->route('admin.content.legal.index')
            ->with('success', 'Legal page updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Legal $legal)
    {
        $legal->delete();

        return redirect()->route('admin.content.legal.index')
            ->with('success', 'Legal page deleted successfully!');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Legal $legal)
    {
        $legal->update(['is_active' => !$legal->is_active]);

        return redirect()->route('admin.content.legal.index')
            ->with('success', 'Legal page status updated successfully!');
    }

    /**
     * Display version history for a legal page.
     */
    public function versions(Legal $legal): View
    {
        $versions = $legal->versions()
            ->with('creator')
            ->latest('version_number')
            ->get();

        return view('admin.content.legal.versions.index', compact('legal', 'versions'));
    }

    /**
     * Display a specific version.
     */
    public function showVersion(Legal $legal, $versionNumber): View
    {
        $version = $legal->getVersion($versionNumber);

        if (!$version) {
            abort(404, 'Version not found');
        }

        $version->load('creator');

        return view('admin.content.legal.versions.show', compact('legal', 'version'));
    }

    /**
     * Restore a specific version.
     */
    public function restoreVersion(Legal $legal, $versionNumber): RedirectResponse
    {
        $version = $legal->getVersion($versionNumber);

        if (!$version) {
            return redirect()->route('admin.content.legal.versions', $legal)
                ->with('error', 'Version not found');
        }

        $restored = $legal->restoreVersion($versionNumber);

        if ($restored) {
            return redirect()->route('admin.content.legal.edit', $legal)
                ->with('success', "Version {$versionNumber} restored successfully!");
        }

        return redirect()->route('admin.content.legal.versions', $legal)
            ->with('error', 'Failed to restore version');
    }

    /**
     * Manually create a version.
     */
    public function createVersion(LegalRequest $request, Legal $legal): RedirectResponse
    {
        $notes = $request->input('version_notes');

        $legal->createVersion($notes);

        return redirect()->route('admin.content.legal.versions', $legal)
            ->with('success', 'Version created successfully!');
    }
}
