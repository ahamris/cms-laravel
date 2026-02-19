<?php

namespace Database\Seeders;

use App\Models\About;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (About::query()->exists()) {
            return;
        }

        About::create([
            'anchor' => 'about-us',
            'nav_title' => 'About Us',
            'title' => 'About OpenPublicatie',
            'subtitle' => 'Your trusted partner in business automation and digital transformation',
            'short_body' => 'OpenPublicatie is a leading provider of comprehensive business management solutions designed to streamline your operations and accelerate growth.',
            'long_body' => 'Founded with the vision of empowering businesses through technology, OpenPublicatie has been at the forefront of digital transformation for companies of all sizes. Our comprehensive suite of tools includes CRM management, automated invoicing, project management, and seamless integrations that help businesses operate more efficiently and effectively.',
            'list_items' => [
                'Over 10 years of experience in business automation',
                'Trusted by thousands of companies worldwide',
                'Comprehensive suite of integrated business tools',
                'Dedicated customer support and training',
                'Continuous innovation and feature development',
                'Secure, reliable, and scalable solutions'
            ],
            'link_text' => 'Learn More About Our Story →',
            'testimonial_quote' => 'OpenPublicatie has transformed how we manage our business. The integrated approach and user-friendly interface have made our operations so much more efficient.',
            'testimonial_author' => 'Sarah Johnson',
            'testimonial_company' => 'TechStart Solutions',
            'image_position' => 'right',
            'sort_order' => 1,
            'is_active' => true,
            'slug' => 'about-openpublicatie',
            'meta_title' => 'About OpenPublicatie - Business Automation Solutions',
            'meta_description' => 'Learn about OpenPublicatie, your trusted partner in business automation and digital transformation. Discover our story, mission, and commitment to your success.',
            'meta_keywords' => 'about openpublicatie, business automation, digital transformation, company story, mission, values'
        ]);
    }
}
