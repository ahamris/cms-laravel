<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\MarketingPersona;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MarketingPersonaController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $personas = MarketingPersona::ordered()->get();
        
        return view('admin.marketing.persona.index', compact('personas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.marketing.persona.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:marketing_personas,slug',
            'description' => 'nullable|string',
            'demographics' => 'nullable|array',
            'pain_points' => 'nullable|array',
            'goals' => 'nullable|array',
            'preferred_channels' => 'nullable|array',
            'avatar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('avatar_image')) {
            $validated['avatar_image'] = $request->file('avatar_image')->store('personas', 'public');
        }

        MarketingPersona::create($validated);

        return redirect()->route('admin.marketing.persona.index')
            ->with('success', 'Marketing persona created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MarketingPersona $persona): View
    {
        return view('admin.marketing.persona.show', compact('persona'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MarketingPersona $persona): View
    {
        return view('admin.marketing.persona.edit', compact('persona'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MarketingPersona $persona): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:marketing_personas,slug,' . $persona->id,
            'description' => 'nullable|string',
            'demographics' => 'nullable|array',
            'pain_points' => 'nullable|array',
            'goals' => 'nullable|array',
            'preferred_channels' => 'nullable|array',
            'avatar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('avatar_image')) {
            // Delete old image if exists
            if ($persona->avatar_image) {
                \Storage::disk('public')->delete($persona->avatar_image);
            }
            $validated['avatar_image'] = $request->file('avatar_image')->store('personas', 'public');
        }

        $persona->update($validated);

        return redirect()->route('admin.marketing.persona.index')
            ->with('success', 'Marketing persona updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MarketingPersona $persona): RedirectResponse
    {
        // Delete image if exists
        if ($persona->avatar_image) {
            \Storage::disk('public')->delete($persona->avatar_image);
        }

        $persona->delete();

        return redirect()->route('admin.marketing.persona.index')
            ->with('success', 'Marketing persona deleted successfully.');
    }
}
