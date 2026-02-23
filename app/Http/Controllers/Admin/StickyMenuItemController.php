<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\StickyMenuItemRequest;
use App\Models\StickyMenuItem;
use Illuminate\Http\Request;

class StickyMenuItemController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stickyMenuItems = StickyMenuItem::ordered()->paginate(15);

        return view('admin.sticky-menu-item.index', compact('stickyMenuItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sticky-menu-item.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StickyMenuItemRequest $request)
    {
        StickyMenuItem::create($request->validated());

        return redirect()
            ->route('admin.content.sticky-menu-item.index')
            ->with('success', 'Sticky menu item succesvol aangemaakt.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StickyMenuItem $stickyMenuItem)
    {
        return view('admin.sticky-menu-item.edit', compact('stickyMenuItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StickyMenuItemRequest $request, StickyMenuItem $stickyMenuItem)
    {
        $stickyMenuItem->update($request->validated());

        return redirect()
            ->route('admin.content.sticky-menu-item.index')
            ->with('success', 'Sticky menu item succesvol bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StickyMenuItem $stickyMenuItem)
    {
        $stickyMenuItem->delete();

        return redirect()
            ->route('admin.content.sticky-menu-item.index')
            ->with('success', 'Sticky menu item succesvol verwijderd.');
    }

    /**
     * Update the sort order of sticky menu items.
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:sticky_menu_items,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            StickyMenuItem::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Toggle the active status of a sticky menu item.
     */
    public function toggleStatus(StickyMenuItem $stickyMenuItem)
    {
        $stickyMenuItem->update(['is_active' => !$stickyMenuItem->is_active]);

        $status = $stickyMenuItem->is_active ? 'geactiveerd' : 'gedeactiveerd';

        return redirect()
            ->back()
            ->with('success', "Sticky menu item succesvol {$status}.");
    }
}
