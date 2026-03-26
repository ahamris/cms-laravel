<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GeneralSettingsController extends AdminBaseController
{
    /**
     * Display the general settings page.
     */
    public function index()
    {
        return view('admin.settings.general.index');
    }

    /**
     * Update general settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'copyright_footer' => 'required|string',
            'site_description' => 'nullable|string|max:1000',
            'site_email' => 'required|email|max:255',
            'site_phone' => 'nullable|string|max:50',
            'site_address' => 'nullable|string|max:500',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:20480',
            'site_favicon' => 'nullable|image|mimes:ico,png,gif,jpg,jpeg,webp|max:20480',
            'admin_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:20480',
            'footer_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:20480',
            'meta_title' => 'required|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'google_analytics' => 'nullable|string|max:100',
            'posts_per_page' => 'required|integer|min:1|max:100',
            'default_category' => 'required|integer|min:1',
            'enable_comments' => 'boolean',
            'moderate_comments' => 'boolean',
            'doculoket_sandbox' => 'boolean',
            'map_latitude' => 'nullable|numeric|between:-90,90',
            'map_longitude' => 'nullable|numeric|between:-180,180',
            'map_zoom' => 'nullable|integer|min:1|max:19',
        ]);

        try {
            // Update site settings
            $this->updateSetting('site_name', $request->site_name);
            $this->updateSetting('copyright_footer', $request->copyright_footer);
            $this->updateSetting('site_description', $request->site_description);
            $this->updateSetting('site_email', $request->site_email);
            $this->updateSetting('site_phone', $request->site_phone);
            $this->updateSetting('site_address', $request->site_address);

            // Handle logo upload
            if ($request->hasFile('site_logo')) {
                $this->handleFileUpload($request, 'site_logo');
            }

            // Handle logo removal
            if ($request->has('remove_site_logo')) {
                $this->deleteFileSetting('site_logo');
            }

            // Handle favicon upload
            if ($request->hasFile('site_favicon')) {
                $this->handleFileUpload($request, 'site_favicon');
            }

            // Handle favicon removal
            if ($request->has('remove_site_favicon')) {
                $this->deleteFileSetting('site_favicon');
            }

            // Handle admin logo upload
            if ($request->hasFile('admin_logo')) {
                $this->handleFileUpload($request, 'admin_logo');
            }

            // Handle admin logo removal
            if ($request->has('remove_admin_logo')) {
                $this->deleteFileSetting('admin_logo');
            }

            // Handle footer logo upload
            if ($request->hasFile('footer_logo')) {
                $this->handleFileUpload($request, 'footer_logo');
            }

            // Handle footer logo removal
            if ($request->has('remove_footer_logo')) {
                $this->deleteFileSetting('footer_logo');
            }

            // Update SEO settings
            $this->updateSetting('meta_title', $request->meta_title);
            $this->updateSetting('meta_description', $request->meta_description);
            $this->updateSetting('meta_keywords', $request->meta_keywords);
            $this->updateSetting('google_analytics', $request->google_analytics);

            // Update content settings
            $this->updateSetting('posts_per_page', $request->posts_per_page);
            $this->updateSetting('default_category', $request->default_category);
            $this->updateSetting('enable_comments', $request->has('enable_comments') ? '1' : '0');
            $this->updateSetting('moderate_comments', $request->has('moderate_comments') ? '1' : '0');

            // Update Doculoket settings
            $this->updateSetting('doculoket_sandbox', $request->has('doculoket_sandbox') ? '1' : '0');

            // Update Map settings
            $this->updateSetting('map_latitude', $request->map_latitude);
            $this->updateSetting('map_longitude', $request->map_longitude);
            $this->updateSetting('map_zoom', $request->map_zoom);

            // Clear relevant caches after updating settings
            $this->clearCaches();

            // Log activity
            $this->logSettingsUpdate('General Settings');

            return redirect()->back()->with('status', 'settings-updated');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'An error occurred while updating settings: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Handle file upload for logo and favicon.
     */
    private function handleFileUpload(Request $request, string $fieldName): void
    {
        $file = $request->file($fieldName);
        // Delete old file if exists
        $oldSetting = Setting::where('key', $fieldName)->first();
        if ($oldSetting && ! empty($oldSetting->value) && trim($oldSetting->value) !== '') {
            if (Storage::disk('public')->exists($oldSetting->value)) {
                Storage::disk('public')->delete($oldSetting->value);
            }
        }

        // Store new file
        $directory = match ($fieldName) {
            'site_logo' => 'logos',
            'admin_logo' => 'logos',
            'footer_logo' => 'logos',
            'site_favicon' => 'favicons',
            default => 'uploads',
        };
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        // Ensure we have a valid filename
        if (empty($originalName) || trim($originalName) === '') {
            $originalName = 'upload_'.uniqid('', true).'.'.($extension ?: 'jpg');
        }

        $filename = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);

        // Final validation
        if (empty($directory) || empty($filename)) {
            throw new \Exception("Invalid file path: directory='{$directory}', filename='{$filename}'");
        }

        // Debug the file object thoroughly

        // Try different file access methods
        try {
            $publicPath = storage_path('app/public/'.$directory);

            // Create directory if it doesn't exist
            if (! file_exists($publicPath)) {
                if (! mkdir($publicPath, 0755, true) && ! is_dir($publicPath)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $publicPath));
                }
            }

            $targetFile = $publicPath.'/'.$filename;

            // Try multiple approaches to get file content
            $success = false;

            // Method 1: Use move_uploaded_file if it's a real upload
            if (is_uploaded_file($file->getPathname())) {
                if (move_uploaded_file($file->getPathname(), $targetFile)) {
                    $success = true;
                }
            }

            // Method 2: Try Laravel's move method
            if (! $success) {
                try {
                    $file->move($publicPath, $filename);
                    $success = true;
                } catch (\Exception $e) {
                }
            }

            // Method 3: Copy file if other methods fail
            if (! $success && $file->getRealPath() && file_exists($file->getRealPath())) {
                if (copy($file->getRealPath(), $targetFile)) {
                    $success = true;
                }
            }

            if ($success) {
                $path = $directory.'/'.$filename;
            } else {
                throw new \Exception('All file storage methods failed');
            }

        } catch (\Exception $e) {
            throw new \Exception('Unable to store file: '.$e->getMessage());
        }

        // Update setting
        $this->updateSetting($fieldName, $path);
    }

    /**
     * Delete a file setting and remove the file from storage.
     */
    private function deleteFileSetting(string $key): void
    {
        $setting = Setting::where('key', $key)->first();

        if ($setting) {
            // Delete file from storage if it exists and is a storage path
            if (! empty($setting->value) && trim($setting->value) !== '') {
                // Only delete from storage if it looks like a storage path (not a full URL or public path)
                if (! filter_var($setting->value, FILTER_VALIDATE_URL) &&
                    ! str_starts_with($setting->value, 'assets/') &&
                    ! str_starts_with($setting->value, 'front/')) {
                    if (Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }
                }
            }
            // Always set the setting to null to allow removal
            $this->updateSetting($key, null);
        }
    }

    /**
     * Update a single setting.
     */
    private function updateSetting(string $key, $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $this->getSettingType($key),
                'group' => $this->getSettingGroup($key),
                'display_name' => $this->getDisplayName($key),
                'description' => $this->getDescription($key),
                'order' => $this->getOrder($key),
            ]
        );
    }

    /**
     * Get setting type based on key.
     */
    private function getSettingType(string $key): string
    {
        $types = [
            'site_name' => 'text',
            'copyright_footer' => 'text',
            'site_description' => 'textarea',
            'site_email' => 'email',
            'site_phone' => 'text',
            'site_address' => 'textarea',
            'site_logo' => 'file',
            'site_favicon' => 'file',
            'admin_logo' => 'file',
            'footer_logo' => 'file',
            'meta_title' => 'text',
            'meta_description' => 'textarea',
            'meta_keywords' => 'textarea',
            'google_analytics' => 'text',
            'posts_per_page' => 'number',
            'default_category' => 'number',
            'enable_comments' => 'boolean',
            'moderate_comments' => 'boolean',
            'doculoket_sandbox' => 'boolean',
            'map_latitude' => 'text',
            'map_longitude' => 'text',
            'map_zoom' => 'number',
        ];

        return $types[$key] ?? 'text';
    }

    /**
     * Get setting group based on key.
     */
    private function getSettingGroup(string $key): string
    {
        if (str_starts_with($key, 'site_') || str_starts_with($key, 'admin_')) {
            return 'site';
        }

        if (str_starts_with($key, 'meta_') || $key === 'google_analytics') {
            return 'seo';
        }

        if (in_array($key, ['posts_per_page', 'default_category', 'enable_comments', 'moderate_comments'])) {
            return 'content';
        }

        if ($key === 'doculoket_sandbox') {
            return 'doculoket';
        }

        if (str_starts_with($key, 'map_')) {
            return 'map';
        }

        return 'general';
    }

    /**
     * Get display name based on key.
     */
    private function getDisplayName(string $key): string
    {
        $names = [
            'site_name' => 'Site Name',
            'copyright_footer' => 'Copyright Footer',
            'site_description' => 'Site Description',
            'site_email' => 'Site Email',
            'site_phone' => 'Site Phone',
            'site_address' => 'Site Address',
            'site_logo' => 'Site Logo',
            'site_favicon' => 'Site Favicon',
            'admin_logo' => 'Admin Logo',
            'footer_logo' => 'Footer Logo',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'google_analytics' => 'Google Analytics ID',
            'posts_per_page' => 'Posts Per Page',
            'default_category' => 'Default Category',
            'enable_comments' => 'Enable Comments',
            'moderate_comments' => 'Moderate Comments',
            'doculoket_sandbox' => 'Doculoket Sandbox',
            'map_latitude' => 'Map Latitude',
            'map_longitude' => 'Map Longitude',
            'map_zoom' => 'Map Zoom Level',
        ];

        return $names[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }

    /**
     * Get description based on key.
     */
    private function getDescription(string $key): ?string
    {
        $descriptions = [
            'site_name' => 'The name of your website',
            'copyright_footer' => 'The copyright text for the footer',
            'site_description' => 'A brief description of your website',
            'site_email' => 'The primary email address for your website',
            'site_phone' => 'The primary phone number for your website',
            'site_address' => 'The physical address of your organization',
            'site_logo' => 'Upload your website logo (JPEG, PNG, JPG, GIF, SVG, WebP - Max: 20MB)',
            'site_favicon' => 'Upload your website favicon (ICO, PNG, GIF, JPG, JPEG, WebP - Max: 20MB)',
            'admin_logo' => 'Upload admin panel logo (JPEG, PNG, JPG, GIF, SVG, WebP - Max: 20MB)',
            'footer_logo' => 'Upload logo for the site footer (JPEG, PNG, JPG, GIF, SVG, WebP - Max: 20MB)',
            'meta_title' => 'The default meta title for your website',
            'meta_description' => 'The default meta description for your website',
            'meta_keywords' => 'The default meta keywords for your website',
            'google_analytics' => 'Your Google Analytics tracking ID',
            'posts_per_page' => 'Number of posts to display per page',
            'default_category' => 'The default category ID for new posts',
            'enable_comments' => 'Allow users to comment on posts',
            'moderate_comments' => 'Require approval for comments before they are published',
            'doculoket_sandbox' => 'Enable sandbox mode for Doculoket API testing and development',
            'map_latitude' => 'Latitude coordinate for the map center (e.g., 52.3676)',
            'map_longitude' => 'Longitude coordinate for the map center (e.g., 4.9041)',
            'map_zoom' => 'Default zoom level for the map (1-19)',
        ];

        return $descriptions[$key] ?? null;
    }

    /**
     * Get order based on key.
     */
    private function getOrder(string $key): int
    {
        $orders = [
            'site_name' => 1,
            'site_description' => 2,
            'site_email' => 3,
            'copyright_footer' => 4,
            'site_phone' => 4,
            'site_address' => 5,
            'site_logo' => 6,
            'site_favicon' => 7,
            'admin_logo' => 8,
            'footer_logo' => 9,
            'meta_title' => 1,
            'meta_description' => 2,
            'meta_keywords' => 3,
            'google_analytics' => 4,
            'posts_per_page' => 1,
            'default_category' => 2,
            'enable_comments' => 3,
            'moderate_comments' => 4,
            'doculoket_sandbox' => 1,
            'map_latitude' => 1,
            'map_longitude' => 2,
            'map_zoom' => 3,
        ];

        return $orders[$key] ?? 0;
    }

    /**
     * Clear relevant caches after updating general settings.
     */
    private function clearCaches(): void
    {
        // Clear general settings cache
        Setting::forgetAggregateCache();

        // Clear individual setting caches that might be affected
        $settingKeys = [
            'site_name', 'copyright_footer', 'site_description', 'site_email', 'site_phone', 'site_address',
            'site_logo', 'site_favicon', 'admin_logo', 'footer_logo', 'meta_title', 'meta_description', 'meta_keywords',
            'google_analytics', 'posts_per_page', 'default_category', 'enable_comments',
            'moderate_comments', 'doculoket_sandbox', 'map_latitude', 'map_longitude', 'map_zoom',
        ];

        foreach ($settingKeys as $key) {
            Cache::forget("settings.{$key}");
        }
    }
}
