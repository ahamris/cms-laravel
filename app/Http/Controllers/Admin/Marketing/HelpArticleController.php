<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\HelpArticle;
use App\Models\ProductFeature;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class HelpArticleController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $helpArticles = HelpArticle::with(['productFeatures'])
            ->orderBy('difficulty_level')
            ->orderBy('sort_order')
            ->get();
        
        return view('admin.marketing.help-article.index', compact('helpArticles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $productFeatures = ProductFeature::active()->ordered()->get();
        
        return view('admin.marketing.help-article.create', compact('productFeatures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:help_articles,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'estimated_read_time' => 'nullable|integer|min:1',
            'product_features' => 'nullable|array',
            'product_features.*' => 'exists:product_features,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
        $validated = $this->purifyHtmlKeys($validated, ['content', 'excerpt']);

        $helpArticle = HelpArticle::create($validated);

        // Sync product features
        if (isset($validated['product_features'])) {
            $helpArticle->productFeatures()->sync($validated['product_features']);
        }

        return redirect()->route('admin.marketing.help-article.index')
            ->with('success', 'Help article created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HelpArticle $helpArticle): View
    {
        $helpArticle->load(['productFeatures']);
        
        return view('admin.marketing.help-article.show', compact('helpArticle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HelpArticle $helpArticle): View
    {
        $helpArticle->load(['productFeatures']);
        $productFeatures = ProductFeature::active()->ordered()->get();
        
        return view('admin.marketing.help-article.edit', compact('helpArticle', 'productFeatures'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HelpArticle $helpArticle): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:help_articles,slug,' . $helpArticle->id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'estimated_read_time' => 'nullable|integer|min:1',
            'product_features' => 'nullable|array',
            'product_features.*' => 'exists:product_features,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
        $validated = $this->purifyHtmlKeys($validated, ['content', 'excerpt']);

        $helpArticle->update($validated);

        // Sync product features
        if (isset($validated['product_features'])) {
            $helpArticle->productFeatures()->sync($validated['product_features']);
        } else {
            $helpArticle->productFeatures()->detach();
        }

        return redirect()->route('admin.marketing.help-article.index')
            ->with('success', 'Help article updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HelpArticle $helpArticle): RedirectResponse
    {
        $helpArticle->delete();

        return redirect()->route('admin.marketing.help-article.index')
            ->with('success', 'Help article deleted successfully.');
    }

    /**
     * Toggle the featured status of a help article.
     */
    public function toggleFeatured(HelpArticle $helpArticle): JsonResponse
    {
        $helpArticle->update(['is_featured' => !$helpArticle->is_featured]);

        return response()->json([
            'success' => true,
            'is_featured' => $helpArticle->is_featured,
        ]);
    }
}
