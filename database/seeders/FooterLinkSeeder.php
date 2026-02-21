<?php

namespace Database\Seeders;

use App\Models\FooterLink;
use Illuminate\Database\Seeder;

class FooterLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $links = [
            // Column 1: Services & Support
            ['title' => 'Services', 'url' => '/services', 'column' => 1, 'order' => 1],
            ['title' => 'Support', 'url' => '/support', 'column' => 1, 'order' => 2],
            ['title' => 'Contact Us', 'url' => '/contact', 'column' => 1, 'order' => 3],
            ['title' => 'Help Center', 'url' => '/help', 'column' => 1, 'order' => 4],
            ['title' => 'FAQ', 'url' => '/faq', 'column' => 1, 'order' => 5],

            // Column 2: Information
            ['title' => 'About Us', 'url' => '/about', 'column' => 2, 'order' => 1],
            ['title' => 'Our Mission', 'url' => '/mission', 'column' => 2, 'order' => 2],
            ['title' => 'Team', 'url' => '/team', 'column' => 2, 'order' => 3],
            ['title' => 'Careers', 'url' => '/careers', 'column' => 2, 'order' => 4],
            ['title' => 'News', 'url' => '/news', 'column' => 2, 'order' => 5],

            // Column 3: Legal & Policies
            ['title' => 'Privacy Policy', 'url' => '/privacy', 'column' => 3, 'order' => 1],
            ['title' => 'Terms of Service', 'url' => '/terms', 'column' => 3, 'order' => 2],
            ['title' => 'Cookie Policy', 'url' => '/cookies', 'column' => 3, 'order' => 3],
            ['title' => 'Accessibility', 'url' => '/accessibility', 'column' => 3, 'order' => 4],
            ['title' => 'GDPR', 'url' => '/gdpr', 'column' => 3, 'order' => 5],

            // Column 4: Connect & Follow
            ['title' => 'Social Media', 'url' => '/social', 'column' => 4, 'order' => 1],
            ['title' => 'Newsletter', 'url' => '/newsletter', 'column' => 4, 'order' => 2],
            ['title' => 'Community', 'url' => '/community', 'column' => 4, 'order' => 3],
            ['title' => 'Partners', 'url' => '/partners', 'column' => 4, 'order' => 4],
            ['title' => 'Developers', 'url' => '/developers', 'column' => 4, 'order' => 5],
        ];

        if (FooterLink::count() == 0) {
            foreach ($links as $link) {
                FooterLink::create(
                    [
                        'title' => $link['title'],
                        'column' => $link['column'],
                        'url' => $link['url'],
                        'order' => $link['order'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
