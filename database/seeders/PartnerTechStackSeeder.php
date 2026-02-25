<?php

namespace Database\Seeders;

use App\Models\PartnerTechItem;
use Illuminate\Database\Seeder;

class PartnerTechStackSeeder extends Seeder
{
    /**
     * Seeds Partner (type=0) and Tech Stack (type=1) records. One record per type; data holds link items.
     */
    public function run(): void
    {
        
        if (app()->environment('production')) {
            return;
        }

        $defaultImage = 'images/dashboard-mockup.svg';

        if (! PartnerTechItem::partners()->exists()) {
            PartnerTechItem::create([
                'name' => 'Partners',
                'banner' => $defaultImage,
                'title' => 'Partners',
                'description' => 'Our partners and collaborators.',
                'type' => PartnerTechItem::TYPE_PARTNER,
                'data' => [
                    ['link' => 'https://laravel.com', 'link_type' => 'external', 'image' => $defaultImage, 'sort_order' => 0],
                    ['link' => 'https://livewire.laravel.com', 'link_type' => 'external', 'image' => $defaultImage, 'sort_order' => 1],
                ],
                'sort_order' => 0,
                'is_active' => true,
            ]);
        }

        if (PartnerTechItem::techStack()->exists()) {
            return;
        }

        $dataItems = [
            ['link' => 'https://react.dev', 'link_type' => 'external', 'image' => $defaultImage, 'sort_order' => 0],
            ['link' => 'https://laravel.com', 'link_type' => 'external', 'image' => $defaultImage, 'sort_order' => 1],
            ['link' => 'https://livewire.laravel.com', 'link_type' => 'external', 'image' => $defaultImage, 'sort_order' => 2],
            ['link' => 'https://alpinejs.dev', 'link_type' => 'external', 'image' => $defaultImage, 'sort_order' => 3],
            ['link' => 'https://laravel.com/docs/scout', 'link_type' => 'external', 'image' => $defaultImage, 'sort_order' => 4],
            ['link' => 'https://tailwindcss.com', 'link_type' => 'external', 'image' => $defaultImage, 'sort_order' => 5],
            ['link' => 'https://inertiajs.com', 'link_type' => 'external', 'image' => $defaultImage, 'sort_order' => 6],
            ['link' => 'https://vitejs.dev', 'link_type' => 'external', 'image' => $defaultImage, 'sort_order' => 7],
        ];

        PartnerTechItem::create([
            'name' => 'Tech Stack',
            'banner' => $defaultImage,
            'title' => 'Tech Stack',
            'description' => 'Technologies used in this application.',
            'type' => PartnerTechItem::TYPE_TECH_STACK,
            'data' => $dataItems,
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }
}
