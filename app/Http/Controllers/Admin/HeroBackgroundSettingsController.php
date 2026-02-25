<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HeroBackgroundSettingsController extends AdminBaseController
{
    /** Hero backgrounds for list/static pages only (no detail pages). Aligned with API endpoints. */
    private const HERO_KEYS = [
        'hero_background_contact',
        'hero_background_blog',
        'hero_background_solutions_index',
        'hero_background_modules_index',
        'hero_background_docs',
        'hero_background_academy',
        'hero_background_trial',
    ];

    /**
     * Display the hero background settings page.
     */
    public function index()
    {
        return view('admin.settings.hero-backgrounds.index');
    }

    /**
     * Update hero background image settings.
     */
    public function update(Request $request)
    {
        $rules = [];
        foreach (self::HERO_KEYS as $key) {
            $rules[$key] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
            $rules["remove_{$key}"] = 'nullable|boolean';
        }
        $request->validate($rules);

        try {
            foreach (self::HERO_KEYS as $key) {
                if ($request->hasFile($key)) {
                    $this->handleFileUpload($request, $key);
                }
                if ($request->has("remove_{$key}")) {
                    $this->deleteFileSetting($key);
                }
            }

            $this->clearCaches();
            $this->logSettingsUpdate('Hero background settings');

            return redirect()->back()->with('status', 'hero-settings-updated');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])
                ->withInput();
        }
    }

    private function handleFileUpload(Request $request, string $fieldName): void
    {
        $file = $request->file($fieldName);

        $oldSetting = Setting::where('key', $fieldName)->first();
        if ($oldSetting && ! empty($oldSetting->value) && trim($oldSetting->value) !== '') {
            if (Storage::disk('public')->exists($oldSetting->value)) {
                Storage::disk('public')->delete($oldSetting->value);
            }
        }

        $directory = 'hero-backgrounds';
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        if (empty($originalName) || trim($originalName) === '') {
            $originalName = 'upload_' . uniqid('', true) . '.' . ($extension ?: 'jpg');
        }
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);

        $publicPath = storage_path('app/public/' . $directory);
        if (! file_exists($publicPath)) {
            if (! mkdir($publicPath, 0755, true) && ! is_dir($publicPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $publicPath));
            }
        }

        $targetFile = $publicPath . '/' . $filename;
        $success = false;
        if (is_uploaded_file($file->getPathname())) {
            $success = move_uploaded_file($file->getPathname(), $targetFile);
        }
        if (! $success) {
            try {
                $file->move($publicPath, $filename);
                $success = true;
            } catch (\Exception $e) {
                //
            }
        }
        if (! $success && $file->getRealPath() && file_exists($file->getRealPath())) {
            $success = copy($file->getRealPath(), $targetFile);
        }

        if (! $success) {
            throw new \Exception('Unable to store file');
        }

        $this->updateSetting($fieldName, $directory . '/' . $filename);
    }

    private function deleteFileSetting(string $key): void
    {
        $setting = Setting::where('key', $key)->first();
        if ($setting && ! empty($setting->value) && trim($setting->value) !== '') {
            if (! filter_var($setting->value, FILTER_VALIDATE_URL)
                && ! str_starts_with($setting->value, 'assets/')
                && ! str_starts_with($setting->value, 'front/')
                && Storage::disk('public')->exists($setting->value)) {
                Storage::disk('public')->delete($setting->value);
            }
        }
        $this->updateSetting($key, null);
    }

    private function updateSetting(string $key, $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => 'image',
                'group' => 'hero',
                'display_name' => ucfirst(str_replace('_', ' ', $key)),
                'description' => null,
                'order' => array_search($key, self::HERO_KEYS) + 1,
            ]
        );
    }

    private function clearCaches(): void
    {
        Cache::forget('settings');
        foreach (self::HERO_KEYS as $key) {
            Cache::forget("settings.{$key}");
        }
    }
}
