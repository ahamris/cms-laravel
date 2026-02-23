<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\SolutionRequest;
use App\Models\Module;
use App\Models\Solution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SolutionController extends AdminBaseController
{
    /**
     * Display a listing of solutions.
     */
    public function index(): View
    {
        $solutions = Solution::ordered()->paginate(20);

        return view('admin.solution.index', compact('solutions'));
    }

    /**
     * Show the form for creating a new solution.
     */
    public function create(): View
    {
        $modules = Module::active()->ordered()->get();

        return view('admin.solution.create', compact('modules'));
    }

    /**
     * Store a newly created solution.
     */
    public function store(SolutionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

        // Always set list_items from request (empty array when all key features removed)
        $raw = $request->input('list_items', []);
        $listItems = is_array($raw) ? $raw : [];
        $listItems = array_values(array_filter(array_map(function ($item) {
            return is_string($item) ? trim($item) : '';
        }, $listItems), fn ($v) => $v !== ''));
        $validated['list_items'] = $listItems;

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('solutions', 'public');
        }

        // Set boolean fields (toggle sends '1' when checked, '0' when unchecked)
        $validated['is_active'] = $request->input('is_active', '0') === '1';
        $validated['show_buttons'] = $request->input('show_buttons', '0') === '1';
        $validated['show_cta'] = $request->input('show_cta', '0') === '1';
        $validated['show_news_articles'] = $request->input('show_news_articles', '0') === '1';
        $validated['show_modules_header'] = $request->input('show_modules_header', '0') === '1';

        $solution = Solution::create($validated);

        // Sync modules
        if ($request->has('modules')) {
            $solution->modules()->sync($request->input('modules', []));
        }

        // Log activity
        $this->logCreate($solution);

        // Check if user wants to continue editing
        if ($request->input('action') === 'save_and_stay') {
            return redirect()->route('admin.solution.edit', $solution)
                ->with('success', 'Solution created successfully! You can continue editing.');
        }

        return redirect()->route('admin.solution.index')
            ->with('success', 'Solution created successfully.');
    }

    /**
     * Display the specified solution.
     */
    public function show(Solution $solution): View
    {
        return view('admin.solution.show', compact('solution'));
    }

    /**
     * Show the form for editing the specified solution.
     */
    public function edit(Solution $solution): View
    {
        $modules = Module::active()->ordered()->get();

        return view('admin.solution.edit', compact('solution', 'modules'));
    }

    /**
     * Update the specified solution.
     */
    public function update(SolutionRequest $request, Solution $solution): RedirectResponse
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

        // Always set list_items from request (empty array when all key features removed)
        $raw = $request->input('list_items', []);
        $listItems = is_array($raw) ? $raw : [];
        $listItems = array_values(array_filter(array_map(function ($item) {
            return is_string($item) ? trim($item) : '';
        }, $listItems), fn ($v) => $v !== ''));
        $validated['list_items'] = $listItems;

        // Handle image deletion
        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            // Delete old image from storage if exists
            if ($solution->image) {
                \Storage::disk('public')->delete($solution->image);
            }
            // Set image to null in database
            $validated['image'] = null;
        }
        // Handle image upload
        elseif ($request->hasFile('image')) {
            // Delete old image if exists
            if ($solution->image) {
                \Storage::disk('public')->delete($solution->image);
            }
            $validated['image'] = $request->file('image')->store('solutions', 'public');
        }

        // Set boolean fields (toggle sends '1' when checked, '0' when unchecked)
        $validated['is_active'] = $request->input('is_active', '0') === '1';
        $validated['show_buttons'] = $request->input('show_buttons', '0') === '1';
        $validated['show_cta'] = $request->input('show_cta', '0') === '1';
        $validated['show_news_articles'] = $request->input('show_news_articles', '0') === '1';
        $validated['show_modules_header'] = $request->input('show_modules_header', '0') === '1';

        $solution->update($validated);

        // Sync modules
        if ($request->has('modules')) {
            $solution->modules()->sync($request->input('modules', []));
        } else {
            $solution->modules()->sync([]);
        }

        // Log activity
        $this->logUpdate($solution);

        // Check if user wants to continue editing
        if ($request->input('action') === 'save_and_stay') {
            return redirect()->route('admin.solution.edit', $solution)
                ->with('success', 'Solution updated successfully! You can continue editing.');
        }

        return redirect()->route('admin.solution.index')
            ->with('success', 'Solution updated successfully.');
    }

    /**
     * Remove the specified solution.
     */
    public function destroy(Solution $solution): RedirectResponse
    {
        // Log activity before deletion
        $this->logDelete($solution);

        // Delete image if existsociated image
        if ($solution->image) {
            \Storage::disk('public')->delete($solution->image);
        }

        // Delete associated modules
        $solution->modules()->detach();

        $solution->delete();

        return redirect()->route('admin.solution.index')
            ->with('success', 'Solution deleted successfully.');
    }

    /**
     * Toggle active status of solution.
     */
    public function toggleActive(Solution $solution): JsonResponse
    {
        $solution->update(['is_active' => !$solution->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $solution->is_active,
            'message' => $solution->is_active ? 'Solution activated.' : 'Solution deactivated.'
        ]);
    }

    /**
     * Update sort order of solutions.
     */
    public function updateOrder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $id) {
            Solution::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
