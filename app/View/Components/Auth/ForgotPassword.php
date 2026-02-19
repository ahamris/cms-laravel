<?php

namespace App\View\Components\Auth;

use App\Models\Setting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ForgotPassword extends Component
{
    public string $mode;
    public string $pageTitle;
    public string $pageSubtitle;
    public ?string $logoUrl;
    public ?string $backgroundImageUrl;
    public string $footerCopyright;
    public array $footerLinks;

    public function __construct()
    {
        $this->mode = Setting::getValue('theme_login_form_mode', 'white');
        $this->pageTitle = Setting::getValue('login_page_title', 'Forgot password');
        $this->pageSubtitle = Setting::getValue('login_page_subtitle', 'Enter your email and we\'ll send a reset link');
        
        // Get logo URL using get_image helper
        $logoPath = Setting::getValue('login_page_logo', null);
        $this->logoUrl = get_image($logoPath, asset('assets/logo/logo.png'));
        
        // Get background image URL using get_image helper
        $backgroundPath = Setting::getValue('login_background_image', null);
        $this->backgroundImageUrl = get_image($backgroundPath, asset('front/images/login-image.jpg'));
        
        // Get footer copyright (replace {{year}} with current year)
        $copyright = Setting::getValue('login_footer_copyright', '© {{year}} All rights reserved.');
        $this->footerCopyright = str_replace('{{year}}', date('Y'), $copyright);
        
        // Get footer links
        $footerLinksJson = Setting::getValue('login_footer_links', '[]');
        $this->footerLinks = json_decode($footerLinksJson, true) ?: [];
        
        // Sort footer links by order
        usort($this->footerLinks, function($a, $b) {
            return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
        });
    }

    public function render(): View|Closure|string
    {
        return view('components.auth.forgot-password');
    }
}


