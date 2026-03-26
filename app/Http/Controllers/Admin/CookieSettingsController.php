<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CookieSettingsController extends AdminBaseController
{
    /**
     * Display the cookie settings page.
     */
    public function index()
    {
        $legalTemplatePages = Page::query()
            ->where('is_active', true)
            ->where('template', $this->cookieLegalTemplateKey())
            ->orderBy('title')
            ->get(['id', 'title', 'slug']);

        return view('admin.settings.cookie.index', [
            'legalTemplatePages' => $legalTemplatePages,
        ]);
    }

    /**
     * Update cookie settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            // Banner visibility
            'cookie_banner_enabled' => 'boolean',

            // Banner intro text
            'cookie_intro_title' => 'nullable|string|max:255',
            'cookie_intro_summary' => 'nullable|string|max:1000',

            // Preferences text
            'cookie_preferences_title' => 'nullable|string|max:255',
            'cookie_preferences_summary' => 'nullable|string|max:1000',

            // Links
            'cookie_settings_label' => 'nullable|string|max:255',
            'cookie_settings_url' => 'nullable|string|max:500',
            'cookie_settings_page_type' => 'nullable|string|in:custom,page',
            'cookie_settings_page_id' => 'nullable|integer',

            'cookie_policy_url' => 'nullable|string|max:500',
            'cookie_policy_page_type' => 'nullable|string|in:custom,page',
            'cookie_policy_page_id' => 'nullable|integer',

            // Category labels
            'cookie_category_functional_label' => 'nullable|string|max:255',
            'cookie_category_functional_description' => 'nullable|string|max:500',

            'cookie_category_analytics_label' => 'nullable|string|max:255',
            'cookie_category_analytics_description' => 'nullable|string|max:500',

            'cookie_category_marketing_label' => 'nullable|string|max:255',
            'cookie_category_marketing_description' => 'nullable|string|max:500',
        ]);

        try {
            // Update banner visibility
            $this->updateSetting('cookie_banner_enabled', $request->has('cookie_banner_enabled') ? '1' : '0');

            // Update banner intro text
            $this->updateSetting('cookie_intro_title', $request->cookie_intro_title);
            $this->updateSetting('cookie_intro_summary', $request->cookie_intro_summary);

            // Update preferences text
            $this->updateSetting('cookie_preferences_title', $request->cookie_preferences_title);
            $this->updateSetting('cookie_preferences_summary', $request->cookie_preferences_summary);

            // Update links
            $this->updateSetting('cookie_settings_label', $request->cookie_settings_label);

            if ($request->cookie_settings_page_type === 'page' && $request->cookie_settings_page_id) {
                $url = $this->resolveLegalTemplatePageUrl((int) $request->cookie_settings_page_id);
                $this->updateSetting('cookie_settings_url', $url ?? ($request->cookie_settings_url ?? ''));
            } else {
                $this->updateSetting('cookie_settings_url', $request->cookie_settings_url);
            }

            $this->updateSetting('cookie_settings_page_type', $request->cookie_settings_page_type);
            $this->updateSetting('cookie_settings_page_id', $request->cookie_settings_page_type === 'page' ? $request->cookie_settings_page_id : null);

            if ($request->cookie_policy_page_type === 'page' && $request->cookie_policy_page_id) {
                $url = $this->resolveLegalTemplatePageUrl((int) $request->cookie_policy_page_id);
                $this->updateSetting('cookie_policy_url', $url ?? ($request->cookie_policy_url ?? ''));
            } else {
                $this->updateSetting('cookie_policy_url', $request->cookie_policy_url);
            }

            $this->updateSetting('cookie_policy_page_type', $request->cookie_policy_page_type);
            $this->updateSetting('cookie_policy_page_id', $request->cookie_policy_page_type === 'page' ? $request->cookie_policy_page_id : null);

            // Update category labels and descriptions
            $this->updateSetting('cookie_category_functional_label', $request->cookie_category_functional_label);
            $this->updateSetting('cookie_category_functional_description', $request->cookie_category_functional_description);

            $this->updateSetting('cookie_category_analytics_label', $request->cookie_category_analytics_label);
            $this->updateSetting('cookie_category_analytics_description', $request->cookie_category_analytics_description);

            $this->updateSetting('cookie_category_marketing_label', $request->cookie_category_marketing_label);
            $this->updateSetting('cookie_category_marketing_description', $request->cookie_category_marketing_description);

            // Clear relevant caches
            $this->clearCaches();

            // Log activity
            $this->logSettingsUpdate('Cookie Settings');

            return redirect()->back()->with('status', 'settings-updated');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'An error occurred while updating settings: '.$e->getMessage()])
                ->withInput();
        }
    }

    private function cookieLegalTemplateKey(): string
    {
        return config('page_templates.cookie_legal_template', 'legal');
    }

    private function resolveLegalTemplatePageUrl(int $pageId): ?string
    {
        $page = Page::query()
            ->whereKey($pageId)
            ->where('is_active', true)
            ->where('template', $this->cookieLegalTemplateKey())
            ->first();

        return $page ? url('/pagina/'.$page->slug) : null;
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
                'group' => 'cookie',
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
            'cookie_banner_enabled' => 'boolean',
            'cookie_intro_title' => 'text',
            'cookie_intro_summary' => 'textarea',
            'cookie_preferences_title' => 'text',
            'cookie_preferences_summary' => 'textarea',
            'cookie_settings_label' => 'text',
            'cookie_settings_url' => 'text',
            'cookie_settings_page_type' => 'text',
            'cookie_settings_page_id' => 'number',
            'cookie_policy_url' => 'text',
            'cookie_policy_page_type' => 'text',
            'cookie_policy_page_id' => 'number',
            'cookie_category_functional_label' => 'text',
            'cookie_category_functional_description' => 'textarea',
            'cookie_category_analytics_label' => 'text',
            'cookie_category_analytics_description' => 'textarea',
            'cookie_category_marketing_label' => 'text',
            'cookie_category_marketing_description' => 'textarea',
        ];

        return $types[$key] ?? 'text';
    }

    /**
     * Get display name based on key.
     */
    private function getDisplayName(string $key): string
    {
        $names = [
            'cookie_banner_enabled' => 'Cookie Banner Enabled',
            'cookie_intro_title' => 'Cookie Intro Title',
            'cookie_intro_summary' => 'Cookie Intro Summary',
            'cookie_preferences_title' => 'Cookie Preferences Title',
            'cookie_preferences_summary' => 'Cookie Preferences Summary',
            'cookie_settings_label' => 'Cookie Settings Label',
            'cookie_settings_url' => 'Cookie Settings URL',
            'cookie_settings_page_type' => 'Cookie Settings Page Type',
            'cookie_settings_page_id' => 'Cookie Settings Page ID',
            'cookie_policy_url' => 'Cookie Policy URL',
            'cookie_policy_page_type' => 'Cookie Policy Page Type',
            'cookie_policy_page_id' => 'Cookie Policy Page ID',
            'cookie_category_functional_label' => 'Functional Category Label',
            'cookie_category_functional_description' => 'Functional Category Description',
            'cookie_category_analytics_label' => 'Analytics Category Label',
            'cookie_category_analytics_description' => 'Analytics Category Description',
            'cookie_category_marketing_label' => 'Marketing Category Label',
            'cookie_category_marketing_description' => 'Marketing Category Description',
        ];

        return $names[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }

    /**
     * Get description based on key.
     */
    private function getDescription(string $key): ?string
    {
        $descriptions = [
            'cookie_banner_enabled' => 'Enable or disable the cookie consent banner',
            'cookie_intro_title' => 'Title shown in the cookie banner',
            'cookie_intro_summary' => 'Summary text shown in the cookie banner',
            'cookie_preferences_title' => 'Title shown in the preferences modal',
            'cookie_preferences_summary' => 'Summary text shown in the preferences modal',
            'cookie_settings_label' => 'Label for the cookie settings link',
            'cookie_settings_url' => 'URL to the cookie settings page',
            'cookie_policy_url' => 'URL to the cookie policy page',
        ];

        return $descriptions[$key] ?? null;
    }

    /**
     * Get order based on key.
     */
    private function getOrder(string $key): int
    {
        $orders = [
            'cookie_banner_enabled' => 1,
            'cookie_intro_title' => 2,
            'cookie_intro_summary' => 3,
            'cookie_preferences_title' => 4,
            'cookie_preferences_summary' => 5,
            'cookie_settings_label' => 6,
            'cookie_settings_url' => 7,
            'cookie_policy_url' => 8,
        ];

        return $orders[$key] ?? 0;
    }

    /**
     * Clear relevant caches after updating cookie settings.
     */
    private function clearCaches(): void
    {
        Setting::forgetAggregateCache();

        $settingKeys = [
            'cookie_banner_enabled',
            'cookie_intro_title',
            'cookie_intro_summary',
            'cookie_preferences_title',
            'cookie_preferences_summary',
            'cookie_settings_label',
            'cookie_settings_url',
            'cookie_policy_url',
            'cookie_category_functional_label',
            'cookie_category_functional_description',
            'cookie_category_analytics_label',
            'cookie_category_analytics_description',
            'cookie_category_marketing_label',
            'cookie_category_marketing_description',
        ];

        foreach ($settingKeys as $key) {
            Cache::forget("settings.{$key}");
        }
    }
}
