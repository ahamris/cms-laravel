<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Site settings
        $this->createSetting('site_name', 'My Website', 'text', 'site', 'Site Name', 'The name of your website', 1);
        $this->createSetting('site_tagline', '', 'text', 'site', 'Site Tagline', 'Short tagline shown next to site name (e.g. in page titles)', 2);
        $this->createSetting('site_description', 'A brief description of your website', 'textarea', 'site', 'Site Description', 'A brief description of your website', 3);
        $this->createSetting('site_logo', null, 'image', 'site', 'Site Logo', 'The logo of your website', 4);
        $this->createSetting('copyright_footer', 'Copyright © {{year}} All rights reserved.', 'text', 'site', 'Copyright Footer', 'The copyright text. Use {{year}} for current year', 5);

        $this->createSetting('admin_logo', 'assets/logo/logo.svg', 'image', 'site', 'Admin Logo', 'The logo of admin panel', 6);

        $this->createSetting('site_favicon', null, 'image', 'site', 'Site Favicon', 'The favicon of your website', 7);
        $this->createSetting('site_email', 'contact@example.com', 'email', 'site', 'Site Email', 'The primary email address for your website', 8);
        $this->createSetting('site_phone', '+1 (555) 000-0000', 'text', 'site', 'Site Phone', 'The primary phone number for your website', 9);
        $this->createSetting('site_address', '123 Main Street, City, State, ZIP Code', 'textarea', 'site', 'Site Address', 'The physical address of your organization', 10);

        // SEO settings
        $this->createSetting('meta_title', 'My Website - Content Management', 'text', 'seo', 'Meta Title', 'The default meta title for your website', 1);
        $this->createSetting('meta_description', 'A professional content management platform for creating and managing digital content', 'textarea', 'seo', 'Meta Description', 'The default meta description for your website', 2);
        $this->createSetting('meta_keywords', 'cms, content management, website, publishing', 'textarea', 'seo', 'Meta Keywords', 'The default meta keywords for your website', 3);
        $this->createSetting('google_analytics', '', 'text', 'seo', 'Google Analytics ID', 'Your Google Analytics tracking ID', 4);

        // Content settings
        $this->createSetting('posts_per_page', '10', 'number', 'content', 'Posts Per Page', 'Number of posts to display per page', 1);
        $this->createSetting('enable_comments', '1', 'boolean', 'content', 'Enable Comments', 'Allow users to comment on posts', 2);
        $this->createSetting('moderate_comments', '1', 'boolean', 'content', 'Moderate Comments', 'Require approval for comments before they are published', 3);
        $this->createSetting('default_category', '1', 'number', 'content', 'Default Category', 'The default category ID for new posts', 4);
        $this->createSetting('doculoket_sandbox', '0', 'boolean', 'content', 'Doculoket Sandbox', 'Use Doculoket sandbox mode for testing', 5);

        // Email settings
        $this->createSetting('mail_driver', 'smtp', 'text', 'email', 'Mail Driver', 'The mail driver (smtp, sendmail, etc.)', 1);
        $this->createSetting('mail_host', 'smtp.mailtrap.io', 'text', 'email', 'Mail Host', 'The mail server host', 2);
        $this->createSetting('mail_port', '2525', 'number', 'email', 'Mail Port', 'The mail server port', 3);
        $this->createSetting('mail_username', '', 'text', 'email', 'Mail Username', 'The mail server username', 4);
        $this->createSetting('mail_password', '', 'text', 'email', 'Mail Password', 'The mail server password', 5);
        $this->createSetting('mail_encryption', 'tls', 'text', 'email', 'Mail Encryption', 'The mail encryption type (tls, ssl)', 6);
        $this->createSetting('mail_from_address', 'noreply@example.com', 'email', 'email', 'Mail From Address', 'The email address that sends emails', 7);
        $this->createSetting('mail_from_name', 'My Website', 'text', 'email', 'Mail From Name', 'The name that appears as the sender of emails', 8);

        // Theme settings
        $this->createSetting('theme_color_primary', '#081245', 'color', 'theme', 'Primary Color', 'The primary brand color used throughout the site', 1);
        $this->createSetting('theme_color_secondary', '#0073e6', 'color', 'theme', 'Secondary Color', 'The secondary accent color', 2);
        $this->createSetting('theme_color_natural', '#dfd4d4', 'color', 'theme', 'Natural Color', 'The neutral/natural color for backgrounds and borders', 3);
        $this->createSetting('theme_footer_bg', '#1a1a2e', 'color', 'theme', 'Footer Background', 'Background color for the footer area', 4);
        $this->createSetting('theme_footer_text', '#ffffff', 'color', 'theme', 'Footer Text', 'Text color for the footer', 5);
        $this->createSetting('theme_header_bg', '#ffffff', 'color', 'theme', 'Header Background', 'Background color for the header area', 6);
        $this->createSetting('theme_header_text', '#1a1a2e', 'color', 'theme', 'Header Text', 'Text color for the header', 7);
        $this->createSetting('theme_font_sans', 'Inter', 'text', 'theme', 'Sans-serif Font', 'The primary sans-serif font family (e.g., "Inter", sans-serif)', 8);
        $this->createSetting('theme_font_outfit', 'Outfit', 'text', 'theme', 'Outfit Font', 'The secondary font family for headings (e.g., "Outfit", sans-serif)', 9);
        $this->createSetting('theme_font_size_h1', '2.25rem', 'text', 'theme', 'H1 Font Size', 'Font size for H1 headings', 10);
        $this->createSetting('theme_font_size_h2', '1.875rem', 'text', 'theme', 'H2 Font Size', 'Font size for H2 headings', 11);
        $this->createSetting('theme_font_size_h3', '1.5rem', 'text', 'theme', 'H3 Font Size', 'Font size for H3 headings', 12);
        $this->createSetting('theme_font_size_h4', '1.25rem', 'text', 'theme', 'H4 Font Size', 'Font size for H4 headings', 13);
        $this->createSetting('theme_font_size_h5', '1.125rem', 'text', 'theme', 'H5 Font Size', 'Font size for H5 headings', 14);
        $this->createSetting('theme_font_size_h6', '1rem', 'text', 'theme', 'H6 Font Size', 'Font size for H6 headings', 15);
        $this->createSetting('theme_font_size_p', '1rem', 'text', 'theme', 'Paragraph Font Size', 'Font size for paragraphs', 16);

        // Login page settings
        $this->createSetting('theme_login_form_mode', 'white', 'select', 'login', 'Login Form Mode', 'Choose between white form style or glass form style (white/glass)', 1);
        $this->createSetting('login_page_title', 'Log in', 'text', 'login', 'Login Page Title', 'The main title displayed on the login page', 2);
        $this->createSetting('login_page_subtitle', 'Enter your credentials to access your account', 'text', 'login', 'Login Page Subtitle', 'The subtitle/description text displayed on the login page', 3);
        $this->createSetting('login_page_logo', 'assets/logo/logo.png', 'image', 'login', 'Login Page Logo', 'The logo displayed on the login page (JPEG, PNG, JPG, GIF, SVG - Max: 20MB)', 4);
        $this->createSetting('login_background_image', 'front/images/login-image.jpg', 'image', 'login', 'Login Background Image', 'The background image for the login page (JPEG, PNG, JPG - Max: 20MB)', 5);
        $this->createSetting('login_footer_copyright', '© {{year}} All rights reserved.', 'text', 'login', 'Login Footer Copyright', 'The copyright text displayed in the login page footer. Use {{year}} for current year', 6);
        $this->createSetting('login_enable_remember_me', '1', 'boolean', 'login', 'Enable Remember Me', 'Show or hide the remember me checkbox on the login form', 7);
        $this->createSetting('login_enable_forgot_password', '1', 'boolean', 'login', 'Enable Forgot Password', 'Show or hide the forgot password link on the login form', 8);

        // Login footer links as JSON array
        $defaultFooterLinks = json_encode([
            ['title' => 'Privacy Policy', 'link' => '/privacy-policy', 'order' => 1, 'target' => '_self'],
            ['title' => 'Cookie Policy', 'link' => '/cookie-policy', 'order' => 2, 'target' => '_self'],
            ['title' => 'Contact', 'link' => '/contact', 'order' => 3, 'target' => '_self'],
            ['title' => 'Support', 'link' => '/support', 'order' => 4, 'target' => '_self'],
        ]);
        $this->createSetting('login_footer_links', $defaultFooterLinks, 'json', 'login', 'Login Footer Links', 'Manage footer links for the login page. Each link should have title, link, order, and target (_self or _blank)', 9);

        // Header CTA (Mega Menu)
        $this->createSetting('header_cta_button_text', 'Sign up', 'text', 'header', 'Header CTA Button Text', 'Text for the call-to-action button in the header', 1);
        $this->createSetting('header_cta_button_url', '#', 'text', 'header', 'Header CTA Button URL', 'URL for the call-to-action button in the header', 2);

        // Cookie settings
        $this->createSetting('cookie_banner_enabled', '1', 'boolean', 'cookie', 'Cookie Banner Enabled', 'Show the cookie consent banner to visitors', 1);
        $this->createSetting('cookie_intro_title', 'We use cookies', 'text', 'cookie', 'Cookie Intro Title', 'Title shown in the cookie banner', 2);
        $this->createSetting('cookie_intro_summary', 'In addition to functional cookies we also place analytics and marketing cookies to understand usage, show relevant content and offer support. Only essential cookies are enabled by default.', 'textarea', 'cookie', 'Cookie Intro Summary', 'Summary text in the cookie banner', 3);
        $this->createSetting('cookie_preferences_title', 'Manage cookie preferences', 'text', 'cookie', 'Cookie Preferences Title', 'Title for the cookie preferences modal', 4);
        $this->createSetting('cookie_preferences_summary', 'Configure your cookie preferences below. Need more information? Read our policy.', 'textarea', 'cookie', 'Cookie Preferences Summary', 'Summary in the cookie preferences modal', 5);
        $this->createSetting('cookie_settings_label', 'Cookie policy', 'text', 'cookie', 'Cookie Settings Label', 'Label for the link to cookie settings/policy', 6);
        $this->createSetting('cookie_settings_page_type', 'custom', 'text', 'cookie', 'Cookie Settings Page Type', 'Type of cookie settings link: custom or page (Legal template)', 7);
        $this->createSetting('cookie_settings_page_id', null, 'number', 'cookie', 'Cookie Settings Page ID', 'CMS pages.id when type is page (must use Legal template)', 8);
        $this->createSetting('cookie_settings_url', 'javascript:void(0)', 'text', 'cookie', 'Cookie Settings URL', 'URL for cookie settings when using custom link', 9);
        $this->createSetting('cookie_policy_page_type', 'custom', 'text', 'cookie', 'Cookie Policy Page Type', 'Type of cookie policy link: custom or page (Legal template)', 10);
        $this->createSetting('cookie_policy_page_id', null, 'number', 'cookie', 'Cookie Policy Page ID', 'CMS pages.id when type is page (must use Legal template)', 11);
        $this->createSetting('cookie_policy_url', 'javascript:void(0)', 'text', 'cookie', 'Cookie Policy URL', 'URL for cookie policy when using custom link', 12);
        $this->createSetting('cookie_category_functional_label', 'Functional cookies', 'text', 'cookie', 'Functional Cookies Label', 'Label for the functional cookies category', 13);
        $this->createSetting('cookie_category_functional_description', 'Required for core functionality of the website.', 'textarea', 'cookie', 'Functional Cookies Description', 'Description for the functional cookies category', 14);
        $this->createSetting('cookie_category_analytics_label', 'Analytics cookies', 'text', 'cookie', 'Analytics Cookies Label', 'Label for the analytics cookies category', 15);
        $this->createSetting('cookie_category_analytics_description', 'Help us measure usage and improve the experience.', 'textarea', 'cookie', 'Analytics Cookies Description', 'Description for the analytics cookies category', 16);
        $this->createSetting('cookie_category_marketing_label', 'Marketing cookies', 'text', 'cookie', 'Marketing Cookies Label', 'Label for the marketing cookies category', 17);
        $this->createSetting('cookie_category_marketing_description', 'Enable personalised content and external integrations.', 'textarea', 'cookie', 'Marketing Cookies Description', 'Description for the marketing cookies category', 18);

        // Map / Contact settings
        $this->createSetting('map_latitude', '52.3676', 'text', 'contact', 'Map Latitude', 'Latitude coordinate for map center', 1);
        $this->createSetting('map_longitude', '4.9041', 'text', 'contact', 'Map Longitude', 'Longitude coordinate for map center', 2);
        $this->createSetting('map_zoom', '13', 'number', 'contact', 'Map Zoom Level', 'Map zoom level (1-19)', 3);

        // Hero / Header section background images (list/static pages only; aligned with API endpoints)
        $this->createSetting('hero_background_contact', null, 'image', 'hero', 'Contact hero', 'Background image for the contact page hero section', 1);
        $this->createSetting('hero_background_blog', null, 'image', 'hero', 'Blog hero', 'Background image for the blog index page hero section', 2);
        $this->createSetting('hero_background_solutions_index', null, 'image', 'hero', 'Solutions hero', 'Background image for the solutions index page hero section', 3);
        $this->createSetting('hero_background_modules_index', null, 'image', 'hero', 'Modules hero', 'Background image for the modules index page hero section', 4);
        $this->createSetting('hero_background_docs', null, 'image', 'hero', 'Docs hero', 'Background image for the docs page hero section', 5);
        $this->createSetting('hero_background_academy', null, 'image', 'hero', 'Academy hero', 'Background image for the academy (course) page hero section', 6);
        $this->createSetting('hero_background_trial', null, 'image', 'hero', 'Demo request hero', 'Background image for the demo request (proefversie) page hero section', 7);
    }

    private function createSetting($key, $value, $type, $group, $displayName, $description = null, $order = 0): void
    {

        Setting::firstOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'display_name' => $displayName,
                'description' => $description,
                'order' => $order,
            ]
        );

    }
}
