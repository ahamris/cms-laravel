<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\CarouselWidgetRequest;
use App\Models\CarouselWidget;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CarouselWidgetController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $carouselWidgets = CarouselWidget::with('blogCategory')
            ->ordered()
            ->paginate(20);

        return view('admin.carousel-widgets.index', compact('carouselWidgets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $blogCategories = BlogCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.carousel-widgets.create', compact('blogCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarouselWidgetRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['description', 'view_all_description']);

        $carouselWidget = CarouselWidget::create($validated);

        $this->logCreate($carouselWidget);

        return redirect()
            ->route('admin.content.carousel-widgets.index')
            ->with('success', 'Carousel widget created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CarouselWidget $carouselWidget): View
    {
        $carouselWidget->load('blogCategory');

        return view('admin.carousel-widgets.show', compact('carouselWidget'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CarouselWidget $carouselWidget): View
    {
        $blogCategories = BlogCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.carousel-widgets.edit', compact('carouselWidget', 'blogCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarouselWidgetRequest $request, CarouselWidget $carouselWidget): RedirectResponse
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['description', 'view_all_description']);

        $carouselWidget->update($validated);

        $this->logUpdate($carouselWidget);

        return redirect()
            ->route('admin.content.carousel-widgets.index')
            ->with('success', 'Carousel widget updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarouselWidget $carouselWidget): RedirectResponse
    {
        $carouselWidget->delete();

        $this->logDelete($carouselWidget);

        return redirect()
            ->route('admin.content.carousel-widgets.index')
            ->with('success', 'Carousel widget deleted successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(CarouselWidget $carouselWidget)
    {
        $carouselWidget->update(['is_active' => !$carouselWidget->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $carouselWidget->is_active,
        ]);
    }
}
