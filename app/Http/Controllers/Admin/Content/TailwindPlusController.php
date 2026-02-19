<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\TailwindPlus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TailwindPlusController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = TailwindPlus::query();

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by component group
        if ($request->filled('component_group')) {
            $query->where('component_group', $request->component_group);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('component_name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('component_group', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $components = $query->orderBy('category')
            ->orderBy('component_group')
            ->orderBy('component_name')
            ->paginate(50);

        // Get unique values for filters
        $categories = TailwindPlus::distinct()->pluck('category')->sort()->values();
        $componentGroups = TailwindPlus::distinct()->pluck('component_group')->sort()->values();

        return view('admin.content.tailwind-plus.index', compact('components', 'categories', 'componentGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $componentGroups = TailwindPlus::whereNotNull('component_group')
            ->distinct()
            ->pluck('component_group')
            ->sort()
            ->values();

        return view('admin.content.tailwind-plus.create', compact('componentGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'component_group' => 'nullable|string|max:255',
            'component_name' => 'required|string|max:255',
            'code' => 'required|string',
            'preview' => 'nullable|string',
            'version' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        TailwindPlus::create($validated);

        return redirect()->route('admin.content.tailwind-plus.index')
            ->with('success', 'Component created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TailwindPlus $tailwindPlus): View
    {
        return view('admin.content.tailwind-plus.show', compact('tailwindPlus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TailwindPlus $tailwindPlus): View
    {
        $componentGroups = TailwindPlus::whereNotNull('component_group')
            ->distinct()
            ->pluck('component_group')
            ->sort()
            ->values();

        return view('admin.content.tailwind-plus.edit', compact('tailwindPlus', 'componentGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TailwindPlus $tailwindPlus): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'component_group' => 'nullable|string|max:255',
            'component_name' => 'required|string|max:255',
            'code' => 'required|string',
            'preview' => 'nullable|string',
            'version' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $tailwindPlus->update($validated);

        return redirect()->route('admin.content.tailwind-plus.index')
            ->with('success', 'Component updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TailwindPlus $tailwindPlus): RedirectResponse
    {
        $tailwindPlus->delete();

        return redirect()->route('admin.content.tailwind-plus.index')
            ->with('success', 'Component deleted successfully!');
    }

    /**
     * Preview component in iframe
     */
    public function preview(TailwindPlus $tailwindPlus): View
    {
        return view('admin.content.tailwind-plus.preview', compact('tailwindPlus'));
    }

    /**
     * Import component from uploaded .html or .blade.php file.
     */
    public function importFromFile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:2048'],
            'category' => ['required', 'string', 'max:255'],
            'component_group' => ['nullable', 'string', 'max:255'],
            'component_name' => ['required', 'string', 'max:255'],
            'version' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $file = $request->file('file');
        $extension = Str::lower($file->getClientOriginalExtension());

        if (! in_array($extension, ['html', 'php'], true)) {
            return redirect()->route('admin.content.tailwind-plus.index')
                ->with('error', 'Only .html or .blade.php files are allowed.');
        }

        $content = file_get_contents($file->getRealPath());
        if ($content === false) {
            return redirect()->route('admin.content.tailwind-plus.index')
                ->with('error', 'Could not read the file.');
        }

        TailwindPlus::create([
            'category' => $validated['category'],
            'component_group' => $validated['component_group'],
            'component_name' => $validated['component_name'],
            'code' => $content,
            'preview' => null,
            'version' => (int) $validated['version'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.content.tailwind-plus.index')
            ->with('success', 'Component imported successfully from file.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(TailwindPlus $tailwindPlus): JsonResponse
    {
        $tailwindPlus->update(['is_active' => ! $tailwindPlus->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $tailwindPlus->is_active,
            'message' => $tailwindPlus->is_active ? 'Component activated successfully!' : 'Component deactivated successfully!',
        ]);
    }
}
