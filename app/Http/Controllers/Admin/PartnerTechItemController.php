<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PartnerTechItemRequest;
use App\Models\PartnerTechItem;
use App\Models\StaticPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartnerTechItemController extends AdminBaseController
{
    public function index(Request $request): View
    {
        $items = PartnerTechItem::query()->orderBy('type')->get();

        return view('admin.partner-tech-item.index', compact('items'));
    }

    public function create(): View
    {
        $existingTypes = PartnerTechItem::query()->pluck('type')->all();
        $staticPages = StaticPage::where('is_active', true)->orderBy('title')->get(['id', 'title', 'slug']);

        return view('admin.partner-tech-item.create', compact('existingTypes', 'staticPages'));
    }

    public function store(PartnerTechItemRequest $request): RedirectResponse
    {
        $validated = $this->mergeUploadedImages($request->safeForModel(), $request);
        if (PartnerTechItem::where('type', $validated['type'])->exists()) {
            return redirect()->back()->withInput()->withErrors(['type' => 'A record for this type already exists. Edit it instead.']);
        }
        $item = PartnerTechItem::create($validated);
        $this->logCreate($item);

        return redirect()->route('admin.partner-tech-item.index')
            ->with('success', 'Record created successfully.');
    }

    public function show(PartnerTechItem $partnerTechItem): View
    {
        return view('admin.partner-tech-item.show', compact('partnerTechItem'));
    }

    public function edit(PartnerTechItem $partnerTechItem): View
    {
        $staticPages = StaticPage::where('is_active', true)->orderBy('title')->get(['id', 'title', 'slug']);

        return view('admin.partner-tech-item.edit', compact('partnerTechItem', 'staticPages'));
    }

    public function update(PartnerTechItemRequest $request, PartnerTechItem $partnerTechItem): RedirectResponse
    {
        $validated = $this->mergeUploadedImages($request->safeForModel(), $request, $partnerTechItem);
        $partnerTechItem->update($validated);

        return redirect()->route('admin.partner-tech-item.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(PartnerTechItem $partnerTechItem): RedirectResponse
    {
        abort(403, 'Deleting Partner/Tech Stack records is not allowed.');
    }

    public function toggleActive(PartnerTechItem $partnerTechItem): RedirectResponse
    {
        $partnerTechItem->update(['is_active' => ! $partnerTechItem->is_active]);
        $this->logUpdate($partnerTechItem);

        return redirect()->back()->with('success', $partnerTechItem->is_active ? 'Activated.' : 'Deactivated.');
    }

    /**
     * Merge uploaded banner and data[].image files into validated array (paths).
     */
    private function mergeUploadedImages(array $validated, PartnerTechItemRequest $request, ?PartnerTechItem $existing = null): array
    {
        if ($request->has('remove_banner')) {
            if ($existing?->banner) {
                $this->deleteImage($existing->banner);
            }
            $validated['banner'] = null;
        } elseif ($request->hasFile('banner')) {
            if ($existing?->banner) {
                $this->deleteImage($existing->banner);
            }
            $validated['banner'] = $this->uploadImage($request->file('banner'), 'partner-tech');
        }

        $data = $validated['data'] ?? [];
        foreach (array_keys($data) as $i) {
            $removeKey = 'remove_data_'.$i.'_image';
            if ($request->has($removeKey)) {
                if (! empty($data[$i]['image']) && is_string($data[$i]['image'])) {
                    $this->deleteImage($data[$i]['image']);
                }
                $data[$i]['image'] = null;
                continue;
            }
            $key = "data.{$i}.image";
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                if ($file && str_starts_with($file->getMimeType(), 'image/') && $file->getSize() <= 4 * 1024 * 1024) {
                    if (! empty($data[$i]['image']) && is_string($data[$i]['image'])) {
                        $this->deleteImage($data[$i]['image']);
                    }
                    $data[$i]['image'] = $this->uploadImage($file, 'partner-tech');
                }
            }
        }
        $validated['data'] = $data;

        return $validated;
    }
}
