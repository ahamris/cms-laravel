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
            return;
        }

        $this->seedCtaElements();
        $this->seedFaqElements();
    }

    private function seedCtaElements(): void
    {
        if (Element::where('type', ElementType::Cta)->where('title', 'Klaar om te starten?')->exists()) {
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
        if (Element::where('type', ElementType::Faq)->where('title', 'Veelgestelde vragen')->exists()) {
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
}
