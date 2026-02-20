<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates a few sample pages to verify API and admin (including template selector) work.
     */
    public function run(): void
    {
        if (Page::query()->exists()) {
            return;
        }

        $pages = [
            [
                'title' => 'Over ons',
                'slug' => 'over-ons',
                'short_body' => 'Leer meer over ons team, onze missie en hoe we bedrijven helpen groeien.',
                'long_body' => '<p>Welkom op onze overzichtspagina. Wij zijn een team van specialisten met een passie voor kwaliteit en resultaat.</p><h3>Onze missie</h3><p>We helpen ondernemers en bedrijven hun doelen te bereiken met heldere strategieën en duurzame oplossingen.</p><h3>Onze aanpak</h3><p>Van advies tot uitvoering: we denken mee en leveren maatwerk. Neem gerust contact op voor een kennismaking.</p>',
                'template' => 'default',
                'meta_title' => 'Over ons | Ons bedrijf',
                'meta_body' => 'Ontdek wie we zijn en wat we voor u kunnen betekenen.',
                'is_active' => true,
            ],
            [
                'title' => 'Contact',
                'slug' => 'contact',
                'short_body' => 'Neem contact met ons op. We helpen u graag verder.',
                'long_body' => '<p>Heeft u een vraag of wilt u een afspraak maken? Vul het formulier in of bel ons direct.</p><p>We reageren binnen één werkdag op alle berichten.</p>',
                'template' => 'minimal',
                'meta_title' => 'Contact',
                'is_active' => true,
            ],
            [
                'title' => 'Diensten',
                'slug' => 'diensten',
                'short_body' => 'Een overzicht van onze diensten en wat we voor u kunnen doen.',
                'long_body' => '<p>Van strategie tot implementatie: wij ondersteunen u in elke fase.</p><ul><li>Advies en begeleiding</li><li>Ontwikkeling op maat</li><li>Opleiding en support</li></ul>',
                'template' => 'landing',
                'meta_title' => 'Diensten',
                'is_active' => true,
            ],
            [
                'title' => 'Privacy & cookies',
                'slug' => 'privacy',
                'short_body' => 'Hoe we omgaan met uw gegevens en cookies.',
                'long_body' => '<p>We nemen uw privacy serieus. Op deze pagina leest u hoe we persoonsgegevens verwerken en welke cookies we gebruiken.</p><h3>Cookies</h3><p>We gebruiken functionele cookies en analytische cookies om de website te verbeteren.</p>',
                'template' => 'default',
                'meta_title' => 'Privacy & cookies',
                'is_active' => true,
            ],
            [
                'title' => 'Aanbod landing',
                'slug' => 'aanbod',
                'short_body' => 'Ontdek ons aanbod in één oogopslag.',
                'long_body' => '<p>Een korte landing over ons aanbod. Ideaal om bezoekers snel te informeren en door te sturen naar contact of een specifieke dienst.</p>',
                'template' => 'landing',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $data) {
            Page::create($data);
        }
    }
}
