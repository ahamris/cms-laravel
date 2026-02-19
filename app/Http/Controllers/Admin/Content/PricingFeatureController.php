<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\PricingFeature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingFeatureController extends AdminBaseController
{
    /**
     * Display a listing of pricing features.
     */
    public function index(): View
    {
        $features = PricingFeature::ordered()->paginate(20);

        return view('admin.content.pricing-feature.index', compact('features'));
    }

    /**
     * Show the form for creating a new pricing feature.
     */
    public function create(): View
    {
        return view('admin.content.pricing-feature.create');
    }

    /**
     * Store a newly created pricing feature.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'available_in_plans' => 'nullable|array',
            'available_in_plans.*' => 'string',
            'badge' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Set checkbox
        $validated['is_active'] = $request->has('is_active');

        PricingFeature::create($validated);

        return redirect()->route('admin.content.pricing-feature.index')
            ->with('success', 'Pricing feature created successfully.');
    }

    /**
     * Display the specified pricing feature.
     */
    public function show(PricingFeature $pricingFeature): View
    {
        return view('admin.content.pricing-feature.show', compact('pricingFeature'));
    }

    /**
     * Show the form for editing the specified pricing feature.
     */
    public function edit(PricingFeature $pricingFeature): View
    {
        return view('admin.content.pricing-feature.edit', compact('pricingFeature'));
    }

    /**
     * Update the specified pricing feature.
     */
    public function update(Request $request, PricingFeature $pricingFeature): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'available_in_plans' => 'nullable|array',
            'available_in_plans.*' => 'string',
            'badge' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Set checkbox
        $validated['is_active'] = $request->has('is_active');

        $pricingFeature->update($validated);

        return redirect()->route('admin.content.pricing-feature.index')
            ->with('success', 'Pricing feature updated successfully.');
    }

    /**
     * Remove the specified pricing feature.
     */
    public function destroy(PricingFeature $pricingFeature): RedirectResponse
    {
        $pricingFeature->delete();

        return redirect()->route('admin.content.pricing-feature.index')
            ->with('success', 'Pricing feature deleted successfully.');
    }

    /**
     * Update sort order of pricing features.
     */
    public function updateOrder(Request $request)
    {
        $order = $request->input('order', []);
        
        foreach ($order as $index => $id) {
            PricingFeature::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
