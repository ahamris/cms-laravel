<?php

namespace Database\Seeders;

use App\Models\Admin\AdminMenu;
use App\Models\Admin\AdminMenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $menu = AdminMenu::updateOrCreate(
                ['slug' => 'admin-main'],
                [
                    'name' => 'Admin Sidebar',
                    'description' => 'Admin panel standard menu',
                    'position' => 0,
                    'is_active' => true,
                ]
            );

            AdminMenuItem::where('admin_menu_id', $menu->id)->delete();

            $position = 0;

            $pagesSection = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'item_type' => 'section',
                'label' => 'PAGES',
                'slug' => Str::slug('PAGES'),
                'position' => $position++,
                'is_active' => true,
            ]);

            AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $pagesSection->id,
                'item_type' => 'link',
                'label' => 'Dashboard',
                'slug' => 'dashboard',
                'route_name' => 'admin.home',
                'icon' => 'chart-line',
                'position' => 0,
                'is_active' => true,
            ]);


            $userManagement = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $pagesSection->id,
                'item_type' => 'link',
                'label' => 'User Management',
                'slug' => 'user-management',
                'route_name' => 'admin.users.index',
                'icon' => 'users',
                'active_pattern' => 'admin.users*|admin.roles*|admin.permissions*',
                'position' => 2,
                'is_active' => true,
            ]);

            $this->createChildren($userManagement, [
                ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => 'users'],
                ['label' => 'Roles', 'route' => 'admin.roles.index', 'icon' => 'user-tag'],
                ['label' => 'Permissions', 'route' => 'admin.permissions.index', 'icon' => 'key'],
            ]);

            $systemSection = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'item_type' => 'section',
                'label' => 'SYSTEM',
                'slug' => Str::slug('SYSTEM'),
                'position' => $position++,
                'is_active' => true,
            ]);

            $settings = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $systemSection->id,
                'item_type' => 'link',
                'label' => 'Settings',
                'slug' => 'settings',
                'route_name' => 'admin.settings',
                'icon' => 'cog',
                'active_pattern' => 'admin.settings*',
                'position' => 0,
                'is_active' => true,
            ]);

            $this->createChildren($settings, [
                ['label' => 'General Settings', 'route' => 'admin.settings.index', 'icon' => 'cog'],
                ['label' => 'Menu Management', 'route' => 'admin.settings.menu', 'icon' => 'bars-progress'],
                ['label' => 'Theme Management', 'route' => 'admin.settings.theme', 'icon' => 'palette'],
            ]);
        });
    }

    protected function createChildren(AdminMenuItem $parent, array $children): void
    {
        foreach ($children as $index => $child) {
            AdminMenuItem::create([
                'admin_menu_id' => $parent->admin_menu_id,
                'parent_id' => $parent->id,
                'item_type' => 'link',
                'label' => $child['label'],
                'slug' => Str::slug($child['label']),
                'route_name' => $child['route'] ?? null,
                'icon' => $child['icon'] ?? null,
                'active_pattern' => $child['pattern'] ?? null,
                'badge_text' => $child['badge'] ?? null,
                'badge_color' => $child['badge_color'] ?? null,
                'position' => $index,
                'is_active' => true,
            ]);
        }
    }
}
