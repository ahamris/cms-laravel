<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

class Variable
{
    public const int CACHE_TTL = 86400;

    public const string GUARD_NAME = 'web';

    public const string ROLE_ADMIN = 'admin';

    public const string ROLE_EDITOR = 'editor';

    public const string ROLE_USER = 'user';

    public static array $fullRoles = [
        self::ROLE_ADMIN,
        self::ROLE_EDITOR,
        self::ROLE_USER,
    ];

    public static function expiresAt(): Carbon
    {
        return now()->addSeconds(self::CACHE_TTL);
    }

    /**
     * Default admin accounts array
     * Format: [first_name, last_name, email, password, role]
     */
    public const array DEFAULT_ACCOUNTS = [
        ['Admin', 'User', 'admin@example.com', 'password', self::ROLE_ADMIN],
        // Add more default accounts here if needed
        // ['Editor', 'User', 'editor@example.com', 'password', self::ROLE_EDITOR],
    ];

    /**
     * Role display names
     *
     * @var array<string, string>
     */
    public static array $fullRolesSelector = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_EDITOR => 'Editor',
        self::ROLE_USER => 'User',
    ];

    /**
     * Permissions configuration
     * Format: 'permission_name' => [allowed_roles]
     */
    public static array $fullPermissions = [
        // Site Settings
        'site_setting' => [self::ROLE_ADMIN],
        'permission_manager' => [self::ROLE_ADMIN],

        // Media Management
        'media_access' => [self::ROLE_ADMIN],
        'media_show' => [self::ROLE_ADMIN],
        'media_create' => [self::ROLE_ADMIN],
        'media_edit' => [self::ROLE_ADMIN],
        'media_delete' => [self::ROLE_ADMIN],

        // User Management
        'user_access' => [self::ROLE_ADMIN],
        'user_show' => [self::ROLE_ADMIN],
        'user_create' => [self::ROLE_ADMIN],
        'user_edit' => [self::ROLE_ADMIN],
        'user_delete' => [self::ROLE_ADMIN],

        // Permission Management
        'permission_access' => [self::ROLE_ADMIN],
        'permission_show' => [self::ROLE_ADMIN],
        'permission_create' => [self::ROLE_ADMIN],
        'permission_edit' => [self::ROLE_ADMIN],
        'permission_delete' => [self::ROLE_ADMIN],

        // Menu Management
        'menu_access' => [self::ROLE_ADMIN],
        'menu_show' => [self::ROLE_ADMIN],
        'menu_create' => [self::ROLE_ADMIN],
        'menu_edit' => [self::ROLE_ADMIN],
        'menu_delete' => [self::ROLE_ADMIN],

        // Theme Management
        'theme_access' => [self::ROLE_ADMIN],
        'theme_show' => [self::ROLE_ADMIN],
        'theme_edit' => [self::ROLE_ADMIN],
    ];

    /**
     * Check if user has permission
     */
    public static function hasPermission(string $permission): bool
    {
        $user = auth()->user();
        if (!$user) {
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
        if (!$user) {
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
