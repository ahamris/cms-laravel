<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\OrganizationNameRequest;
use App\Models\OrganizationName;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationNameController extends AdminBaseController
{
    /**
     * Display a listing of organization names.
     */
    public function index(): View
    {
        return view('admin.content.organization-name.index');
    }

    /**
     * Show the form for creating a new organization name.
     */
    public function create(): View
    {
        return view('admin.content.organization-name.create');
    }

    /**
     * Store a newly created organization name in storage.
     */
    public function store(OrganizationNameRequest $request)
    {
        $validated = $request->validated();

        // Set is_active (toggle sends '1' when checked, '0' when unchecked)
        $validated['is_active'] = $request->input('is_active', '0') === '1';

        // Set default sort order if not provided
        if (! isset($validated['sort_order'])) {
            $validated['sort_order'] = OrganizationName::max('sort_order') + 1;
        }

        $organizationName = OrganizationName::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Organization name created successfully.',
                'data' => $organizationName,
            ]);
        }

        return redirect()->route('admin.content.organization-name.index')
            ->with('success', 'Organization name created successfully.');
    }

    /**
     * Display the specified organization name.
     */
    public function show(OrganizationName $organizationName): View
    {
        return view('admin.content.organization-name.show', compact('organizationName'));
    }

    /**
     * Get organization name data as JSON (for drawer).
     */
    public function getJson(OrganizationName $organizationName)
    {
        return response()->json([
            'id' => $organizationName->id,
            'name' => $organizationName->name,
            'abbreviation' => $organizationName->abbreviation,
            'email' => $organizationName->email,
            'address' => $organizationName->address,
            'sort_order' => $organizationName->sort_order,
            'is_active' => $organizationName->is_active,
        ]);
    }

    /**
     * Show the form for editing the specified organization name.
     */
    public function edit(OrganizationName $organizationName): View
    {
        return view('admin.content.organization-name.edit', compact('organizationName'));
    }

    /**
     * Update the specified organization name in storage.
     */
    public function update(OrganizationNameRequest $request, OrganizationName $organizationName)
    {
        $validated = $request->validated();

        // Set is_active (toggle sends '1' when checked, '0' when unchecked)
        $validated['is_active'] = $request->input('is_active', '0') === '1';

        $organizationName->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Organization name updated successfully.',
                'data' => $organizationName,
            ]);
        }

        return redirect()->route('admin.content.organization-name.index')
            ->with('success', 'Organization name updated successfully.');
    }

    /**
     * Remove the specified organization name from storage.
     */
    public function destroy(OrganizationName $organizationName)
    {
        $organizationName->delete();

        return redirect()->route('admin.content.organization-name.index')
            ->with('success', 'Organization name deleted successfully.');
    }

    /**
     * Toggle the active status of the specified organization name.
     */
    public function toggleActive(OrganizationName $organizationName)
    {
        $organizationName->update([
            'is_active' => ! $organizationName->is_active,
        ]);

        $status = $organizationName->is_active ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'is_active' => $organizationName->is_active,
            'message' => "Organization name {$status} successfully.",
        ]);
    }

    /**
     * Update the order of organization names.
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:organization_names,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            OrganizationName::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        // Clear cache
        OrganizationName::clearCache();

        return response()->json(['success' => true]);
    }

    /**
     * Search organization names using Typesense.
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1',
            'per_page' => 'integer|min:1|max:100',
        ]);

        $query = $request->input('q');
        $perPage = $request->input('per_page', 10);

        $results = OrganizationName::search($query)
            ->where('is_active', true)
            ->paginate($perPage);

        return response()->json([
            'data' => $results->items(),
            'pagination' => [
                'current_page' => $results->currentPage(),
                'per_page' => $results->perPage(),
                'total' => $results->total(),
                'last_page' => $results->lastPage(),
            ],
        ]);
    }
}
