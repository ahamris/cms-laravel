<?php

namespace App\Http\Controllers\Admin;

use App\Models\FooterLink;
use App\Models\MegaMenuItem;
use App\Models\Setting;
use App\Services\TailwindPlusComponentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class FooterLinkController extends AdminBaseController
{
    use \App\Traits\HandlesNavigationLinks;
    /**
     * Display a listing of the resource.
     */
    public function index(TailwindPlusComponentService $componentService)
    {
        $links = FooterLink::orderBy('column')->orderBy('order')->get()->groupBy('column');

        // Get footer components for selection
        $footerComponents = $componentService->getFooterComponents();
        $selectedFooterComponentId = Setting::getValue('site_footer_component_id');
        $selectedFooterLayoutType = Setting::getValue('site_footer_layout_type');

        return view('admin.footer_links.index', compact('links', 'footerComponents', 'selectedFooterComponentId', 'selectedFooterLayoutType'));
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
            if (!is_array($itemIds)) {
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
        $footerLink->update(['is_active' => !$footerLink->is_active]);

        return response()->json(['success' => true, 'is_active' => $footerLink->is_active]);
    }

    /**
     * Update the selected footer component.
     */
    public function updateFooterComponent(Request $request)
    {
        $validated = $request->validate([
            'footer_component_id' => 'nullable|integer|exists:tailwind_plus,id',
            'footer_layout_type' => 'nullable|string|in:,full-width,container,max-w-2xl,max-w-4xl,max-w-6xl,max-w-7xl',
            'footer_cta_title' => 'nullable|string|max:255',
            'footer_cta_subtitle' => 'nullable|string|max:500',
            'footer_cta_description' => 'nullable|string|max:1000',
            'footer_cta_button_text' => 'nullable|string|max:255',
            'footer_cta_button_url' => 'nullable|string|max:500',
        ]);

        $componentId = $validated['footer_component_id'] ?? null;
        $footerLayoutType = $validated['footer_layout_type'] ?? null;
        $footerCtaTitle = $validated['footer_cta_title'] ?? '';
        $footerCtaSubtitle = $validated['footer_cta_subtitle'] ?? '';
        $footerCtaDescription = $validated['footer_cta_description'] ?? '';
        $footerCtaButtonText = $validated['footer_cta_button_text'] ?? 'Get started';
        $footerCtaButtonUrl = $validated['footer_cta_button_url'] ?? '#';

        Setting::setValue('site_footer_component_id', $componentId);
        Setting::setValue('site_footer_layout_type', $footerLayoutType);
        Setting::setValue('footer_cta_title', $footerCtaTitle);
        Setting::setValue('footer_cta_subtitle', $footerCtaSubtitle);
        Setting::setValue('footer_cta_description', $footerCtaDescription);
        Setting::setValue('footer_cta_button_text', $footerCtaButtonText);
        Setting::setValue('footer_cta_button_url', $footerCtaButtonUrl);

        // Clear cache
        Cache::forget('settings'); // Clear the main settings cache used by get_setting() helper
        Cache::forget('settings.site_footer_component_id');
        Cache::forget('settings.site_footer_layout_type');
        Cache::forget('settings.footer_cta_title');
        Cache::forget('settings.footer_cta_subtitle');
        Cache::forget('settings.footer_cta_description');
        Cache::forget('settings.footer_cta_button_text');
        Cache::forget('settings.footer_cta_button_url');
        FooterLink::getCached(); // This will clear footer links cache on next access

        return redirect()->route('admin.settings.footer-links.index')
            ->with('success', 'Footer component updated successfully.');
    }

    /**
     * Update CTA settings for footer components.
     */
    public function updateCtaSettings(Request $request)
    {
        $validated = $request->validate([
            'cta_title' => 'nullable|string|max:255',
            'cta_subtitle' => 'nullable|string|max:500',
            'cta_description' => 'nullable|string|max:1000',
            'cta_button_text' => 'nullable|string|max:255',
            'cta_button_url' => 'nullable|string|max:500',
        ]);

        Setting::setValue('footer_cta_title', $validated['cta_title'] ?? '');
        Setting::setValue('footer_cta_subtitle', $validated['cta_subtitle'] ?? '');
        Setting::setValue('footer_cta_description', $validated['cta_description'] ?? '');
        Setting::setValue('footer_cta_button_text', $validated['cta_button_text'] ?? '');
        Setting::setValue('footer_cta_button_url', $validated['cta_button_url'] ?? '');

        // Clear cache
        Cache::forget('settings'); // Clear the main settings cache used by get_setting() helper
        Cache::forget('settings.footer_cta_title');
        Cache::forget('settings.footer_cta_subtitle');
        Cache::forget('settings.footer_cta_description');
        Cache::forget('settings.footer_cta_button_text');
        Cache::forget('settings.footer_cta_button_url');

        return redirect()->route('admin.settings.footer-links.index')
            ->with('success', 'CTA settings updated successfully.');
    }
}


