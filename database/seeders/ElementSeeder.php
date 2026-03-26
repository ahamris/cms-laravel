<?php

namespace Database\Seeders;

use App\Enums\ElementType;
use App\Models\Element;
use Illuminate\Database\Seeder;

class ElementSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            // return;
        }

        $this->seedCtaElements();
        $this->seedFaqElements();
        $this->seedRelatedContentElements();
        $this->seedCardGridElements();
        $this->seedHeroSectionElements();
        $this->seedHeroVideoElements();
        $this->seedNewsletterElements();
        $this->seedFeatureElements();
    }

    private function seedCtaElements(): void
    {
        if (Element::where('type', ElementType::Cta)->exists()) {
            return;
        }

        Element::create([
            'type' => ElementType::Cta,
            'title' => 'Klaar om te starten?',
            'sub_title' => 'Neem vandaag nog contact op en ontdek wat wij voor u kunnen betekenen.',
            'options' => [
                'button_text' => 'Neem contact op',
                'button_url' => '/contact',
                'button_style' => 'primary',
                'background' => 'gradient',
                'alignment' => 'center',
            ],
        ]);

        Element::create([
            'type' => ElementType::Cta,
            'title' => 'Gratis demo aanvragen',
            'sub_title' => 'Ervaar zelf hoe ons platform werkt met een vrijblijvende demonstratie.',
            'options' => [
                'button_text' => 'Plan een demo',
                'button_url' => '/demo',
                'button_style' => 'secondary',
                'background' => 'light',
                'alignment' => 'left',
            ],
        ]);
    }

    private function seedFaqElements(): void
    {
        if (Element::where('type', ElementType::Faq)->exists()) {
            return;
        }

        Element::create([
            'type' => ElementType::Faq,
            'title' => 'Veelgestelde vragen',
            'sub_title' => 'Hier vindt u antwoorden op de meest gestelde vragen.',
            'options' => [
                'items' => [
                    [
                        'question' => 'Wat is de doorlooptijd van een implementatie?',
                        'answer' => 'Een standaard implementatie duurt gemiddeld 2 tot 4 weken, afhankelijk van de complexiteit en gewenste integraties.',
                    ],
                    [
                        'question' => 'Kan ik het platform eerst uitproberen?',
                        'answer' => 'Ja, wij bieden een gratis proefperiode van 14 dagen aan. U kunt alle functionaliteiten testen zonder verplichtingen.',
                    ],
                    [
                        'question' => 'Welke ondersteuning bieden jullie?',
                        'answer' => 'Wij bieden support via e-mail, telefoon en live chat. Daarnaast heeft u toegang tot onze uitgebreide kennisbank en documentatie.',
                    ],
                    [
                        'question' => 'Is mijn data veilig?',
                        'answer' => 'Absoluut. Wij hanteren strikte beveiligingsprotocollen en voldoen aan de AVG. Alle data wordt versleuteld opgeslagen op servers binnen de EU.',
                    ],
                ],
                'layout' => 'accordion',
                'columns' => 1,
            ],
        ]);

        Element::create([
            'type' => ElementType::Faq,
            'title' => 'Vragen over prijzen',
            'sub_title' => null,
            'options' => [
                'items' => [
                    [
                        'question' => 'Zijn er verborgen kosten?',
                        'answer' => 'Nee, alle kosten staan duidelijk vermeld in onze prijsplannen. Wat u ziet is wat u betaalt.',
                    ],
                    [
                        'question' => 'Kan ik op elk moment opzeggen?',
                        'answer' => 'Ja, u kunt op elk moment uw abonnement opzeggen. Er geldt geen minimale contractduur.',
                    ],
                    [
                        'question' => 'Bieden jullie korting voor jaarabonnementen?',
                        'answer' => 'Ja, bij een jaarabonnement ontvangt u 20% korting ten opzichte van maandelijkse betaling.',
                    ],
                ],
                'layout' => 'accordion',
                'columns' => 1,
            ],
        ]);
    }

    private function seedRelatedContentElements(): void
    {
        if (Element::where('type', ElementType::RelatedContent)->exists()) {
            return;
        }

        Element::create([
            'type' => ElementType::RelatedContent,
            'title' => 'Verder lezen',
            'sub_title' => null,
            'description' => null,
            'options' => [
                'layout' => 'grid',
                'columns' => 3,
                'items' => [
                    [
                        'title' => 'Documentatie',
                        'url' => '/docs',
                        'excerpt' => 'Technische documentatie en API-referentie.',
                    ],
                    [
                        'title' => 'Blog',
                        'url' => '/blog',
                        'excerpt' => 'Nieuws en artikelen.',
                    ],
                    [
                        'title' => 'Contact',
                        'url' => '/contact',
                        'excerpt' => 'Neem contact op met het team.',
                    ],
                ],
            ],
        ]);
    }

    private function seedCardGridElements(): void
    {
        if (Element::where('type', ElementType::CardGrid)->exists()) {
            return;
        }

        Element::create([
            'type' => ElementType::CardGrid,
            'title' => 'Strategische controle over digitale infrastructuur',
            'sub_title' => 'VOOR BELEIDSMAKERS',
            'description' => 'Ministeries bepalen samen de richting van het platform en behouden volledige eigenaarschap over kritieke systemen.',
            'options' => [
                'label' => 'VOOR BELEIDSMAKERS',
                'title' => 'Strategische controle over digitale infrastructuur',
                'description' => 'Ministeries bepalen samen de richting van het platform en behouden volledige eigenaarschap over kritieke systemen.',
                'cards' => [
                    [
                        'label' => 'Voor organisaties',
                        'title' => 'Deelname zonder grote implementatie-inspanning',
                        'description' => 'Provincies en gemeenten sluiten aan op bestaande infrastructuur en besparen aanzienlijk op IT-kosten.',
                        'button_text' => 'Meer',
                        'button_link' => '#',
                    ],
                    [
                        'label' => 'Voor gebruikers',
                        'title' => 'Betere toegang tot overheidsgegevens',
                        'description' => 'Burgers en bedrijven vinden informatie sneller en gemakkelijker omdat deze centraal doorzoekbaar en consistent gepubliceerd is.',
                        'button_text' => 'Meer',
                        'button_link' => '#',
                    ],
                    [
                        'label' => 'Voor experts',
                        'title' => 'Technische vrijheid en innovatie',
                        'description' => 'Ontwikkelaars werken met open standaarden en kunnen het platform aanpassen aan specifieke behoeften van hun organisatie.',
                        'button_text' => 'Meer',
                        'button_link' => '#',
                    ],
                ],
            ],
        ]);
    }

    private function seedHeroVideoElements(): void
    {
        if (Element::where('type', ElementType::HeroVideo)->exists()) {
            return;
        }

        Element::create([
            'type' => ElementType::HeroVideo,
            'title' => 'Wij bouwen ecosystemen.',
            'sub_title' => 'Niet zomaar software.',
            'description' => 'Van verouderde legacy naar open, schaalbare digitale omgevingen, volledig in eigendom van de overheid. Zonder vendor lock-in. Zonder big tech. Binnen vier weken een werkende MVP.',
            'options' => [
                'video_path' => null,
                'primary_button_text' => 'Plan een adviesgesprek',
                'primary_button_url' => '/contact',
                'secondary_button_text' => 'Bekijk onze aanpak',
                'secondary_button_url' => '/aanpak',
            ],
        ]);
    }

    private function seedHeroSectionElements(): void
    {
        if (Element::where('type', ElementType::HeroSection)->exists()) {
            return;
        }

        Element::create([
            'type' => ElementType::HeroSection,
            'title' => 'Build better products faster',
            'sub_title' => 'Hero Sections',
            'description' => 'A generalized hero block schema for wireframe-driven frontend rendering.',
            'options' => [
                'variant' => 'hero_split_image',
                'layout' => 'split',
                'eyebrow' => 'Introducing',
                'media_type' => 'image',
                'media_url' => null,
                'image_path' => null,
                'primary_button_text' => 'Get started',
                'primary_button_url' => '/contact',
                'secondary_button_text' => 'Learn more',
                'secondary_button_url' => '/about',
                'background_style' => 'default',
                'text_alignment' => 'left',
            ],
        ]);
    }

    private function seedNewsletterElements(): void
    {
        if (Element::where('type', ElementType::Newsletter)->exists()) {
            return;
        }

        Element::create([
            'type' => ElementType::Newsletter,
            'title' => 'Sluit je aan bij Staterra',
            'sub_title' => 'Staterra wordt samen met publieke partners ontwikkeld.',
            'description' => 'Organisaties die willen samenwerken zijn welkom.',
            'options' => [
                'email_placeholder' => 'Je e-mailadres',
                'button_text' => 'Aanmelden',
                'submit_endpoint' => '/api/newsletter/subscribe',
                'terms_text' => 'Door aan te melden ga je akkoord met onze voorwaarden.',
            ],
        ]);
    }

    private function seedFeatureElements(): void
    {
        if (Element::where('type', ElementType::Feature)->exists()) {
            return;
        }

        Element::create([
            'type' => ElementType::Feature,
            'title' => 'Gefragmenteerde openbaarmaking vraagt om betere systemen',
            'sub_title' => 'Woo',
            'description' => 'Overheden publiceren informatie via verschillende kanalen zonder centrale toegang. Burgers zoeken vergeefs naar consistente, doorzoekbare gegevens. Woo-verzoeken worden handmatig verwerkt, wat leidt tot vertragingen en fouten.',
            'options' => [
                'section_label' => '01 Het probleem',
                'primary_button_text' => 'Verkennen',
                'primary_button_url' => '#',
                'image_path' => null,
            ],
        ]);

        Element::create([
            'type' => ElementType::Feature,
            'title' => 'Geintegreerde workflow van intake tot publicatie',
            'sub_title' => 'OPMS',
            'description' => 'OPMS is een end-to-end systeem dat intake, beoordeling, zoekmogelijkheden en publicatie integreert. AI-ondersteuning helpt bij validatie en metadata-verrijking. Alle documenten worden centraal gearchiveerd en tegelijk via meerdere kanalen gepubliceerd.',
            'options' => [
                'section_label' => '02 De oplossing',
                'primary_button_text' => 'Ontdekken',
                'primary_button_url' => '#',
                'image_path' => null,
            ],
        ]);

        Element::create([
            'type' => ElementType::Feature,
            'title' => 'Gedeelde basis voor alle overheidsprocessen',
            'sub_title' => 'Infrastructuur',
            'description' => 'Staterra bouwt gedeelde infrastructuur die overheden samen eigenaar zijn en beheren. Dit gaat verder dan Woo en schept basis voor standaardisatie en hergebruik in alle overheidsprocessen. Organisaties werken samen aan duurzame, schaalbare oplossingen.',
            'options' => [
                'section_label' => '03 De toekomst',
                'primary_button_text' => 'Meer informatie',
                'primary_button_url' => '#',
                'image_path' => null,
            ],
        ]);
    }
}
