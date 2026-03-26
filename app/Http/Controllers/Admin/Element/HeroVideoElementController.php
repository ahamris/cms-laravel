<?php

namespace App\Http\Controllers\Admin\Element;

use App\Enums\ElementType;
use App\Models\Element;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroVideoElementController extends BaseTypedElementController
{
    protected function type(): ElementType
    {
        return ElementType::HeroVideo;
    }

    protected function heading(): string
    {
        return 'Hero sections';
    }

    protected function routeBase(): string
    {
        return 'admin.element-hero-video';
    }

    protected function optionsFormView(): string
    {
        return 'admin.elements.forms.hero-video-options';
    }

    protected function showOptionsView(): string
    {
        return 'admin.elements.show.hero-video-options';
    }

    protected function typeHelp(): string
    {
        return 'Create reusable hero sections with optional video and two call-to-action buttons.';
    }

    protected function validateOptions(Request $request): array
    {
        return $request->validate([
            'options.primary_button_text' => 'nullable|string|max:255',
            'options.primary_button_url' => 'nullable|string|max:500',
            'options.secondary_button_text' => 'nullable|string|max:255',
            'options.secondary_button_url' => 'nullable|string|max:500',
        ])['options'];
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'video_file' => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg|max:51200',
        ]);

        $validated = $this->validatedPayload($request);
        $options = $validated['options'];

        if ($request->hasFile('video_file')) {
            $options['video_path'] = $request->file('video_file')->store('element-hero-videos', 'public');
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
            'video_file' => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg|max:51200',
            'remove_video_file' => 'nullable|boolean',
        ]);

        $item = $this->findTypedElement($element);
        $validated = $this->validatedPayload($request);
        $options = $validated['options'];
        $existingVideoPath = $item->options['video_path'] ?? null;

        if ($request->boolean('remove_video_file')) {
            if ($existingVideoPath) {
                Storage::disk('public')->delete($existingVideoPath);
            }
            $options['video_path'] = null;
        } elseif ($request->hasFile('video_file')) {
            if ($existingVideoPath) {
                Storage::disk('public')->delete($existingVideoPath);
            }
            $options['video_path'] = $request->file('video_file')->store('element-hero-videos', 'public');
        } elseif ($existingVideoPath) {
            $options['video_path'] = $existingVideoPath;
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

    public function clone(int $element): RedirectResponse
    {
        $item = $this->findTypedElement($element);
        $options = $item->options ?? [];
        $newVideoPath = $options['video_path'] ?? null;

        // Duplicate media file so clone can be managed independently.
        if (! empty($newVideoPath) && Storage::disk('public')->exists($newVideoPath)) {
            $extension = pathinfo($newVideoPath, PATHINFO_EXTENSION);
            $copyPath = 'element-hero-videos/'.uniqid('clone_', true).($extension ? '.'.$extension : '');
            Storage::disk('public')->copy($newVideoPath, $copyPath);
            $options['video_path'] = $copyPath;
        }

        $clone = Element::create([
            'type' => $this->type(),
            'title' => $item->title ? $item->title.' (copy)' : 'Untitled hero (copy)',
            'sub_title' => $item->sub_title,
            'description' => $item->description,
            'options' => $options,
        ]);

        return redirect()->route($this->routeBase().'.edit', $clone->id)
            ->with('success', 'Hero section cloned successfully. You can now edit the copy.');
    }
}
