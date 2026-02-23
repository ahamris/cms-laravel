<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\PresenterRequest;
use App\Models\Presenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PresenterController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     * Table is rendered by Livewire admin.table component.
     */
    public function index()
    {
        return view('admin.presenter.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.presenter.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PresenterRequest $request)
    {
        $data = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('presenters', 'public');
        }

        Presenter::create($data);

        return redirect()
            ->route('admin.content.presenter.index')
            ->with('success', 'Presenter succesvol aangemaakt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Presenter $presenter)
    {
        $presenter->load(['liveSessions' => function ($query) {
            $query->orderBy('session_date', 'desc');
        }]);

        return view('admin.presenter.show', compact('presenter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presenter $presenter)
    {
        return view('admin.presenter.edit', compact('presenter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PresenterRequest $request, Presenter $presenter)
    {
        $data = $request->validated();

        // Handle avatar deletion
        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            // Delete old avatar from storage if exists
            if ($presenter->avatar) {
                Storage::disk('public')->delete($presenter->avatar);
            }
            // Set avatar to null in database
            $data['avatar'] = null;
        }
        // Handle avatar upload
        elseif ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($presenter->avatar) {
                Storage::disk('public')->delete($presenter->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('presenters', 'public');
        }

        $presenter->update($data);

        return redirect()
            ->route('admin.content.presenter.index')
            ->with('success', 'Presenter succesvol bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presenter $presenter)
    {
        // Delete avatar if exists
        if ($presenter->avatar) {
            Storage::disk('public')->delete($presenter->avatar);
        }

        $presenter->delete();

        return redirect()
            ->route('admin.content.presenter.index')
            ->with('success', 'Presenter succesvol verwijderd.');
    }

    /**
     * Update the sort order of presenters.
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:presenters,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            Presenter::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Toggle the active status of a presenter.
     */
    public function toggleStatus(Presenter $presenter)
    {
        $presenter->update(['is_active' => !$presenter->is_active]);

        $status = $presenter->is_active ? 'geactiveerd' : 'gedeactiveerd';
        
        return redirect()
            ->back()
            ->with('success', "Presenter succesvol {$status}.");
    }

    /**
     * Remove avatar from presenter.
     */
    public function removeAvatar(Presenter $presenter)
    {
        if ($presenter->avatar) {
            Storage::disk('public')->delete($presenter->avatar);
            $presenter->update(['avatar' => null]);
        }

        return redirect()
            ->back()
            ->with('success', 'Avatar succesvol verwijderd.');
    }
}
