<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\ProductFeature;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductFeatureController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $productFeatures = ProductFeature::ordered()->get();
        $categories = ProductFeature::getCategories();
        
        return view('admin.marketing.product-feature.index', compact('productFeatures', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.marketing.product-feature.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:product_features,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string|max:255',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        ProductFeature::create($validated);

        return redirect()->route('admin.marketing.product-feature.index')
            ->with('success', 'Product feature created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductFeature $productFeature): View
    {
        return view('admin.marketing.product-feature.show', compact('productFeature'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductFeature $productFeature): View
    {
        return view('admin.marketing.product-feature.edit', compact('productFeature'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductFeature $productFeature): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:product_features,slug,' . $productFeature->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string|max:255',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $productFeature->update($validated);

        return redirect()->route('admin.marketing.product-feature.index')
            ->with('success', 'Product feature updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductFeature $productFeature): RedirectResponse
    {
        $productFeature->delete();

        return redirect()->route('admin.marketing.product-feature.index')
            ->with('success', 'Product feature deleted successfully.');
    }
}
