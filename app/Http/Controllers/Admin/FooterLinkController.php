<?php

namespace App\Http\Controllers\Admin;

use App\Models\FooterLink;
use App\Models\MegaMenuItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class FooterLinkController extends AdminBaseController
{
    use \App\Traits\HandlesNavigationLinks;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $links = FooterLink::orderBy('column')->orderBy('order')->get()->groupBy('column');

        return view('admin.footer_links.index', compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Use MegaMenuItem business logic for consistency
        $availableRoutes = MegaMenuItem::possibleMenuItems();
        $systemContent = MegaMenuItem::getSystemContent();

        return view('admin.footer_links.create', compact('availableRoutes', 'systemContent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'link_type' => 'nullable|string|in:predefined,custom,system',
            'column' => 'required|integer|in:1,2,3,4',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Process URL based on link type (reuse MegaMenu logic)
        $data = $this->processUrlByLinkType($request, $validator->validated());
        unset($data['link_type']); // Remove link_type as it's not a database field

        FooterLink::create([
            'title' => $data['title'],
            'url' => $data['url'],
            'column' => $data['column'],
            'is_active' => $request->boolean('is_active', true),
            'order' => FooterLink::where('column', $data['column'])->max('order') + 1,
        ]);

        return redirect()->route('admin.settings.footer-links.index')->with('success', 'Footer link created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FooterLink $footerLink)
    {
        // Use MegaMenuItem business logic for consistency
        $availableRoutes = MegaMenuItem::possibleMenuItems();
        $systemContent = MegaMenuItem::getSystemContent();

        return view('admin.footer_links.edit', compact('footerLink', 'availableRoutes', 'systemContent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FooterLink $footerLink)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'link_type' => 'nullable|string|in:predefined,custom,system',
            'column' => 'required|integer|in:1,2,3,4',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Process URL based on link type (reuse MegaMenu logic)
        $data = $this->processUrlByLinkType($request, $validator->validated());
        unset($data['link_type']); // Remove link_type as it's not a database field

        $footerLink->update([
            'title' => $data['title'],
            'url' => $data['url'],
            'column' => $data['column'],
            'is_active' => $request->boolean('is_active', $footerLink->is_active),
        ]);

        return redirect()->route('admin.settings.footer-links.index')->with('success', 'Footer link updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FooterLink $footerLink)
    {
        $footerLink->delete();

        return redirect()->route('admin.settings.footer-links.index')->with('success', 'Footer link deleted successfully.');
    }

    /**
     * Update the order of the links.
     */
    public function updateOrder(Request $request)
    {
        $orderData = $request->input('order');

        foreach ($orderData as $column => $itemIds) {
            if (! is_array($itemIds)) {
                continue;
            }
            foreach ($itemIds as $index => $itemId) {
                $link = FooterLink::find($itemId);
                if ($link) {
                    $link->order = $index;
                    $link->column = $column;
                    $link->save(); // This will trigger the 'updated' model event
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Footer links reordered successfully.']);
    }

    /**
     * Toggle the active status of a link.
     */
    public function toggleActive(FooterLink $footerLink)
    {
        $footerLink->update(['is_active' => ! $footerLink->is_active]);

        return response()->json(['success' => true, 'is_active' => $footerLink->is_active]);
    }

    
}
