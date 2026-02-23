<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminThemeSetting;
use App\Models\ExternalCode;
use App\Models\HomepageSection;
use App\Models\Organization;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class HomepageController extends Controller
{
    #[OA\Get(path: '/api/homepage', summary: 'Homepage content sections', description: 'Editable homepage sections (hero, feature cards, about OPMS, how it works, user features, competition, latest updates title, bottom CTA). Header and footer come from GET /api/settings.', tags: ['Homepage'], responses: [
        new OA\Response(response: 200, description: 'Sections keyed by section_key'),
    ])]
    public function homepage(Request $request)
    {
        $sections = HomepageSection::getAllForApi();
        return self::resolveImageUrls($sections);
    }

    /**
     * Resolve relative image paths to full URLs in section content.
     *
     * @param  array<string, array<string, mixed>>  $sections
     * @return array<string, array<string, mixed>>
     */
    private static function resolveImageUrls(array $sections): array
    {
        foreach ($sections as $key => $content) {
            if (! is_array($content)) {
                continue;
            }
            if (! empty($content['image'])) {
                $sections[$key]['image'] = get_image($content['image']);
            }
            if ($key === 'hero' || $key === 'about_opms') {
                continue;
            }
            if (isset($content['cards']) && is_array($content['cards'])) {
                foreach ($content['cards'] as $i => $card) {
                    if (! empty($card['icon']) && str_starts_with((string) $card['icon'], 'http')) {
                        continue;
                    }
                    if (! empty($card['icon'])) {
                        $sections[$key]['cards'][$i]['icon'] = $card['icon'];
                    }
                }
            }
        }
        return $sections;
    }
    #[OA\Get(path: '/api/settings', summary: 'Site settings', description: 'Grouped site, theme, SEO, contact/map, cookie, hero, header and footer settings for frontend.', tags: ['Settings'], responses: [
        new OA\Response(response: 200, description: 'Grouped settings'),
    ])]
    public function settings(Request $request)
    {
        $theme = AdminThemeSetting::getSettings();

        return [
            'site' => [
                'name' => get_setting('site_name', config('app.name')),
                'tagline' => get_setting('site_tagline'),
                'description' => get_setting('site_description'),
                'logo' => get_image(get_setting('site_logo')),
                'favicon' => get_image(get_setting('site_favicon')),
                'email' => get_setting('site_email'),
                'phone' => get_setting('site_phone'),
                'address' => get_setting('site_address'),
                'copyright_footer' => get_setting('copyright_footer'),
            ],
            'theme' => [
                'base_color' => $theme->base_color,
                'accent_color' => $theme->accent_color,
                'primary_color' => get_setting('theme_color_primary'),
                'secondary_color' => get_setting('theme_color_secondary'),
                'natural_color' => get_setting('theme_color_natural'),
                'font_sans' => get_setting('theme_font_sans'),
                'font_outfit' => get_setting('theme_font_outfit'),
                'font_size_h1' => get_setting('theme_font_size_h1'),
                'font_size_h2' => get_setting('theme_font_size_h2'),
                'font_size_h3' => get_setting('theme_font_size_h3'),
                'font_size_h4' => get_setting('theme_font_size_h4'),
                'font_size_h5' => get_setting('theme_font_size_h5'),
                'font_size_h6' => get_setting('theme_font_size_h6'),
                'font_size_p' => get_setting('theme_font_size_p'),
            ],
            'seo' => [
                'meta_title' => get_setting('meta_title'),
                'meta_description' => get_setting('meta_description'),
                'meta_keywords' => get_setting('meta_keywords'),
                'google_analytics_id' => get_setting('google_analytics'),
            ],
            'contact' => [
                'map_latitude' => get_setting('map_latitude'),
                'map_longitude' => get_setting('map_longitude'),
                'map_zoom' => (int) get_setting('map_zoom', 13),
            ],
            'cookie' => [
                'banner_enabled' => (bool) get_setting('cookie_banner_enabled', true),
                'intro_title' => get_setting('cookie_intro_title'),
                'intro_summary' => get_setting('cookie_intro_summary'),
                'preferences_title' => get_setting('cookie_preferences_title'),
                'preferences_summary' => get_setting('cookie_preferences_summary'),
                'settings_label' => get_setting('cookie_settings_label'),
                'settings_url' => get_setting('cookie_settings_url'),
                'policy_url' => get_setting('cookie_policy_url'),
                'category_functional_label' => get_setting('cookie_category_functional_label'),
                'category_functional_description' => get_setting('cookie_category_functional_description'),
                'category_analytics_label' => get_setting('cookie_category_analytics_label'),
                'category_analytics_description' => get_setting('cookie_category_analytics_description'),
                'category_marketing_label' => get_setting('cookie_category_marketing_label'),
                'category_marketing_description' => get_setting('cookie_category_marketing_description'),
            ],
            'hero' => [
                'contact' => get_setting('hero_background_contact') ? get_image(get_setting('hero_background_contact')) : null,
                'blog' => get_setting('hero_background_blog') ? get_image(get_setting('hero_background_blog')) : null,
                'solutions_index' => get_setting('hero_background_solutions_index') ? get_image(get_setting('hero_background_solutions_index')) : null,
                'solutions_show' => get_setting('hero_background_solutions_show') ? get_image(get_setting('hero_background_solutions_show')) : null,
                'modules_index' => get_setting('hero_background_modules_index') ? get_image(get_setting('hero_background_modules_index')) : null,
                'modules_show' => get_setting('hero_background_modules_show') ? get_image(get_setting('hero_background_modules_show')) : null,
                'academy' => get_setting('hero_background_academy') ? get_image(get_setting('hero_background_academy')) : null,
            ],
            'header' => [
                'cta_button_text' => get_setting('header_cta_button_text'),
                'cta_button_url' => get_setting('header_cta_button_url'),
            ],
            'footer' => [
                'cta_title' => get_setting('footer_cta_title'),
                'cta_subtitle' => get_setting('footer_cta_subtitle'),
                'cta_description' => get_setting('footer_cta_description'),
                'cta_button_text' => get_setting('footer_cta_button_text'),
                'cta_button_url' => get_setting('footer_cta_button_url'),
            ],
            'organizations' => Organization::getCached(),
            'external_codes' => ExternalCode::getCached(),
        ];
    }
}
