<?php

namespace App\Livewire\Admin;

use App\Models\MegaMenuItem;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;

class LoginSettings extends Component
{
    use WithFileUploads;

    // Login Form Settings
    public string $loginFormMode = 'white';

    public string $loginPageTitle = 'Log in';

    public string $loginPageSubtitle = 'Enter your credentials to access your account';

    public $loginPageLogo;

    public $loginBackgroundImage;

    public string $loginFooterCopyright = '© {{year}} All rights reserved.';

    public bool $loginEnableRememberMe = true;

    public bool $loginEnableForgotPassword = true;

    // Footer Links
    public array $footerLinks = [];

    // UI State
    public bool $saved = false;

    public ?string $currentLoginPageLogo = null;

    public ?string $currentLoginBackgroundImage = null;

    public function mount(): void
    {
        // Load current login settings
        $this->loginFormMode = Setting::getValue('theme_login_form_mode', 'white');
        $this->loginPageTitle = Setting::getValue('login_page_title', 'Log in');
        $this->loginPageSubtitle = Setting::getValue('login_page_subtitle', 'Enter your credentials to access your account');
        $this->loginFooterCopyright = Setting::getValue('login_footer_copyright', '© {{year}} All rights reserved.');
        $this->loginEnableRememberMe = Setting::getValue('login_enable_remember_me', '1') === '1';
        $this->loginEnableForgotPassword = Setting::getValue('login_enable_forgot_password', '1') === '1';

        // Load current images using get_image helper
        $logoPath = Setting::getValue('login_page_logo', null);
        $this->currentLoginPageLogo = $logoPath ? get_image($logoPath) : null;

        $backgroundPath = Setting::getValue('login_background_image', null);
        $this->currentLoginBackgroundImage = $backgroundPath ? get_image($backgroundPath) : null;

        // Load footer links
        $footerLinksJson = Setting::getValue('login_footer_links', '[]');
        $this->footerLinks = json_decode($footerLinksJson, true) ?: [];

        // Ensure each link has link_type field
        foreach ($this->footerLinks as $index => $link) {
            if (! isset($this->footerLinks[$index]['link_type'])) {
                $this->footerLinks[$index]['link_type'] = 'custom';
            }
        }
    }

    public function addFooterLink(): void
    {
        $this->footerLinks[] = [
            'title' => '',
            'link' => '',
            'link_type' => 'custom',
            'order' => count($this->footerLinks) + 1,
            'target' => '_self',
        ];
    }

    public function removeFooterLink(int $index): void
    {
        unset($this->footerLinks[$index]);
        $this->footerLinks = array_values($this->footerLinks);
        // Reorder
        foreach ($this->footerLinks as $i => $link) {
            $this->footerLinks[$i]['order'] = $i + 1;
        }
    }

    public function save(): void
    {
        // Validate all inputs
        $this->validate([
            'loginFormMode' => 'required|string|in:white,glass',
            'loginPageTitle' => 'required|string|max:255',
            'loginPageSubtitle' => 'nullable|string|max:500',
            'loginPageLogo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'loginBackgroundImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'loginFooterCopyright' => 'nullable|string|max:500',
            'loginEnableRememberMe' => 'boolean',
            'loginEnableForgotPassword' => 'boolean',
            'footerLinks' => 'array',
            'footerLinks.*.title' => 'required|string|max:255',
            'footerLinks.*.link' => 'required|string|max:500',
            'footerLinks.*.link_type' => 'nullable|string|in:predefined,custom,system',
            'footerLinks.*.order' => 'required|integer|min:0',
            'footerLinks.*.target' => 'required|string|in:_self,_blank',
        ], [
            'loginFormMode.in' => 'Login form mode must be either "white" or "glass"',
            'footerLinks.*.title.required' => 'Each footer link must have a title',
            'footerLinks.*.link.required' => 'Each footer link must have a link',
        ]);

        try {
            // Save login form mode
            $this->updateSetting('theme_login_form_mode', $this->loginFormMode);

            // Save login page text settings
            $this->updateSetting('login_page_title', $this->loginPageTitle);
            $this->updateSetting('login_page_subtitle', $this->loginPageSubtitle);
            $this->updateSetting('login_footer_copyright', $this->loginFooterCopyright);

            // Handle login page logo upload
            if ($this->loginPageLogo) {
                $path = $this->loginPageLogo->store('logos', 'public');
                $this->updateSetting('login_page_logo', $path);
                $this->currentLoginPageLogo = get_image($path);
                $this->loginPageLogo = null;
            }

            // Handle login background image upload
            if ($this->loginBackgroundImage) {
                $path = $this->loginBackgroundImage->store('login', 'public');
                $this->updateSetting('login_background_image', $path);
                $this->currentLoginBackgroundImage = get_image($path);
                $this->loginBackgroundImage = null;
            }

            // Save boolean settings
            $this->updateSetting('login_enable_remember_me', $this->loginEnableRememberMe ? '1' : '0');
            $this->updateSetting('login_enable_forgot_password', $this->loginEnableForgotPassword ? '1' : '0');

            // Save footer links
            usort($this->footerLinks, function ($a, $b) {
                return $a['order'] <=> $b['order'];
            });
            $this->updateSetting('login_footer_links', json_encode($this->footerLinks));

            // Clear cache
            $this->clearCaches();

            // Set saved state for visual feedback
            $this->saved = true;

            // Dispatch success event
            $this->dispatch('notify', type: 'success', message: 'Login settings updated successfully!');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Error updating settings: '.$e->getMessage());
        }
    }

    public function removeLoginPageLogo(): void
    {
        $setting = Setting::where('key', 'login_page_logo')->first();
        if ($setting && $setting->value) {
            if (file_exists(storage_path('app/public/'.$setting->value))) {
                unlink(storage_path('app/public/'.$setting->value));
            }
        }
        $this->updateSetting('login_page_logo', null);
        $this->currentLoginPageLogo = null;
    }

    public function removeLoginBackgroundImage(): void
    {
        $setting = Setting::where('key', 'login_background_image')->first();
        if ($setting && $setting->value) {
            if (file_exists(storage_path('app/public/'.$setting->value))) {
                unlink(storage_path('app/public/'.$setting->value));
            }
        }
        $this->updateSetting('login_background_image', null);
        $this->currentLoginBackgroundImage = null;
    }

    /**
     * Update a setting
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
     * Get setting type based on key
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
     * Get display name based on key
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
     * Get description based on key
     */
    private function getDescription(string $key): ?string
    {
        $descriptions = [
            'theme_login_form_mode' => 'Choose between white form style or glass form style (white/glass)',
            'login_page_title' => 'The main title displayed on the login page',
            'login_page_subtitle' => 'The subtitle/description text displayed on the login page',
            'login_page_logo' => 'The logo displayed on the login page',
            'login_background_image' => 'The background image for the login page',
            'login_footer_copyright' => 'The copyright text displayed in the login page footer',
            'login_enable_remember_me' => 'Show or hide the remember me checkbox on the login form',
            'login_enable_forgot_password' => 'Show or hide the forgot password link on the login form',
            'login_footer_links' => 'Manage footer links for the login page',
        ];

        return $descriptions[$key] ?? null;
    }

    /**
     * Get order based on key
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
     * Clear relevant caches
     */
    private function clearCaches(): void
    {
        Setting::forgetAggregateCache();
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

    public function render()
    {
        // Get available routes and system content for URL selector
        $availableRoutes = MegaMenuItem::possibleMenuItems();
        $systemContent = MegaMenuItem::getSystemContent();

        return view('livewire.admin.login-settings', [
            'availableRoutes' => $availableRoutes,
            'systemContent' => $systemContent,
        ]);
    }
}
