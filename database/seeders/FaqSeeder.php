<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Seed the contact page FAQ module (identifier: contact).
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        if (Faq::where('identifier', 'contact')->exists()) {
            return;
        }

        Faq::create([
            'identifier' => 'contact',
            'title' => 'Veelgestelde vragen',
            'subtitle' => null,
            'items' => [
                [
                    'question' => 'Hoe helpt OPMS bij het halen van Woo-termijnen?',
                    'answer' => 'OPMS ondersteunt je met termijnbewaking, notificaties en overzicht per verzoek. Verzoeken komen binnen via het gebruikersportaal; status en vervolgstappen zijn in één scherm zichtbaar. Geen verzoek dat tussen wal en schip valt.',
                ],
                [
                    'question' => 'Kun je OPMS koppelen aan bestaande systemen?',
                    'answer' => 'Ja. OPMS biedt koppelingen met bestaande systemen via APIs en standaardintegraties. Onze documentatie en support helpen je bij de inrichting.',
                ],
                [
                    'question' => 'Hoe zit het met beveiliging en toegang?',
                    'answer' => 'OPMS werkt met role-based toegang en voldoet aan gangbare beveiligingsstandaarden. Toegang wordt per organisatie en rol beheerd.',
                ],
                [
                    'question' => 'Hebben gebruikers programmeerkennis nodig?',
                    'answer' => 'Nee. Het portaal is bedoeld voor gebruik zonder technische voorkennis. Beheerders kunnen geavanceerde instellingen doen waar nodig.',
                ],
                [
                    'question' => 'Sluit OPMS aan op Common Ground en de NDS?',
                    'answer' => 'OPMS sluit waar mogelijk aan op Common Ground en de Nationale Datastrategie (NDS) en ondersteunt daarmee overheidsbrede afspraken.',
                ],
                [
                    'question' => 'Hoe zit het met AI?',
                    'answer' => 'OPMS ondersteunt optioneel AI-functionaliteit voor onder meer zoeken en samenvatten. Dit is configureerbaar per omgeving.',
                ],
            ],
        ]);
    }
}
