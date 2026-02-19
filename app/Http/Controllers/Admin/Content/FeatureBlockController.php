<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\FeatureBlockRequest;
use App\Models\FeatureBlock;
use Illuminate\Http\Request;

class FeatureBlockController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $featureBlocks = FeatureBlock::orderBy('sort_order')->paginate(20);

        return view('admin.content.block-feature.index', compact('featureBlocks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Use MegaMenuItem business logic for URL selection consistency
        $availableRoutes = \App\Models\MegaMenuItem::possibleMenuItems();
        $systemContent = \App\Models\MegaMenuItem::getSystemContent();

        return view('admin.content.block-feature.create', compact('availableRoutes', 'systemContent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FeatureBlockRequest $request)
    {
        $data = $request->validated();
        $data = $this->purifyHtmlKeys($data, ['items.*.content']);

        // Set is_active (toggle sends '1' when checked, '0' when unchecked)
        $data['is_active'] = $request->input('is_active', '0') === '1';

        // Handle image uploads for each item
        if ($request->hasFile('item_images')) {
            $items = $data['items'];
            foreach ($request->file('item_images') as $index => $image) {
                if ($image) {
                    $items[$index]['image'] = $this->uploadImage($image, 'feature-blocks');
                }
            }
            $data['items'] = $items;
        }

        // Auto-assign sort order
        if (! isset($data['sort_order'])) {
            $data['sort_order'] = FeatureBlock::max('sort_order') + 1;
        }

        FeatureBlock::create($data);

        return redirect()
            ->route('admin.content.block-feature.index')
            ->with('success', 'Feature Block created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FeatureBlock $blockFeature)
    {
        return view('admin.content.block-feature.show', compact('blockFeature'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeatureBlock $blockFeature)
    {
        // Use MegaMenuItem business logic for URL selection consistency
        $availableRoutes = \App\Models\MegaMenuItem::possibleMenuItems();
        $systemContent = \App\Models\MegaMenuItem::getSystemContent();

        return view('admin.content.block-feature.edit', compact('blockFeature', 'availableRoutes', 'systemContent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FeatureBlockRequest $request, FeatureBlock $blockFeature)
    {
        $data = $request->validated();
        $data = $this->purifyHtmlKeys($data, ['items.*.content']);
        $items = $data['items'] ?? [];
        $oldItems = $blockFeature->items ?? [];

        // Set is_active (toggle sends '1' when checked, '0' when unchecked)
        $data['is_active'] = $request->input('is_active', '0') === '1';

        // Preserve existing images for each item
        foreach ($items as $index => $item) {
            // If old item exists and has an image, preserve it
            if (isset($oldItems[$index]['image'])) {
                $items[$index]['image'] = $oldItems[$index]['image'];
            }
        }

        // Handle new image uploads for each item
        if ($request->hasFile('item_images')) {
            foreach ($request->file('item_images') as $index => $image) {
                if ($image) {
                    // Delete old image only when uploading a new one
                    if (isset($oldItems[$index]['image'])) {
                        $this->deleteImage($oldItems[$index]['image']);
                    }
                    // Upload new image
                    $items[$index]['image'] = $this->uploadImage($image, 'feature-blocks');
                }
            }
        }

        $data['items'] = $items;
        $blockFeature->update($data);

        return redirect()
            ->route('admin.content.block-feature.index')
            ->with('success', 'Feature Block updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeatureBlock $blockFeature)
    {
        // Delete all item images
        if ($blockFeature->items) {
            foreach ($blockFeature->items as $item) {
                if (isset($item['image'])) {
                    $this->deleteImage($item['image']);
                }
            }
        }

        $blockFeature->delete();

        return redirect()
            ->route('admin.content.block-feature.index')
            ->with('success', 'Feature Block deleted successfully.');
    }

    /**
     * Update sort order
     */
    public function updateOrder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $id) {
            FeatureBlock::where('id', $id)->update(['sort_order' => $index]);
        }

        FeatureBlock::clearCache();

        return response()->json(['success' => true]);
    }
}
