<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\SiteSettingsResource;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    /**
     * Site settings + theme for frontend (homepage, branding).
     */
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
                'hero_background' => get_setting('hero_background_academy') ? get_image(get_setting('hero_background_academy')) : null,
            ],
            'theme' => [
                'base_color' => $theme->base_color,
                'accent_color' => $theme->accent_color,
            ],
        ];
    }
}
