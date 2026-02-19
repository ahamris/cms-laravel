<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\MarketingTestimonial;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class MarketingTestimonialController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $testimonials = MarketingTestimonial::ordered()->get();
        
        return view('admin.marketing.testimonial.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.marketing.testimonial.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'quote' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rating' => 'nullable|integer|min:1|max:5',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Handle image uploads
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('testimonials/photos', 'public');
        }

        if ($request->hasFile('company_logo')) {
            $validated['company_logo'] = $request->file('company_logo')->store('testimonials/logos', 'public');
        }

        MarketingTestimonial::create($validated);

        return redirect()->route('admin.marketing.testimonial.index')
            ->with('success', 'Testimonial created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MarketingTestimonial $testimonial): View
    {
        return view('admin.marketing.testimonial.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MarketingTestimonial $testimonial): View
    {
        return view('admin.marketing.testimonial.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MarketingTestimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'quote' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rating' => 'nullable|integer|min:1|max:5',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Handle image uploads
        if ($request->hasFile('photo')) {
            if ($testimonial->photo) {
                \Storage::disk('public')->delete($testimonial->photo);
            }
            $validated['photo'] = $request->file('photo')->store('testimonials/photos', 'public');
        }

        if ($request->hasFile('company_logo')) {
            if ($testimonial->company_logo) {
                \Storage::disk('public')->delete($testimonial->company_logo);
            }
            $validated['company_logo'] = $request->file('company_logo')->store('testimonials/logos', 'public');
        }

        $testimonial->update($validated);

        return redirect()->route('admin.marketing.testimonial.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MarketingTestimonial $testimonial): RedirectResponse
    {
        // Delete images if they exist
        if ($testimonial->photo) {
            \Storage::disk('public')->delete($testimonial->photo);
        }
        if ($testimonial->company_logo) {
            \Storage::disk('public')->delete($testimonial->company_logo);
        }

        $testimonial->delete();

        return redirect()->route('admin.marketing.testimonial.index')
            ->with('success', 'Testimonial deleted successfully.');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(MarketingTestimonial $testimonial): JsonResponse
    {
        $testimonial->update(['featured' => !$testimonial->featured]);

        return response()->json([
            'success' => true,
            'featured' => $testimonial->featured
        ]);
    }
}
