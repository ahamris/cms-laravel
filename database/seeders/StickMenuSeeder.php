<?php

namespace Database\Seeders;

use App\Models\StickyMenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StickMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createStickyMenuItems();
    }

    private function createStickyMenuItems(): void
    {

        if (StickyMenuItem::count() > 0) {
            return;
        }

            $menuItems = [
                [
                    'title' => 'Knowledgebase',
                    'icon' => 'fa fa-book',
                    'link' => '#',
                    'link_type' => 'internal',
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'title' => 'Changelog',
                    'icon' => 'fa-solid fa-list-ul',
                    'link' => '#',
                    'link_type' => 'internal',
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'title' => 'Helpdesk',
                    'icon' => 'fa-solid fa-headset',
                    'link' => '#',
                    'link_type' => 'internal',
                    'is_active' => true,
                    'sort_order' => 3,
                ],
            ];
    
            foreach ($menuItems as $item) {
                StickyMenuItem::firstOrCreate(
                    ['title' => $item['title']],
                    $item
                );
            }
    
            $this->command->info('✅ Sticky menu items created successfully.');
    }
}
