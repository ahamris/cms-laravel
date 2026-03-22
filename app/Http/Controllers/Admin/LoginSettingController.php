<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class LoginSettingController extends AdminBaseController
{
    /**
     * Display the login settings page.
     */
    public function index()
    {
        return view('admin.settings.login.index');
    }

    /**
     * Update login settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'theme_login_form_mode' => 'required|string|in:white,glass',
            'login_page_title' => 'required|string|max:255',
            'login_page_subtitle' => 'nullable|string|max:500',
            'login_page_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'login_background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'login_footer_copyright' => 'nullable|string|max:500',
            'login_enable_remember_me' => 'boolean',
            'login_enable_forgot_password' => 'boolean',
            'login_footer_links' => 'nullable|string',
        ]);

        // Validate footer links JSON structure if provided
        $footerLinks = null;
        if ($request->has('login_footer_links') && !empty($request->login_footer_links)) {
            $footerLinks = json_decode($request->login_footer_links, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()
                    ->withErrors(['login_footer_links' => 'Invalid JSON format for footer links'])
                    ->withInput();
            }

            if (!is_array($footerLinks)) {
                return redirect()->back()
                    ->withErrors(['login_footer_links' => 'Footer links must be an array'])
                    ->withInput();
            }

            // Validate each footer link item
            foreach ($footerLinks as $index => $link) {
                if (!isset($link['title']) || empty(trim($link['title']))) {
                    return redirect()->back()
                        ->withErrors(["login_footer_links" => "Footer link #{$index} must have a title"])
                        ->withInput();
                }
                if (!isset($link['link']) || empty(trim($link['link']))) {
                    return redirect()->back()
                        ->withErrors(["login_footer_links" => "Footer link #{$index} must have a link"])
                        ->withInput();
                }
                if (!isset($link['order']) || !is_numeric($link['order']) || $link['order'] < 0) {
                    return redirect()->back()
                        ->withErrors(["login_footer_links" => "Footer link #{$index} must have a valid order (integer >= 0)"])
                        ->withInput();
                }
                if (!isset($link['target']) || !in_array($link['target'], ['_self', '_blank'])) {
                    return redirect()->back()
                        ->withErrors(["login_footer_links" => "Footer link #{$index} target must be either '_self' or '_blank'"])
                        ->withInput();
                }
            }
        }

        try {
            // Update login form mode
            $this->updateSetting('theme_login_form_mode', $request->theme_login_form_mode);

            // Update login page text settings
            $this->updateSetting('login_page_title', $request->login_page_title);
            $this->updateSetting('login_page_subtitle', $request->login_page_subtitle);
            $this->updateSetting('login_footer_copyright', $request->login_footer_copyright);

            // Handle login page logo upload
            if ($request->hasFile('login_page_logo')) {
                $this->handleFileUpload($request, 'login_page_logo');
            }

            // Handle login page logo removal
            if ($request->has('remove_login_page_logo')) {
                $this->deleteFileSetting('login_page_logo');
            }

            // Handle login background image upload
            if ($request->hasFile('login_background_image')) {
                $this->handleFileUpload($request, 'login_background_image');
            }

            // Handle login background image removal
            if ($request->has('remove_login_background_image')) {
                $this->deleteFileSetting('login_background_image');
            }

            // Update boolean settings
            $this->updateSetting('login_enable_remember_me', $request->has('login_enable_remember_me') ? '1' : '0');
            $this->updateSetting('login_enable_forgot_password', $request->has('login_enable_forgot_password') ? '1' : '0');

            // Update footer links JSON
            if ($footerLinks !== null) {
                // Sort by order
                usort($footerLinks, function($a, $b) {
                    return $a['order'] <=> $b['order'];
                });
                
                // Re-encode as JSON
                $this->updateSetting('login_footer_links', json_encode($footerLinks));
            } elseif ($request->has('login_footer_links') && empty($request->login_footer_links)) {
                // If explicitly empty, set to empty array
                $this->updateSetting('login_footer_links', json_encode([]));
            }

            // Clear relevant caches after updating settings
            $this->clearCaches();

            // Log activity
            $this->logSettingsUpdate('Login Settings');

            return redirect()->back()->with('status', 'login-settings-updated');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'An error occurred while updating login settings: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Handle file upload for login page logo and background image.
     */
    private function handleFileUpload(Request $request, string $fieldName): void
    {
        $file = $request->file($fieldName);
        
        // Delete old file if exists
        $oldSetting = Setting::where('key', $fieldName)->first();
        if ($oldSetting && !empty($oldSetting->value) && trim($oldSetting->value) !== '') {
            if (Storage::disk('public')->exists($oldSetting->value)) {
                Storage::disk('public')->delete($oldSetting->value);
            }
        }

        // Store new file
        $directory = match($fieldName) {
            'login_page_logo' => 'logos',
            'login_background_image' => 'login',
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

        try {
            $publicPath = storage_path('app/public/'.$directory);

            // Create directory if it doesn't exist
            if (!file_exists($publicPath)) {
                if (!mkdir($publicPath, 0755, true) && !is_dir($publicPath)) {
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
            if (!$success) {
                try {
                    $file->move($publicPath, $filename);
                    $success = true;
                } catch (\Exception $e) {
                    // Continue to next method
                }
            }

            // Method 3: Copy file if other methods fail
            if (!$success && $file->getRealPath() && file_exists($file->getRealPath())) {
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
            if (!empty($setting->value) && trim($setting->value) !== '') {
                // Only delete from storage if it looks like a storage path (not a full URL or public path)
                if (!filter_var($setting->value, FILTER_VALIDATE_URL) && 
                    !str_starts_with($setting->value, 'assets/') && 
                    !str_starts_with($setting->value, 'front/')) {
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
                'group' => 'login',
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
            'theme_login_form_mode' => 'select',
            'login_page_title' => 'text',
            'login_page_subtitle' => 'text',
            'login_page_logo' => 'image',
            'login_background_image' => 'image',
            'login_footer_copyright' => 'text',
            'login_enable_remember_me' => 'boolean',
            'login_enable_forgot_password' => 'boolean',
            'login_footer_links' => 'json',
        ];

        return $types[$key] ?? 'text';
    }

    /**
     * Get display name based on key.
     */
    private function getDisplayName(string $key): string
    {
        $names = [
            'theme_login_form_mode' => 'Login Form Mode',
            'login_page_title' => 'Login Page Title',
            'login_page_subtitle' => 'Login Page Subtitle',
            'login_page_logo' => 'Login Page Logo',
            'login_background_image' => 'Login Background Image',
            'login_footer_copyright' => 'Login Footer Copyright',
            'login_enable_remember_me' => 'Enable Remember Me',
            'login_enable_forgot_password' => 'Enable Forgot Password',
            'login_footer_links' => 'Login Footer Links',
        ];

        return $names[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }

    /**
     * Get description based on key.
     */
    private function getDescription(string $key): ?string
    {
        $descriptions = [
            'theme_login_form_mode' => 'Choose between white form style or glass form style (white/glass)',
            'login_page_title' => 'The main title displayed on the login page',
            'login_page_subtitle' => 'The subtitle/description text displayed on the login page',
            'login_page_logo' => 'The logo displayed on the login page (JPEG, PNG, JPG, GIF, SVG - Max: 20MB)',
            'login_background_image' => 'The background image for the login page (JPEG, PNG, JPG - Max: 20MB)',
            'login_footer_copyright' => 'The copyright text displayed in the login page footer. Use {{year}} for current year',
            'login_enable_remember_me' => 'Show or hide the remember me checkbox on the login form',
            'login_enable_forgot_password' => 'Show or hide the forgot password link on the login form',
            'login_footer_links' => 'Manage footer links for the login page. Each link should have title, link, order, and target (_self or _blank)',
        ];

        return $descriptions[$key] ?? null;
    }

    /**
     * Get order based on key.
     */
    private function getOrder(string $key): int
    {
        $orders = [
            'theme_login_form_mode' => 1,
            'login_page_title' => 2,
            'login_page_subtitle' => 3,
            'login_page_logo' => 4,
            'login_background_image' => 5,
            'login_footer_copyright' => 6,
            'login_enable_remember_me' => 7,
            'login_enable_forgot_password' => 8,
            'login_footer_links' => 9,
        ];

        return $orders[$key] ?? 0;
    }

    /**
     * Clear relevant caches after updating login settings.
     */
    private function clearCaches(): void
    {
        Cache::forget('settings');

        $settingKeys = [
            'theme_login_form_mode',
            'login_page_title',
            'login_page_subtitle',
            'login_page_logo',
            'login_background_image',
            'login_footer_copyright',
            'login_enable_remember_me',
            'login_enable_forgot_password',
            'login_footer_links',
        ];

        foreach ($settingKeys as $key) {
            Cache::forget("settings.{$key}");
        }
    }
}

