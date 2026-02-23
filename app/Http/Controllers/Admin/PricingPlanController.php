<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\PricingPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingPlanController extends AdminBaseController
{
    /**
     * Display a listing of pricing plans.
     */
    public function index(): View
    {
        $plans = PricingPlan::ordered()->paginate(20);

        return view('admin.pricing-plan.index', compact('plans'));
    }

    /**
     * Show the form for creating a new pricing plan.
     */
    public function create(): View
    {
        return view('admin.pricing-plan.create');
    }

    /**
     * Store a newly created pricing plan.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pricing_plans,slug',
            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'footnote' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
        ]);

        // Set checkboxes
        $validated['is_active'] = $request->has('is_active');
        $validated['is_popular'] = $request->has('is_popular');

        $plan = PricingPlan::create($validated);

        return redirect()->route('admin.content.pricing-plan.index')
            ->with('success', 'Pricing plan created successfully.');
    }

    /**
     * Display the specified pricing plan.
     */
    public function show(PricingPlan $pricingPlan): View
    {
        return view('admin.pricing-plan.show', compact('pricingPlan'));
    }

    /**
     * Show the form for editing the specified pricing plan.
     */
    public function edit(PricingPlan $pricingPlan): View
    {
        return view('admin.pricing-plan.edit', compact('pricingPlan'));
    }

    /**
     * Update the specified pricing plan.
     */
    public function update(Request $request, PricingPlan $pricingPlan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pricing_plans,slug,' . $pricingPlan->id,
            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'footnote' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
        ]);

        // Set checkboxes
        $validated['is_active'] = $request->has('is_active');
        $validated['is_popular'] = $request->has('is_popular');

        $pricingPlan->update($validated);

        return redirect()->route('admin.content.pricing-plan.index')
            ->with('success', 'Pricing plan updated successfully.');
    }

    /**
     * Remove the specified pricing plan.
     */
    public function destroy(PricingPlan $pricingPlan): RedirectResponse
    {
        $pricingPlan->delete();

        return redirect()->route('admin.content.pricing-plan.index')
            ->with('success', 'Pricing plan deleted successfully.');
    }

    /**
     * Update sort order of pricing plans.
     */
    public function updateOrder(Request $request)
    {
        $order = $request->input('order', []);
        
        foreach ($order as $index => $id) {
            PricingPlan::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
