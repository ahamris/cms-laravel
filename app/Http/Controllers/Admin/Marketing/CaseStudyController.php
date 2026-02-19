<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\CaseStudy;
use App\Models\ProductFeature;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class CaseStudyController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $caseStudies = CaseStudy::with(['productFeatures'])
            ->orderBy('is_featured', 'desc')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.marketing.case-study.index', compact('caseStudies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $productFeatures = ProductFeature::active()->ordered()->get();
        
        return view('admin.marketing.case-study.create', compact('productFeatures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:case_studies,slug',
            'client_name' => 'required|string|max:255',
            'client_industry' => 'nullable|string|max:255',
            'client_size' => 'nullable|string|max:255',
            'challenge' => 'required|string',
            'solution' => 'required|string',
            'results' => 'required|string',
            'metrics' => 'nullable|array',
            'metrics.*' => 'string',
            'quote' => 'nullable|string',
            'quote_author' => 'nullable|string|max:255',
            'quote_position' => 'nullable|string|max:255',
            'client_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_features' => 'nullable|array',
            'product_features.*' => 'exists:product_features,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Handle file uploads
        if ($request->hasFile('client_logo')) {
            $validated['client_logo'] = $request->file('client_logo')->store('case-studies/logos', 'public');
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('case-studies/images', 'public');
        }

        $caseStudy = CaseStudy::create($validated);

        // Sync product features
        if (isset($validated['product_features'])) {
            $caseStudy->productFeatures()->sync($validated['product_features']);
        }

        return redirect()->route('admin.marketing.case-study.index')
            ->with('success', 'Case study created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CaseStudy $caseStudy): View
    {
        $caseStudy->load(['productFeatures']);
        
        return view('admin.marketing.case-study.show', compact('caseStudy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CaseStudy $caseStudy): View
    {
        $caseStudy->load(['productFeatures']);
        $productFeatures = ProductFeature::active()->ordered()->get();
        
        return view('admin.marketing.case-study.edit', compact('caseStudy', 'productFeatures'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CaseStudy $caseStudy): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:case_studies,slug,' . $caseStudy->id,
            'client_name' => 'required|string|max:255',
            'client_industry' => 'nullable|string|max:255',
            'client_size' => 'nullable|string|max:255',
            'challenge' => 'required|string',
            'solution' => 'required|string',
            'results' => 'required|string',
            'metrics' => 'nullable|array',
            'metrics.*' => 'string',
            'quote' => 'nullable|string',
            'quote_author' => 'nullable|string|max:255',
            'quote_position' => 'nullable|string|max:255',
            'client_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_features' => 'nullable|array',
            'product_features.*' => 'exists:product_features,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Handle file uploads
        if ($request->hasFile('client_logo')) {
            // Delete old logo
            if ($caseStudy->client_logo) {
                Storage::disk('public')->delete($caseStudy->client_logo);
            }
            $validated['client_logo'] = $request->file('client_logo')->store('case-studies/logos', 'public');
        }

        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($caseStudy->featured_image) {
                Storage::disk('public')->delete($caseStudy->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('case-studies/images', 'public');
        }

        $caseStudy->update($validated);

        // Sync product features
        if (isset($validated['product_features'])) {
            $caseStudy->productFeatures()->sync($validated['product_features']);
        } else {
            $caseStudy->productFeatures()->detach();
        }

        return redirect()->route('admin.marketing.case-study.index')
            ->with('success', 'Case study updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CaseStudy $caseStudy): RedirectResponse
    {
        // Delete associated files
        if ($caseStudy->client_logo) {
            Storage::disk('public')->delete($caseStudy->client_logo);
        }
        if ($caseStudy->featured_image) {
            Storage::disk('public')->delete($caseStudy->featured_image);
        }

        $caseStudy->delete();

        return redirect()->route('admin.marketing.case-study.index')
            ->with('success', 'Case study deleted successfully.');
    }

    /**
     * Toggle the featured status of a case study.
     */
    public function toggleFeatured(CaseStudy $caseStudy): JsonResponse
    {
        $caseStudy->update(['is_featured' => !$caseStudy->is_featured]);

        return response()->json([
            'success' => true,
            'is_featured' => $caseStudy->is_featured,
        ]);
    }
}
