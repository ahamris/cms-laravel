<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrganizationRequest;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class OrganizationController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.organization.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.organization.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrganizationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        unset($validated['logo']);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->uploadImage($request->file('logo'), 'organizations');
        }

        Organization::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Organization created successfully.',
            ]);
        }

        return redirect()->route('admin.organization.index')
            ->with('success', 'Organization created successfully.');
    }

    /**
     * Get organization data as JSON (for drawer).
     */
    public function getJson(Organization $organization)
    {
        return response()->json([
            'id' => $organization->id,
            'name' => $organization->name,
            'logo' => $organization->logo,
            'logo_url' => $organization->logo_url,
            'created_at' => $organization->created_at?->toIso8601String(),
            'updated_at' => $organization->updated_at?->toIso8601String(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization): View
    {
        return view('admin.organization.show', compact('organization'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization): View
    {
        return view('admin.organization.edit', compact('organization'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrganizationRequest $request, Organization $organization): RedirectResponse
    {
        $validated = $request->validated();
        unset($validated['logo']);

        if ($request->has('remove_logo') && $request->input('remove_logo') == '1') {
            $this->deleteImage($organization->logo);
            $validated['logo'] = null;
        } elseif ($request->hasFile('logo')) {
            $this->deleteImage($organization->logo);
            $validated['logo'] = $this->uploadImage($request->file('logo'), 'organizations');
        }

        $organization->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Organization updated successfully.',
            ]);
        }

        return redirect()->route('admin.organization.index')
            ->with('success', 'Organization updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization): RedirectResponse
    {
        $this->deleteImage($organization->logo);
        $organization->delete();

        return redirect()->route('admin.organization.index')
            ->with('success', 'Organization deleted successfully.');
    }

    /**
     * Download a sample JSON file for import.
     * Format: array of objects with "name" (required). Optional: wrap in { "organizations": [...] }.
     */
    public function importSample(): Response
    {
        $sample = [
            ['name' => 'Acme Inc'],
            ['name' => 'Tech Corp'],
            ['name' => 'Global Solutions'],
        ];

        $content = json_encode($sample, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return response($content, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="organizations-import-sample.json"',
        ]);
    }

    /**
     * Import organizations from a JSON file.
     * Expected format: array of objects with "name" (required), e.g. [{"name":"Acme Inc"},...]
     * or {"organizations":[{"name":"Acme Inc"},...]}.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:json',
            'overwrite' => 'boolean',
        ]);

        $file = $request->file('file');
        $overwrite = $request->boolean('overwrite');

        try {
            $content = file_get_contents($file->getPathname());
            $data = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON format');
            }

            $items = isset($data['organizations']) && is_array($data['organizations'])
                ? $data['organizations']
                : (is_array($data) && isset($data[0]) ? $data : []);
            if (! is_array($items)) {
                $items = [$data];
            }

            $imported = 0;
            $skipped = 0;

            foreach ($items as $row) {
                $name = $row['name'] ?? $row['title'] ?? null;
                if (empty($name) || ! is_string($name)) {
                    $skipped++;
                    continue;
                }

                $exists = Organization::where('name', $name)->first();
                if ($exists && ! $overwrite) {
                    $skipped++;
                    continue;
                }

                if ($exists) {
                    $exists->update(['name' => $name]);
                } else {
                    Organization::create(['name' => $name]);
                }
                $imported++;
            }

            $message = "Import completed. {$imported} organization(s) imported.";
            if ($skipped > 0) {
                $message .= " {$skipped} skipped.";
            }

            return redirect()->route('admin.organization.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.organization.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
