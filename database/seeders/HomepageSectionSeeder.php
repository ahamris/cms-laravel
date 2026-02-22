<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSectionSeeder extends Seeder
{
    /**
     * Seed homepage sections with OpenPublicatie/OPMS content from the reference design.
     * Safe to run multiple times: uses updateOrCreate on section_key.
     */
    public function run(): void
    {
        $sections = [
            'hero' => [
                'section_name' => 'Hero',
                'content' => [
                    'label' => 'OPMS OPEN PUBLICATION PLATFORM',
                    'heading' => 'Grip op informatie van bron tot burger',
                    'paragraph' => 'Eén platform voor het beheren en publiceren van uw overheidsinformatie. Van bron tot burger, volledig in controle.',
                    'bullets' => [
                        ['icon' => 'check', 'text' => 'Eén centraal platform voor alle content'],
                        ['icon' => 'check', 'text' => 'Snel live met uw publicatieomgeving'],
                        ['icon' => 'check', 'text' => 'GWV-klaar en eenvoudig te koppelen'],
                    ],
                    'cta_primary_text' => 'Demo aanvragen',
                    'cta_primary_url' => '/demo',
                    'cta_secondary_text' => 'Meer informatie',
                    'cta_secondary_url' => '/meer-informatie',
                    'image' => '',
                ],
            ],
            'feature_cards' => [
                'section_name' => 'Feature cards',
                'content' => [
                    'cards' => [
                        [
                            'icon' => 'cog',
                            'title' => 'Een platform',
                            'description' => 'Alles op één plek: beheer, ontwerp en publiceer uw overheidsinformatie zonder versnipperde systemen.',
                            'link_text' => 'Lees meer',
                            'link_url' => '/een-platform',
                        ],
                        [
                            'icon' => 'clock',
                            'title' => 'Binnen 30 minuten live',
                            'description' => 'Uw publicatieomgeving staat snel live. Geen lange implementatietrajecten, direct aan de slag.',
                            'link_text' => 'Lees meer',
                            'link_url' => '/binnen-30-minuten-live',
                        ],
                        [
                            'icon' => 'network-wired',
                            'title' => 'GWV koppeling tussen',
                            'description' => 'Koppel eenvoudig met het Gemeenschappelijk Voorzieningenplatform en voldoe aan de eisen voor overheidsinformatie.',
                            'link_text' => 'Lees meer',
                            'link_url' => '/gwv-koppeling',
                        ],
                    ],
                ],
            ],
            'about_opms' => [
                'section_name' => 'About OPMS',
                'content' => [
                    'label' => 'OVER OPMS',
                    'heading' => 'Slimmer besturen met OPMS',
                    'paragraph' => 'OPMS ondersteunt overheidsorganisaties bij het efficiënt beheren en publiceren van informatie. Van beleidsstukken tot nieuws: alles op één plek, altijd actueel en eenvoudig te delen.',
                    'bullets' => [
                        ['icon' => 'check', 'text' => 'Eén bron van waarheid voor al uw content'],
                        ['icon' => 'check', 'text' => 'Workflows die aansluiten op uw processen'],
                        ['icon' => 'check', 'text' => 'Integraties met bestaande systemen'],
                    ],
                    'link_text' => 'Meer over OPMS',
                    'link_url' => '/over-opms',
                    'image' => '',
                ],
            ],
            'how_it_works' => [
                'section_name' => 'How it works',
                'content' => [
                    'title' => 'Hoe het werkt',
                    'steps' => [
                        ['number' => '1', 'title' => 'Contact OPMS', 'description' => 'Neem contact met ons op. We bespreken uw wensen en mogelijkheden.'],
                        ['number' => '2', 'title' => 'Ontwerpen', 'description' => 'Samen ontwerpen we uw publicatieomgeving en bepalen we de inrichting.'],
                        ['number' => '3', 'title' => 'Voldoen aan', 'description' => 'U gaat live en voldoet eenvoudig aan de eisen voor overheidsinformatie.'],
                    ],
                ],
            ],
            'user_features' => [
                'section_name' => 'User features',
                'content' => [
                    'left_title' => 'Voor de ontwikkelaar',
                    'left_items' => ['API', 'SDK', 'Webhook', 'REST API', 'Open source'],
                    'right_title' => 'Voor de gebruiker',
                    'right_items' => ['Makkelijk te gebruiken', 'Intuïtief', 'Snel', 'Betrouwbaar'],
                ],
            ],
            'competition' => [
                'section_name' => 'Competition',
                'content' => [
                    'heading' => 'Waarom OPMS de concurrentie uitschakelt',
                    'paragraph' => 'Met OPMS kiest u voor een platform dat specifiek is gebouwd voor overheidsinformatie. Geen generieke oplossingen, maar maatwerk dat aansluit op wet- en regelgeving en uw manier van werken.',
                    'boxes' => [
                        ['value' => '100%', 'label' => 'Focus op overheidsinformatie'],
                        ['value' => 'GWV', 'label' => 'Klaar voor Gemeenschappelijk Voorzieningenplatform'],
                        ['value' => '30m', 'label' => 'Binnen 30 minuten live'],
                        ['value' => 'API', 'label' => 'Volledige API voor integraties'],
                    ],
                ],
            ],
            'latest_updates' => [
                'section_name' => 'Latest updates',
                'content' => [
                    'title' => 'Laatste updates',
                ],
            ],
            'bottom_cta' => [
                'section_name' => 'Bottom CTA',
                'content' => [
                    'heading' => 'Slimmer werken begint met een demo.',
                    'subtext' => 'Ontdek wat OPMS voor uw organisatie kan betekenen. Vraag een vrijblijvende demo aan.',
                    'cta_primary_text' => 'Demo aanvragen',
                    'cta_primary_url' => '/demo',
                    'cta_secondary_text' => 'Meer informatie',
                    'cta_secondary_url' => '/meer-informatie',
                ],
            ],
        ];

        foreach (HomepageSection::SECTION_KEYS as $sortOrder => $sectionKey) {
            $data = $sections[$sectionKey] ?? null;
            if ($data === null) {
                continue;
            }

            HomepageSection::createOrFirst(
                ['section_key' => $sectionKey],
                [
                    'section_name' => $data['section_name'],
                    'module_type' => 'content',
                    'sort_order' => $sortOrder,
                    'is_active' => true,
                    'content' => $data['content'],
                ]
            );
        }

        \Illuminate\Support\Facades\Cache::forget(HomepageSection::CACHE_KEY);
    }
}
