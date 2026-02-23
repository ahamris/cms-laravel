<?php

namespace App\Helpers;

use Carbon\CarbonInterface;

class Variable
{
    public const int CACHE_TTL = 86400;

    public const string GUARD_NAME = 'web';

    public const string ROLE_ADMIN = 'admin';

    public const string ROLE_EDITOR = 'editor';

    public const string ROLE_USER = 'customer';

    public static array $fullRoles = [
        self::ROLE_ADMIN,
        self::ROLE_EDITOR,
        self::ROLE_USER,
    ];

    /** Default/random password for seeded admin account (change in production). */
    public const string DEFAULT_ADMIN_PASSWORD = 'k8mQ2pw4nRj';

    public static function expiresAt(): CarbonInterface
    {
        return now()->addSeconds(self::CACHE_TTL);
    }

    public const array DEFAULT_ACCOUNTS = [
        ['Admin', 'Account', 'admin@openpublication.eu', self::DEFAULT_ADMIN_PASSWORD, self::ROLE_ADMIN],
//        ['Webmaster', 'Account', 'selim@code-labs.nl', '@14396Oem!!', self::ROLE_ADMIN],

    ]; // default admin accounts

    /**
     * @var array|string[]
     */
    public static array $fullRolesSelector = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_EDITOR => 'Staff',
        self::ROLE_USER => 'Customer',
    ];

    public static array $fullPermissions = [

        'site_setting' => [self::ROLE_ADMIN],
        'permission_manager' => [self::ROLE_ADMIN],

        'media_access' => [self::ROLE_ADMIN],
        'media_show' => [self::ROLE_ADMIN],
        'media_create' => [self::ROLE_ADMIN],
        'media_edit' => [self::ROLE_ADMIN],
        'media_delete' => [self::ROLE_ADMIN],

        'user_access' => [self::ROLE_ADMIN],
        'user_show' => [self::ROLE_ADMIN],
        'user_create' => [self::ROLE_ADMIN],
        'user_edit' => [self::ROLE_ADMIN],
        'user_delete' => [self::ROLE_ADMIN],

        'permission_access' => [self::ROLE_ADMIN],
        'permission_show' => [self::ROLE_ADMIN],
        'permission_create' => [self::ROLE_ADMIN],
        'permission_edit' => [self::ROLE_ADMIN],
        'permission_delete' => [self::ROLE_ADMIN],

        'role_access' => [self::ROLE_ADMIN],
        'role_show' => [self::ROLE_ADMIN],
        'role_create' => [self::ROLE_ADMIN],
        'role_edit' => [self::ROLE_ADMIN],
        'role_delete' => [self::ROLE_ADMIN],

        'blog_access' => [self::ROLE_ADMIN],
        'blog_show' => [self::ROLE_ADMIN],
        'blog_create' => [self::ROLE_ADMIN],
        'blog_edit' => [self::ROLE_ADMIN],
        'blog_delete' => [self::ROLE_ADMIN],

        'blog_category_access' => [self::ROLE_ADMIN],
        'blog_category_show' => [self::ROLE_ADMIN],
        'blog_category_create' => [self::ROLE_ADMIN],
        'blog_category_edit' => [self::ROLE_ADMIN],
        'blog_category_delete' => [self::ROLE_ADMIN],

        'comment_access' => [self::ROLE_ADMIN],
        'comment_show' => [self::ROLE_ADMIN],
        'comment_create' => [self::ROLE_ADMIN],
        'comment_edit' => [self::ROLE_ADMIN],
        'comment_delete' => [self::ROLE_ADMIN],

        'page_access' => [self::ROLE_ADMIN],
        'page_show' => [self::ROLE_ADMIN],
        'page_create' => [self::ROLE_ADMIN],
        'page_edit' => [self::ROLE_ADMIN],
        'page_delete' => [self::ROLE_ADMIN],

        'legal_access' => [self::ROLE_ADMIN],
        'legal_show' => [self::ROLE_ADMIN],
        'legal_create' => [self::ROLE_ADMIN],
        'legal_edit' => [self::ROLE_ADMIN],
        'legal_delete' => [self::ROLE_ADMIN],

        'static_page_access' => [self::ROLE_ADMIN],
        'static_page_show' => [self::ROLE_ADMIN],
        'static_page_create' => [self::ROLE_ADMIN],
        'static_page_edit' => [self::ROLE_ADMIN],
        'static_page_delete' => [self::ROLE_ADMIN],

        'changelog_access' => [self::ROLE_ADMIN],
        'changelog_show' => [self::ROLE_ADMIN],
        'changelog_create' => [self::ROLE_ADMIN],
        'changelog_edit' => [self::ROLE_ADMIN],
        'changelog_delete' => [self::ROLE_ADMIN],

        'event_access' => [self::ROLE_ADMIN],
        'event_show' => [self::ROLE_ADMIN],
        'event_create' => [self::ROLE_ADMIN],
        'event_edit' => [self::ROLE_ADMIN],
        'event_delete' => [self::ROLE_ADMIN],

        'solution_access' => [self::ROLE_ADMIN],
        'solution_show' => [self::ROLE_ADMIN],
        'solution_create' => [self::ROLE_ADMIN],
        'solution_edit' => [self::ROLE_ADMIN],
        'solution_delete' => [self::ROLE_ADMIN],

        'module_access' => [self::ROLE_ADMIN],
        'module_show' => [self::ROLE_ADMIN],
        'module_create' => [self::ROLE_ADMIN],
        'module_edit' => [self::ROLE_ADMIN],
        'module_delete' => [self::ROLE_ADMIN],

        'feature_access' => [self::ROLE_ADMIN],
        'feature_show' => [self::ROLE_ADMIN],
        'feature_create' => [self::ROLE_ADMIN],
        'feature_edit' => [self::ROLE_ADMIN],
        'feature_delete' => [self::ROLE_ADMIN],

        'doc_version_access' => [self::ROLE_ADMIN],
        'doc_version_show' => [self::ROLE_ADMIN],
        'doc_version_create' => [self::ROLE_ADMIN],
        'doc_version_edit' => [self::ROLE_ADMIN],
        'doc_version_delete' => [self::ROLE_ADMIN],

        'doc_section_access' => [self::ROLE_ADMIN],
        'doc_section_show' => [self::ROLE_ADMIN],
        'doc_section_create' => [self::ROLE_ADMIN],
        'doc_section_edit' => [self::ROLE_ADMIN],
        'doc_section_delete' => [self::ROLE_ADMIN],

        'doc_page_access' => [self::ROLE_ADMIN],
        'doc_page_show' => [self::ROLE_ADMIN],
        'doc_page_create' => [self::ROLE_ADMIN],
        'doc_page_edit' => [self::ROLE_ADMIN],
        'doc_page_delete' => [self::ROLE_ADMIN],

        'faq_module_access' => [self::ROLE_ADMIN],
        'faq_module_show' => [self::ROLE_ADMIN],
        'faq_module_create' => [self::ROLE_ADMIN],
        'faq_module_edit' => [self::ROLE_ADMIN],
        'faq_module_delete' => [self::ROLE_ADMIN],

        'external_code_access' => [self::ROLE_ADMIN],
        'external_code_show' => [self::ROLE_ADMIN],
        'external_code_create' => [self::ROLE_ADMIN],
        'external_code_edit' => [self::ROLE_ADMIN],
        'external_code_delete' => [self::ROLE_ADMIN],

        'carousel_widget_access' => [self::ROLE_ADMIN],
        'carousel_widget_show' => [self::ROLE_ADMIN],
        'carousel_widget_create' => [self::ROLE_ADMIN],
        'carousel_widget_edit' => [self::ROLE_ADMIN],
        'carousel_widget_delete' => [self::ROLE_ADMIN],

        'pricing_plan_access' => [self::ROLE_ADMIN],
        'pricing_plan_show' => [self::ROLE_ADMIN],
        'pricing_plan_create' => [self::ROLE_ADMIN],
        'pricing_plan_edit' => [self::ROLE_ADMIN],
        'pricing_plan_delete' => [self::ROLE_ADMIN],

        'pricing_booster_access' => [self::ROLE_ADMIN],
        'pricing_booster_show' => [self::ROLE_ADMIN],
        'pricing_booster_create' => [self::ROLE_ADMIN],
        'pricing_booster_edit' => [self::ROLE_ADMIN],
        'pricing_booster_delete' => [self::ROLE_ADMIN],

        'pricing_feature_access' => [self::ROLE_ADMIN],
        'pricing_feature_show' => [self::ROLE_ADMIN],
        'pricing_feature_create' => [self::ROLE_ADMIN],
        'pricing_feature_edit' => [self::ROLE_ADMIN],
        'pricing_feature_delete' => [self::ROLE_ADMIN],

        'course_category_access' => [self::ROLE_ADMIN],
        'course_category_show' => [self::ROLE_ADMIN],
        'course_category_create' => [self::ROLE_ADMIN],
        'course_category_edit' => [self::ROLE_ADMIN],
        'course_category_delete' => [self::ROLE_ADMIN],

        'course_access' => [self::ROLE_ADMIN],
        'course_show' => [self::ROLE_ADMIN],
        'course_create' => [self::ROLE_ADMIN],
        'course_edit' => [self::ROLE_ADMIN],
        'course_delete' => [self::ROLE_ADMIN],

        'course_video_access' => [self::ROLE_ADMIN],
        'course_video_show' => [self::ROLE_ADMIN],
        'course_video_create' => [self::ROLE_ADMIN],
        'course_video_edit' => [self::ROLE_ADMIN],
        'course_video_delete' => [self::ROLE_ADMIN],

        'live_session_access' => [self::ROLE_ADMIN],
        'live_session_show' => [self::ROLE_ADMIN],
        'live_session_create' => [self::ROLE_ADMIN],
        'live_session_edit' => [self::ROLE_ADMIN],
        'live_session_delete' => [self::ROLE_ADMIN],

        'presenter_access' => [self::ROLE_ADMIN],
        'presenter_show' => [self::ROLE_ADMIN],
        'presenter_create' => [self::ROLE_ADMIN],
        'presenter_edit' => [self::ROLE_ADMIN],
        'presenter_delete' => [self::ROLE_ADMIN],

        'session_registration_access' => [self::ROLE_ADMIN],
        'session_registration_show' => [self::ROLE_ADMIN],
        'session_registration_create' => [self::ROLE_ADMIN],
        'session_registration_edit' => [self::ROLE_ADMIN],
        'session_registration_delete' => [self::ROLE_ADMIN],

        'sticky_menu_item_access' => [self::ROLE_ADMIN],
        'sticky_menu_item_show' => [self::ROLE_ADMIN],
        'sticky_menu_item_create' => [self::ROLE_ADMIN],
        'sticky_menu_item_edit' => [self::ROLE_ADMIN],
        'sticky_menu_item_delete' => [self::ROLE_ADMIN],

        'vacancy_access' => [self::ROLE_ADMIN],
        'vacancy_show' => [self::ROLE_ADMIN],
        'vacancy_create' => [self::ROLE_ADMIN],
        'vacancy_edit' => [self::ROLE_ADMIN],
        'vacancy_delete' => [self::ROLE_ADMIN],

        'job_application_access' => [self::ROLE_ADMIN],
        'job_application_show' => [self::ROLE_ADMIN],
        'job_application_create' => [self::ROLE_ADMIN],
        'job_application_edit' => [self::ROLE_ADMIN],
        'job_application_delete' => [self::ROLE_ADMIN],

        'customer_access' => [self::ROLE_ADMIN],
        'customer_show' => [self::ROLE_ADMIN],
        'customer_create' => [self::ROLE_ADMIN],
        'customer_edit' => [self::ROLE_ADMIN],
        'customer_delete' => [self::ROLE_ADMIN],

        'contact_access' => [self::ROLE_ADMIN],
        'contact_show' => [self::ROLE_ADMIN],
        'contact_create' => [self::ROLE_ADMIN],
        'contact_edit' => [self::ROLE_ADMIN],
        'contact_delete' => [self::ROLE_ADMIN],

        'contact_form_access' => [self::ROLE_ADMIN],
        'contact_form_show' => [self::ROLE_ADMIN],
        'contact_form_create' => [self::ROLE_ADMIN],
        'contact_form_edit' => [self::ROLE_ADMIN],
        'contact_form_delete' => [self::ROLE_ADMIN],

        'organization_access' => [self::ROLE_ADMIN],
        'organization_show' => [self::ROLE_ADMIN],
        'organization_create' => [self::ROLE_ADMIN],
        'organization_edit' => [self::ROLE_ADMIN],
        'organization_delete' => [self::ROLE_ADMIN],

        'email_log_access' => [self::ROLE_ADMIN],
        'email_log_show' => [self::ROLE_ADMIN],
        'email_log_create' => [self::ROLE_ADMIN],
        'email_log_edit' => [self::ROLE_ADMIN],
        'email_log_delete' => [self::ROLE_ADMIN],

        'analytics_access' => [self::ROLE_ADMIN],
        'analytics_show' => [self::ROLE_ADMIN],
        'analytics_create' => [self::ROLE_ADMIN],
        'analytics_edit' => [self::ROLE_ADMIN],
        'analytics_delete' => [self::ROLE_ADMIN],

        'activity_log_access' => [self::ROLE_ADMIN],
        'activity_log_show' => [self::ROLE_ADMIN],
        'activity_log_create' => [self::ROLE_ADMIN],
        'activity_log_edit' => [self::ROLE_ADMIN],
        'activity_log_delete' => [self::ROLE_ADMIN],

        'general_access' => [self::ROLE_ADMIN],
        'general_show' => [self::ROLE_ADMIN],
        'general_create' => [self::ROLE_ADMIN],
        'general_edit' => [self::ROLE_ADMIN],
        'general_delete' => [self::ROLE_ADMIN],

        'contact_page_access' => [self::ROLE_ADMIN],
        'contact_page_show' => [self::ROLE_ADMIN],
        'contact_page_create' => [self::ROLE_ADMIN],
        'contact_page_edit' => [self::ROLE_ADMIN],
        'contact_page_delete' => [self::ROLE_ADMIN],

        'ai_access' => [self::ROLE_ADMIN],
        'ai_show' => [self::ROLE_ADMIN],
        'ai_create' => [self::ROLE_ADMIN],
        'ai_edit' => [self::ROLE_ADMIN],
        'ai_delete' => [self::ROLE_ADMIN],

        'theme_access' => [self::ROLE_ADMIN],
        'theme_show' => [self::ROLE_ADMIN],
        'theme_create' => [self::ROLE_ADMIN],
        'theme_edit' => [self::ROLE_ADMIN],
        'theme_delete' => [self::ROLE_ADMIN],

        'login_access' => [self::ROLE_ADMIN],
        'login_show' => [self::ROLE_ADMIN],
        'login_create' => [self::ROLE_ADMIN],
        'login_edit' => [self::ROLE_ADMIN],
        'login_delete' => [self::ROLE_ADMIN],

        'hero_background_access' => [self::ROLE_ADMIN],
        'hero_background_show' => [self::ROLE_ADMIN],
        'hero_background_create' => [self::ROLE_ADMIN],
        'hero_background_edit' => [self::ROLE_ADMIN],
        'hero_background_delete' => [self::ROLE_ADMIN],

        'cookie_access' => [self::ROLE_ADMIN],
        'cookie_show' => [self::ROLE_ADMIN],
        'cookie_create' => [self::ROLE_ADMIN],
        'cookie_edit' => [self::ROLE_ADMIN],
        'cookie_delete' => [self::ROLE_ADMIN],

        'footer_link_access' => [self::ROLE_ADMIN],
        'footer_link_show' => [self::ROLE_ADMIN],
        'footer_link_create' => [self::ROLE_ADMIN],
        'footer_link_edit' => [self::ROLE_ADMIN],
        'footer_link_delete' => [self::ROLE_ADMIN],

        'mega_menu_access' => [self::ROLE_ADMIN],
        'mega_menu_show' => [self::ROLE_ADMIN],
        'mega_menu_create' => [self::ROLE_ADMIN],
        'mega_menu_edit' => [self::ROLE_ADMIN],
        'mega_menu_delete' => [self::ROLE_ADMIN],

        'social_media_platform_access' => [self::ROLE_ADMIN],
        'social_media_platform_show' => [self::ROLE_ADMIN],
        'social_media_platform_create' => [self::ROLE_ADMIN],
        'social_media_platform_edit' => [self::ROLE_ADMIN],
        'social_media_platform_delete' => [self::ROLE_ADMIN],

        'robots_txt_access' => [self::ROLE_ADMIN],
        'robots_txt_show' => [self::ROLE_ADMIN],
        'robots_txt_create' => [self::ROLE_ADMIN],
        'robots_txt_edit' => [self::ROLE_ADMIN],
        'robots_txt_delete' => [self::ROLE_ADMIN],

        'two_factor_access' => [self::ROLE_ADMIN],
        'two_factor_show' => [self::ROLE_ADMIN],
        'two_factor_create' => [self::ROLE_ADMIN],
        'two_factor_edit' => [self::ROLE_ADMIN],
        'two_factor_delete' => [self::ROLE_ADMIN],

        'image_optimizer_access' => [self::ROLE_ADMIN],
        'image_optimizer_show' => [self::ROLE_ADMIN],
        'image_optimizer_create' => [self::ROLE_ADMIN],
        'image_optimizer_edit' => [self::ROLE_ADMIN],
        'image_optimizer_delete' => [self::ROLE_ADMIN],

        'social_setting_access' => [self::ROLE_ADMIN],
        'social_setting_show' => [self::ROLE_ADMIN],
        'social_setting_create' => [self::ROLE_ADMIN],
        'social_setting_edit' => [self::ROLE_ADMIN],
        'social_setting_delete' => [self::ROLE_ADMIN],

        'mail_access' => [self::ROLE_ADMIN],
        'mail_show' => [self::ROLE_ADMIN],
        'mail_create' => [self::ROLE_ADMIN],
        'mail_edit' => [self::ROLE_ADMIN],
        'mail_delete' => [self::ROLE_ADMIN],

        'translation_access' => [self::ROLE_ADMIN],
        'translation_show' => [self::ROLE_ADMIN],
        'translation_create' => [self::ROLE_ADMIN],
        'translation_edit' => [self::ROLE_ADMIN],
        'translation_delete' => [self::ROLE_ADMIN],

        'persona_access' => [self::ROLE_ADMIN],
        'persona_show' => [self::ROLE_ADMIN],
        'persona_create' => [self::ROLE_ADMIN],
        'persona_edit' => [self::ROLE_ADMIN],
        'persona_delete' => [self::ROLE_ADMIN],

        'content_type_access' => [self::ROLE_ADMIN],
        'content_type_show' => [self::ROLE_ADMIN],
        'content_type_create' => [self::ROLE_ADMIN],
        'content_type_edit' => [self::ROLE_ADMIN],
        'content_type_delete' => [self::ROLE_ADMIN],

        'testimonial_access' => [self::ROLE_ADMIN],
        'testimonial_show' => [self::ROLE_ADMIN],
        'testimonial_create' => [self::ROLE_ADMIN],
        'testimonial_edit' => [self::ROLE_ADMIN],
        'testimonial_delete' => [self::ROLE_ADMIN],

        'product_feature_access' => [self::ROLE_ADMIN],
        'product_feature_show' => [self::ROLE_ADMIN],
        'product_feature_create' => [self::ROLE_ADMIN],
        'product_feature_edit' => [self::ROLE_ADMIN],
        'product_feature_delete' => [self::ROLE_ADMIN],

        'help_article_access' => [self::ROLE_ADMIN],
        'help_article_show' => [self::ROLE_ADMIN],
        'help_article_create' => [self::ROLE_ADMIN],
        'help_article_edit' => [self::ROLE_ADMIN],
        'help_article_delete' => [self::ROLE_ADMIN],

        'case_study_access' => [self::ROLE_ADMIN],
        'case_study_show' => [self::ROLE_ADMIN],
        'case_study_create' => [self::ROLE_ADMIN],
        'case_study_edit' => [self::ROLE_ADMIN],
        'case_study_delete' => [self::ROLE_ADMIN],

        'marketing_event_access' => [self::ROLE_ADMIN],
        'marketing_event_show' => [self::ROLE_ADMIN],
        'marketing_event_create' => [self::ROLE_ADMIN],
        'marketing_event_edit' => [self::ROLE_ADMIN],
        'marketing_event_delete' => [self::ROLE_ADMIN],

        'intent_brief_access' => [self::ROLE_ADMIN],
        'intent_brief_show' => [self::ROLE_ADMIN],
        'intent_brief_create' => [self::ROLE_ADMIN],
        'intent_brief_edit' => [self::ROLE_ADMIN],
        'intent_brief_delete' => [self::ROLE_ADMIN],

        'content_plan_access' => [self::ROLE_ADMIN],
        'content_plan_show' => [self::ROLE_ADMIN],
        'content_plan_create' => [self::ROLE_ADMIN],
        'content_plan_edit' => [self::ROLE_ADMIN],
        'content_plan_delete' => [self::ROLE_ADMIN],

    ];

    public static function hasPermission(string $permission): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        // Admin has access to everything
        if ($user->hasRole(self::ROLE_ADMIN)) {
            return true;
        }

        // Check the roles allowed for the permission
        if (isset(self::$fullPermissions[$permission])) {
            $allowedRoles = self::$fullPermissions[$permission];
            foreach ($allowedRoles as $role) {
                if ($user->hasRole($role)) {
                    return true;
                }
            }
        }

        return $user->hasPermissionTo($permission);
    }

    /**
     * Gets the allowed roles for a specific permission
     */
    public static function getAllowedRoles(string $permission): array
    {
        return self::$fullPermissions[$permission] ?? [];
    }

    /**
     * Gets all permissions that the user has
     */
    public static function getUserPermissions(): array
    {
        $user = auth()->user();
        if (! $user) {
            return [];
        }

        $userPermissions = [];

        foreach (self::$fullPermissions as $permission => $roles) {
            if (
                $user->hasRole(self::ROLE_ADMIN) ||
                $user->hasAnyRole($roles) ||
                $user->hasPermissionTo($permission)
            ) {
                $userPermissions[] = $permission;
            }
        }

        return $userPermissions;
    }

    /**
     * Gets all permissions for a specific module
     */
    public static function getModulePermissions(string $module): array
    {
        $modulePermissions = [];
        $prefix = $module.'_';

        foreach (array_keys(self::$fullPermissions) as $permission) {
            if (str_starts_with($permission, $prefix)) {
                $modulePermissions[] = $permission;
            }
        }

        return $modulePermissions;
    }

    /**
     * Checks if the user has access permission to a module
     */
    public static function hasModuleAccess(string $module): bool
    {
        return self::hasPermission($module.'_access');
    }
}
