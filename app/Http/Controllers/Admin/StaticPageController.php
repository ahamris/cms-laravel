<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StaticPageRequest;
use App\Models\StaticPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StaticPageController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $staticPages = StaticPage::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.static-page.index', compact('staticPages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.static-page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StaticPageRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['body', 'faqs.*.answer']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('static-pages', 'public');
            $validated['image'] = $imagePath;
        }

        $staticPage = StaticPage::create($validated);

        return redirect()
            ->route('admin.static-page.index')
            ->with('success', 'Static page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StaticPage $staticPage): View
    {
        return view('admin.static-page.show', compact('staticPage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StaticPage $staticPage): View
    {
        return view('admin.static-page.edit', compact('staticPage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StaticPageRequest $request, StaticPage $staticPage): RedirectResponse
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['body', 'faqs.*.answer']);

        // Handle image deletion
        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            // Delete old image from storage if exists
            if ($staticPage->image) {
                \Storage::disk('public')->delete($staticPage->image);
            }
            // Set image to null in database
            $validated['image'] = null;
        }
        // Handle image upload
        elseif ($request->hasFile('image')) {
            // Delete old image if exists
            if ($staticPage->image && \Storage::disk('public')->exists($staticPage->image)) {
                \Storage::disk('public')->delete($staticPage->image);
            }

            $imagePath = $request->file('image')->store('static-pages', 'public');
            $validated['image'] = $imagePath;
        }

        $staticPage->update($validated);

        return redirect()
            ->route('admin.static-page.index')
            ->with('success', 'Static page updated successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(StaticPage $staticPage): RedirectResponse
    {
        $staticPage->update(['is_active' => ! $staticPage->is_active]);

        $status = $staticPage->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', "Static page {$status} successfully.");
    }
}
