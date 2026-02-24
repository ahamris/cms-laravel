<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\ExternalCodeRequest;
use App\Models\ExternalCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExternalCodeController extends AdminBaseController
{
    /**
     * Display a listing of external codes.
     */
    public function index(): View
    {
        $externalCodes = ExternalCode::ordered()->paginate(20);

        return view('admin.external-code.index', compact('externalCodes'));
    }

    /**
     * Show the form for creating a new external code.
     */
    public function create(): View
    {
        return view('admin.external-code.create');
    }

    /**
     * Store a newly created external code.
     */
    public function store(ExternalCodeRequest $request): RedirectResponse
    {
        try {
            $location = $request->input('injection_location', 'body');
            $data = [
                'name' => $request->input('name'),
                'content' => $request->input('content'),
                'before_header' => $location === 'header',
                'before_body' => $location === 'body',
                'is_active' => $request->boolean('is_active'),
                'sort_order' => (int) $request->input('sort_order', 0),
            ];

            ExternalCode::create($data);

            return redirect()->route('admin.external-code.index')
                ->with('success', 'External code created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create external code: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified external code.
     */
    public function show(ExternalCode $externalCode): View
    {
        return view('admin.external-code.show', compact('externalCode'));
    }

    /**
     * Show the form for editing the specified external code.
     */
    public function edit(ExternalCode $externalCode): View
    {
        return view('admin.external-code.edit', compact('externalCode'));
    }

    /**
     * Update the specified external code.
     */
    public function update(ExternalCodeRequest $request, ExternalCode $externalCode): RedirectResponse
    {
        try {
            $location = $request->input('injection_location', 'body');
            $data = [
                'name' => $request->input('name'),
                'content' => $request->input('content'),
                'before_header' => $location === 'header',
                'before_body' => $location === 'body',
                'is_active' => $request->boolean('is_active'),
                'sort_order' => (int) $request->input('sort_order', 0),
            ];

            $externalCode->update($data);

            return redirect()->route('admin.external-code.index')
                ->with('success', 'External code updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update external code: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified external code.
     */
    public function destroy(ExternalCode $externalCode): RedirectResponse
    {
        $externalCode->delete();

        return redirect()->route('admin.external-code.index')
            ->with('success', 'External code deleted successfully.');
    }

    /**
     * Update sort order of external codes.
     */
    public function updateOrder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $id) {
            ExternalCode::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
