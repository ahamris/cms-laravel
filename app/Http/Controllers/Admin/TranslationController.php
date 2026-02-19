<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class TranslationController extends AdminBaseController
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        parent::__construct();
        $this->translationService = $translationService;
    }

    /**
     * Display a listing of translations.
     */
    public function index(Request $request): View
    {
        $query = Translation::query();

        // Filter by locale
        if ($request->filled('locale')) {
            $query->forLocale($request->locale);
        }

        // Filter by group
        if ($request->filled('group')) {
            $query->forGroup($request->group);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('translation_key', 'like', "%{$search}%")
                  ->orWhere('translation_value', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $translations = $query->orderBy('locale')
                             ->orderBy('group_name')
                             ->orderBy('translation_key')
                             ->paginate(20);

        // Get available locales and groups for filters
        $locales = Translation::distinct('locale')->pluck('locale')->sort();
        $groups = Translation::distinct('group_name')->whereNotNull('group_name')->pluck('group_name')->sort();

        return view('admin.translations.index', compact('translations', 'locales', 'groups'));
    }

    /**
     * Show the form for creating a new translation.
     */
    public function create(): View
    {
        $locales = Translation::distinct('locale')->pluck('locale')->sort();
        $groups = Translation::distinct('group_name')->whereNotNull('group_name')->pluck('group_name')->sort();

        return view('admin.translations.create', compact('locales', 'groups'));
    }

    /**
     * Store a newly created translation.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'translation_key' => 'required|string|max:255',
            'locale' => 'required|string|max:10',
            'translation_value' => 'required|string',
            'group_name' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        // Check for duplicate key in same locale and group
        $exists = Translation::where('translation_key', $validated['translation_key'])
                            ->where('locale', $validated['locale'])
                            ->where('group_name', $validated['group_name'] ?? null)
                            ->exists();

        if ($exists) {
            return back()->withErrors([
                'translation_key' => 'A translation with this key already exists for the selected locale and group.'
            ])->withInput();
        }

        Translation::create($validated);

        return redirect()->route('admin.translations.index')
            ->with('success', 'Translation created successfully.');
    }

    /**
     * Show the form for editing the specified translation.
     */
    public function edit(Translation $translation): View
    {
        $locales = Translation::distinct('locale')->pluck('locale')->sort();
        $groups = Translation::distinct('group_name')->whereNotNull('group_name')->pluck('group_name')->sort();

        return view('admin.translations.edit', compact('translation', 'locales', 'groups'));
    }

    /**
     * Update the specified translation.
     */
    public function update(Request $request, Translation $translation): RedirectResponse
    {
        $validated = $request->validate([
            'translation_key' => 'required|string|max:255',
            'locale' => 'required|string|max:10',
            'translation_value' => 'required|string',
            'group_name' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        // Check for duplicate key in same locale and group (excluding current translation)
        $exists = Translation::where('translation_key', $validated['translation_key'])
                            ->where('locale', $validated['locale'])
                            ->where('group_name', $validated['group_name'] ?? null)
                            ->where('id', '!=', $translation->id)
                            ->exists();

        if ($exists) {
            return back()->withErrors([
                'translation_key' => 'A translation with this key already exists for the selected locale and group.'
            ])->withInput();
        }

        $translation->update($validated);

        return redirect()->route('admin.translations.index')
            ->with('success', 'Translation updated successfully.');
    }

    /**
     * Remove the specified translation.
     */
    public function destroy(Translation $translation): RedirectResponse
    {
        $translation->delete();

        return redirect()->route('admin.translations.index')
            ->with('success', 'Translation deleted successfully.');
    }

    /**
     * Toggle active status of translation.
     */
    public function toggleActive(Translation $translation): JsonResponse
    {
        $translation->update(['is_active' => !$translation->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $translation->is_active,
            'message' => $translation->is_active ? 'Translation activated.' : 'Translation deactivated.'
        ]);
    }

    /**
     * Bulk delete translations.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:translations,id'
        ]);

        $count = Translation::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => "{$count} translations deleted successfully."
        ]);
    }

    /**
     * Clear translation cache.
     */
    public function clearCache(Request $request): JsonResponse
    {
        $locale = $request->input('locale');
        
        $this->translationService->clearCache($locale);

        $message = $locale 
            ? "Translation cache cleared for locale: {$locale}" 
            : 'All translation caches cleared successfully.';

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Load translations to cache.
     */
    public function loadCache(Request $request): JsonResponse
    {
        $locale = $request->input('locale');
        
        $this->translationService->loadToCache($locale);

        $message = $locale 
            ? "Translations loaded to cache for locale: {$locale}" 
            : 'All translations loaded to cache successfully.';

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Import translations from file.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:json,csv',
            'locale' => 'required|string|max:10',
            'group_name' => 'nullable|string|max:100',
            'overwrite' => 'boolean'
        ]);

        $file = $request->file('file');
        $locale = $request->input('locale');
        $group = $request->input('group_name');
        $overwrite = $request->boolean('overwrite');

        try {
            $content = file_get_contents($file->getPathname());
            $data = [];

            if ($file->getClientOriginalExtension() === 'json') {
                $data = json_decode($content, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Invalid JSON format');
                }
            } elseif ($file->getClientOriginalExtension() === 'csv') {
                $lines = str_getcsv($content, "\n");
                foreach ($lines as $line) {
                    $row = str_getcsv($line);
                    if (count($row) >= 2) {
                        $data[$row[0]] = $row[1];
                    }
                }
            }

            $imported = 0;
            $skipped = 0;

            foreach ($data as $key => $value) {
                $exists = Translation::where('translation_key', $key)
                                   ->where('locale', $locale)
                                   ->where('group_name', $group)
                                   ->exists();

                if ($exists && !$overwrite) {
                    $skipped++;
                    continue;
                }

                Translation::updateOrCreate(
                    [
                        'translation_key' => $key,
                        'locale' => $locale,
                        'group_name' => $group,
                    ],
                    [
                        'translation_value' => $value,
                        'is_active' => true,
                    ]
                );

                $imported++;
            }

            $message = "Import completed. {$imported} translations imported";
            if ($skipped > 0) {
                $message .= ", {$skipped} skipped (already exist)";
            }

            return redirect()->route('admin.translations.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->route('admin.translations.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Export translations.
     */
    public function runImportCommand(): RedirectResponse
    {
        try {
            
            Artisan::call('translations:import');

            return redirect()->route('admin.translations.index')
                ->with('success', 'Translations imported successfully from language files.');

        } catch (\Exception $e) {
            return redirect()->route('admin.translations.index')
                ->with('error', 'Import command failed: ' . $e->getMessage());
        }
    }

    /**
     * Export translations.
     */
    public function export(Request $request)
    {
        $request->validate([
            'locale' => 'nullable|string|max:10',
            'group' => 'nullable|string|max:100',
            'format' => 'required|in:json,csv'
        ]);

        $locale = $request->input('locale');
        $group = $request->input('group');
        $format = $request->input('format');

        $query = Translation::active();

        if ($locale) {
            $query->forLocale($locale);
        }

        if ($group) {
            $query->forGroup($group);
        }

        $translations = $query->pluck('translation_value', 'translation_key')->toArray();

        $filename = 'translations';
        if ($locale) $filename .= "_{$locale}";
        if ($group) $filename .= "_{$group}";
        $filename .= '_' . date('Y-m-d_H-i-s');

        if ($format === 'json') {
            $content = json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $filename .= '.json';
            $mimeType = 'application/json';
        } else {
            $content = "key,value\n";
            foreach ($translations as $key => $value) {
                $content .= '"' . str_replace('"', '""', $key) . '","' . str_replace('"', '""', $value) . "\"\n";
            }
            $filename .= '.csv';
            $mimeType = 'text/csv';
        }

        return response($content)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
