<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\AboutRequest;
use App\Models\About;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutController extends AdminBaseController
{
    /**
     * Display a listing of about sections.
     */
    public function index()
    {
        // Get the single about record or redirect to edit if it exists
        $about = About::first();

        if ($about) {
            return redirect()->route('admin.content.about.edit', $about);
        }

        // If no record exists, show empty state
        return view('admin.content.about.index', ['abouts' => collect()]);
    }

    /**
     * Show the form for creating a new about section.
     * Disabled - redirects to edit existing record.
     */
    public function create(): RedirectResponse
    {
        $about = About::first();

        if ($about) {
            return redirect()->route('admin.content.about.edit', $about)
                ->with('info', 'Only one about section is allowed. Edit the existing one.');
        }

        return redirect()->route('admin.content.about.index')
            ->with('error', 'No about section found. Please run the seeder first.');
    }

    /**
     * Store a newly created about section.
     * Disabled - redirects to edit existing record.
     */
    public function store(AboutRequest $request): RedirectResponse
    {
        $about = About::first();

        if ($about) {
            return redirect()->route('admin.content.about.edit', $about)
                ->with('info', 'Only one about section is allowed. Edit the existing one.');
        }

        return redirect()->route('admin.content.about.index')
            ->with('error', 'Creation not allowed. Please run the seeder first.');
    }

    /**
     * Display the specified about section.
     */
    public function show(About $about): View
    {
        return view('admin.content.about.show', compact('about'));
    }

    /**
     * Show the form for editing the specified about section.
     */
    public function edit(About $about): View
    {
        return view('admin.content.about.edit', compact('about'));
    }

    /**
     * Update the specified about section.
     */
    public function update(AboutRequest $request, About $about): RedirectResponse
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

        // Handle image deletion
        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            // Delete old image from storage if exists
            if ($about->image) {
                \Storage::disk('public')->delete($about->image);
            }
            // Set image to null in database
            $validated['image'] = null;
        }
        // Handle image upload
        elseif ($request->hasFile('image')) {
            // Delete old image if exists
            if ($about->image) {
                \Storage::disk('public')->delete($about->image);
            }
            $validated['image'] = $request->file('image')->store('abouts', 'public');
        }

        // Set is_active (toggle sends '1' when checked, '0' when unchecked)
        $validated['is_active'] = $request->input('is_active', '0') === '1';

        $about->update($validated);

        return redirect()->route('admin.content.about.index')
            ->with('success', 'About section updated successfully.');
    }

    /**
     * Remove the specified about section.
     * Disabled - deletion not allowed.
     */
    public function destroy(About $about): RedirectResponse
    {
        return redirect()->route('admin.content.about.edit', $about)
            ->with('error', 'Deletion not allowed. The about section cannot be deleted.');
    }

    /**
     * Update sort order of about sections.
     */
    public function updateOrder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $id) {
            About::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
