<?php

namespace App\Http\Controllers\Admin\Element;

use App\Enums\ElementType;
use App\Models\Element;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSectionElementController extends BaseTypedElementController
{
    protected function type(): ElementType
    {
        return ElementType::HeroSection;
    }

    protected function heading(): string
    {
        return 'Hero sections';
    }

    protected function routeBase(): string
    {
        return 'admin.element-hero-section';
    }

    protected function optionsFormView(): string
    {
        return 'admin.elements.forms.hero-section-options';
    }

    protected function showOptionsView(): string
    {
        return 'admin.elements.show.hero-section-options';
    }

    protected function typeHelp(): string
    {
        return 'Generic hero block backend schema for wireframe-driven frontend rendering.';
    }

    protected function validateOptions(Request $request): array
    {
        return $request->validate([
            'options.variant' => 'nullable|string|max:100',
            'options.layout' => 'nullable|string|max:100',
            'options.media_type' => 'nullable|string|in:none,image,video',
            'options.media_url' => 'nullable|string|max:1000',
            'options.eyebrow' => 'nullable|string|max:255',
            'options.primary_button_text' => 'nullable|string|max:255',
            'options.primary_button_url' => 'nullable|string|max:500',
            'options.secondary_button_text' => 'nullable|string|max:255',
            'options.secondary_button_url' => 'nullable|string|max:500',
            'options.background_style' => 'nullable|string|max:100',
            'options.text_alignment' => 'nullable|string|in:left,center,right',
        ])['options'];
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'image_file' => 'nullable|image|max:5120',
        ]);

        $validated = $this->validatedPayload($request);
        $options = $validated['options'];

        if ($request->hasFile('image_file')) {
            $options['image_path'] = $request->file('image_file')->store('element-hero-sections', 'public');
        }

        Element::create([
            'type' => $this->type(),
            'title' => $validated['title'] ?? null,
            'sub_title' => $validated['sub_title'] ?? null,
            'description' => $validated['description'] ?? null,
            'options' => $options,
        ]);

        return redirect()->route($this->routeBase().'.index')
            ->with('success', $this->heading().' item created successfully.');
    }

    public function update(Request $request, int $element): RedirectResponse
    {
        $request->validate([
            'image_file' => 'nullable|image|max:5120',
            'remove_image_file' => 'nullable|boolean',
        ]);

        $item = $this->findTypedElement($element);
        $validated = $this->validatedPayload($request);
        $options = $validated['options'];
        $existingImagePath = $item->options['image_path'] ?? null;

        if ($request->boolean('remove_image_file')) {
            if ($existingImagePath) {
                Storage::disk('public')->delete($existingImagePath);
            }
            $options['image_path'] = null;
        } elseif ($request->hasFile('image_file')) {
            if ($existingImagePath) {
                Storage::disk('public')->delete($existingImagePath);
            }
            $options['image_path'] = $request->file('image_file')->store('element-hero-sections', 'public');
        } elseif ($existingImagePath) {
            $options['image_path'] = $existingImagePath;
        }

        $item->update([
            'type' => $this->type(),
            'title' => $validated['title'] ?? null,
            'sub_title' => $validated['sub_title'] ?? null,
            'description' => $validated['description'] ?? null,
            'options' => $options,
        ]);

        return redirect()->route($this->routeBase().'.index')
            ->with('success', $this->heading().' item updated successfully.');
    }
}
