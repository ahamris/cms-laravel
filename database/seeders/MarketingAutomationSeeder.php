<?php

namespace Database\Seeders;

use App\Models\MarketingPersona;
use App\Models\ContentType;
use App\Models\MarketingTestimonial;
use App\Models\ProductFeature;
use Illuminate\Database\Seeder;

class MarketingAutomationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedMarketingPersonas();
        $this->seedContentTypes();
        $this->seedProductFeatures();
        $this->seedMarketingTestimonials();
    }

    private function seedMarketingPersonas(): void
    {
        $personas = [
            [
                'name' => 'Startende Ondernemer',
                'slug' => 'startende-ondernemer',
                'description' => 'Ondernemers die net beginnen en op zoek zijn naar eenvoudige, betaalbare oplossingen.',
                'demographics' => [
                    'age_range' => '25-40',
                    'company_size' => '1-5 medewerkers',
                    'industry' => 'Diverse',
                    'location' => 'Nederland'
                ],
                'pain_points' => [
                    'Beperkt budget voor software',
                    'Weinig tijd voor complexe implementaties',
                    'Gebrek aan technische kennis',
                    'Behoefte aan snelle resultaten'
                ],
                'goals' => [
                    'Administratie automatiseren',
                    'Tijd besparen',
                    'Professioneel overkomen',
                    'Groei faciliteren'
                ],
                'preferred_channels' => ['Email', 'Social Media', 'Webinars'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Groeiende MKB',
                'slug' => 'groeiende-mkb',
                'description' => 'Middelgrote bedrijven die snel groeien en schaalbaarheid zoeken.',
                'demographics' => [
                    'age_range' => '30-50',
                    'company_size' => '10-50 medewerkers',
                    'industry' => 'Dienstverlening, Handel',
                    'location' => 'Nederland, België'
                ],
                'pain_points' => [
                    'Systemen die niet meegroeien',
                    'Gebrek aan integraties',
                    'Inefficiënte processen',
                    'Compliance uitdagingen'
                ],
                'goals' => [
                    'Processen optimaliseren',
                    'Schaalbaarheid realiseren',
                    'Kosten beheersen',
                    'Compliance waarborgen'
                ],
                'preferred_channels' => ['Direct Sales', 'Webinars', 'Case Studies'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise Organisatie',
                'slug' => 'enterprise-organisatie',
                'description' => 'Grote organisaties met complexe behoeften en hoge eisen.',
                'demographics' => [
                    'age_range' => '35-60',
                    'company_size' => '100+ medewerkers',
                    'industry' => 'Overheid, Grote Corporaties',
                    'location' => 'Europa'
                ],
                'pain_points' => [
                    'Legal systemen',
                    'Complexe integraties',
                    'Strenge security eisen',
                    'Lange besluitvormingsprocessen'
                ],
                'goals' => [
                    'Digitale transformatie',
                    'Risk management',
                    'Efficiency verbetering',
                    'Compliance & Security'
                ],
                'preferred_channels' => ['Direct Sales', 'Whitepapers', 'Consultancy'],
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($personas as $persona) {
            MarketingPersona::create($persona);
        }
    }

    private function seedContentTypes(): void
    {
        $contentTypes = [
            [
                'name' => 'Blog Artikel',
                'slug' => 'blog-artikel',
                'description' => 'Informatieve artikelen voor SEO en thought leadership',
                'icon' => 'fa-newspaper',
                'color' => '#3b82f6',
                'applicable_models' => ['App\Models\Blog'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Whitepaper',
                'slug' => 'whitepaper',
                'description' => 'Diepgaande technische documenten',
                'icon' => 'fa-file-alt',
                'color' => '#6366f1',
                'applicable_models' => ['App\Models\Blog', 'App\Models\Page'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Case Study',
                'slug' => 'case-study',
                'description' => 'Klantensuccessen en praktijkvoorbeelden',
                'icon' => 'fa-chart-line',
                'color' => '#10b981',
                'applicable_models' => ['App\Models\Blog', 'App\Models\CaseStudy'],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Handleiding',
                'slug' => 'handleiding',
                'description' => 'Stap-voor-stap instructies en tutorials',
                'icon' => 'fa-book',
                'color' => '#f59e0b',
                'applicable_models' => ['App\Models\HelpArticle', 'App\Models\Blog'],
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Landing Page',
                'slug' => 'landing-page',
                'description' => 'Conversie-geoptimaliseerde paginas',
                'icon' => 'fa-bullseye',
                'color' => '#ef4444',
                'applicable_models' => ['App\Models\Page', 'App\Models\Service'],
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($contentTypes as $contentType) {
            ContentType::create($contentType);
        }
    }

    private function seedProductFeatures(): void
    {
        $features = [
            [
                'name' => 'Automatische Facturatie',
                'slug' => 'automatische-facturatie',
                'description' => 'Genereer en verstuur facturen automatisch',
                'icon' => 'fa-file-invoice',
                'category' => 'Financieel',
                'benefits' => [
                    'Tijd besparen',
                    'Minder fouten',
                    'Snellere betaling',
                    'Professionele uitstraling'
                ],
                'is_premium' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'CRM Integratie',
                'slug' => 'crm-integratie',
                'description' => 'Koppel je klantgegevens aan externe CRM systemen',
                'icon' => 'fa-users',
                'category' => 'Integraties',
                'benefits' => [
                    'Centrale klantdata',
                    'Betere klantrelaties',
                    'Geautomatiseerde workflows',
                    'Verbeterde sales'
                ],
                'is_premium' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Rapportage Dashboard',
                'slug' => 'rapportage-dashboard',
                'description' => 'Real-time inzicht in je bedrijfsprestaties',
                'icon' => 'fa-chart-bar',
                'category' => 'Analytics',
                'benefits' => [
                    'Data-driven beslissingen',
                    'Trends identificeren',
                    'Performance monitoring',
                    'ROI inzicht'
                ],
                'is_premium' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($features as $feature) {
            ProductFeature::create($feature);
        }
    }

    private function seedMarketingTestimonials(): void
    {
        $testimonials = [
            [
                'customer_name' => 'Jan van der Berg',
                'company' => 'Berg Consultancy',
                'position' => 'Directeur',
                'quote' => 'OpenPublicatie heeft ons geholpen om onze administratie volledig te automatiseren. We besparen nu 10 uur per week!',
                'rating' => 5,
                'tags' => ['Automatische Facturatie', 'Tijdsbesparing'],
                'featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'customer_name' => 'Maria Jansen',
                'company' => 'Jansen & Partners',
                'position' => 'CEO',
                'quote' => 'De CRM integratie heeft onze sales verhoogd met 30%. Eindelijk hebben we alle klantdata op één plek.',
                'rating' => 5,
                'tags' => ['CRM Integratie', 'Sales Verbetering'],
                'featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'customer_name' => 'Pieter de Vries',
                'company' => 'De Vries Bouwbedrijf',
                'position' => 'Eigenaar',
                'quote' => 'Het rapportage dashboard geeft ons precies het inzicht dat we nodig hebben om betere beslissingen te nemen.',
                'rating' => 4,
                'tags' => ['Rapportage Dashboard', 'Business Intelligence'],
                'featured' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            MarketingTestimonial::create($testimonial);
        }
    }
}
