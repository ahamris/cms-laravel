<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\PricingBooster;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingBoosterController extends AdminBaseController
{
    /**
     * Display a listing of pricing boosters.
     */
    public function index(): View
    {
        $boosters = PricingBooster::ordered()->paginate(20);

        return view('admin.content.pricing-booster.index', compact('boosters'));
    }

    /**
     * Show the form for creating a new pricing booster.
     */
    public function create(): View
    {
        return view('admin.content.pricing-booster.create');
    }

    /**
     * Store a newly created pricing booster.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pricing_boosters,slug',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'link_text' => 'nullable|string|max:255',
            'link_url' => 'nullable|string|max:255',
            'footnote' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Set checkbox
        $validated['is_active'] = $request->has('is_active');

        PricingBooster::create($validated);

        return redirect()->route('admin.content.pricing-booster.index')
            ->with('success', 'Pricing booster created successfully.');
    }

    /**
     * Display the specified pricing booster.
     */
    public function show(PricingBooster $pricingBooster): View
    {
        return view('admin.content.pricing-booster.show', compact('pricingBooster'));
    }

    /**
     * Show the form for editing the specified pricing booster.
     */
    public function edit(PricingBooster $pricingBooster): View
    {
        return view('admin.content.pricing-booster.edit', compact('pricingBooster'));
    }

    /**
     * Update the specified pricing booster.
     */
    public function update(Request $request, PricingBooster $pricingBooster): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pricing_boosters,slug,' . $pricingBooster->id,
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'link_text' => 'nullable|string|max:255',
            'link_url' => 'nullable|string|max:255',
            'footnote' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Set checkbox
        $validated['is_active'] = $request->has('is_active');

        $pricingBooster->update($validated);

        return redirect()->route('admin.content.pricing-booster.index')
            ->with('success', 'Pricing booster updated successfully.');
    }

    /**
     * Remove the specified pricing booster.
     */
    public function destroy(PricingBooster $pricingBooster): RedirectResponse
    {
        $pricingBooster->delete();

        return redirect()->route('admin.content.pricing-booster.index')
            ->with('success', 'Pricing booster deleted successfully.');
    }

    /**
     * Update sort order of pricing boosters.
     */
    public function updateOrder(Request $request)
    {
        $order = $request->input('order', []);
        
        foreach ($order as $index => $id) {
            PricingBooster::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
