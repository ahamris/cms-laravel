<?php

namespace Database\Seeders;

use App\Models\MegaMenuItem;
use Illuminate\Database\Seeder;

/**
 * Seeds header mega menu to match Websiteplan OpenPublication.EU (sectie 3. Sitemap).
 * Hoofdmenu: Regie op informatie · Leren & Doen · Op de hoogte · Achter OpenPublication · In gesprek
 * CTA: Demo aanvragen, Contact
 * All URLs use api_path() for headless API endpoints.
 */
class MegaMenuSeeder extends Seeder
{
    public function run(): void
    {
        if (MegaMenuItem::query()->exists()) {
            return;
        }

        $primaryDark = '#001f4c';
        $primary = '#1f64aa';
        $secondary = '#709bc1';

        // 2. Regie op informatie (Oplossingen) – mega menu
        $regie = MegaMenuItem::create([
            'parent_id' => null,
            'order' => 1,
            'title' => 'Regie op informatie',
            'subtitle' => 'Oplossingen voor Woo, informatiehuishouding, openbaarmaking en archivering.',
            'description' => null,
            'icon' => 'fas fa-layer-group',
            'icon_bg_color' => $primary,
            'url' => api_path('solutions'),
            'is_mega_menu' => true,
            'is_active' => true,
            'open_in_new_tab' => false,
            'tags' => ['nav', 'dropdown'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $regie->id,
            'order' => 1,
            'title' => 'Woo-verzoeken',
            'subtitle' => 'Afhandeling & Dossierbeheer',
            'icon' => 'fas fa-folder-open',
            'icon_bg_color' => $secondary,
            'url' => api_path('solution', 'woo-verzoeken'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $regie->id,
            'order' => 2,
            'title' => 'Actieve openbaarmaking',
            'subtitle' => 'Transparant volgens de wet',
            'icon' => 'fas fa-globe',
            'icon_bg_color' => $secondary,
            'url' => api_path('solution', 'actieve-openbaarmaking'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $regie->id,
            'order' => 3,
            'title' => 'Publicatieplatform',
            'subtitle' => 'Gecentraliseerd & Toegankelijk',
            'icon' => 'fas fa-desktop',
            'icon_bg_color' => $secondary,
            'url' => api_path('solution', 'publicatieplatform'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $regie->id,
            'order' => 4,
            'title' => 'Informatieobjectcatalogus & Data-regie',
            'subtitle' => 'Metadata, levenscyclusbeheer, API-first.',
            'icon' => 'fas fa-database',
            'icon_bg_color' => $secondary,
            'url' => api_path('solution', 'informatieobjectcatalogus'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        // 3. Leren & Doen (Academy) – mega menu
        $leren = MegaMenuItem::create([
            'parent_id' => null,
            'order' => 2,
            'title' => 'Leren & Doen',
            'subtitle' => 'Academy: webinars, OPMS in Vogelvlucht, Terugkijken.',
            'description' => null,
            'icon' => 'fas fa-graduation-cap',
            'icon_bg_color' => $primary,
            'url' => api_path('academy'),
            'is_mega_menu' => true,
            'is_active' => true,
            'open_in_new_tab' => false,
            'tags' => ['nav', 'dropdown'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $leren->id,
            'order' => 1,
            'title' => 'Live sessies',
            'subtitle' => 'Webinars en live sessies.',
            'icon' => 'fas fa-video',
            'icon_bg_color' => $secondary,
            'url' => api_path('live_sessions'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $leren->id,
            'order' => 2,
            'title' => 'Terugkijken',
            'subtitle' => 'Opnames van eerdere sessies.',
            'icon' => 'fas fa-history',
            'icon_bg_color' => $secondary,
            'url' => api_path('live_sessions_recordings'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $leren->id,
            'order' => 3,
            'title' => 'OPMS in Vogelvlucht',
            'subtitle' => 'Snel meegroeien met OPMS.',
            'icon' => 'fas fa-play-circle',
            'icon_bg_color' => $secondary,
            'url' => api_path('academy'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        // 4. Op de hoogte – mega menu
        $hoogte = MegaMenuItem::create([
            'parent_id' => null,
            'order' => 3,
            'title' => 'Op de hoogte',
            'subtitle' => 'Succesverhalen, changelog, nieuws en blog.',
            'description' => null,
            'icon' => 'fas fa-bullhorn',
            'icon_bg_color' => $primary,
            'url' => api_path('blog'),
            'is_mega_menu' => true,
            'is_active' => true,
            'open_in_new_tab' => false,
            'tags' => ['nav', 'dropdown'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $hoogte->id,
            'order' => 1,
            'title' => 'Zo doen zij het',
            'subtitle' => 'Succesverhalen en cases.',
            'icon' => 'fas fa-award',
            'icon_bg_color' => $secondary,
            'url' => api_path('page', 'zo-doen-zij-het'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $hoogte->id,
            'order' => 2,
            'title' => 'Wat is er nieuw',
            'subtitle' => 'Changelog en releases.',
            'icon' => 'fas fa-code-branch',
            'icon_bg_color' => $secondary,
            'url' => api_path('changelog'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $hoogte->id,
            'order' => 3,
            'title' => 'Nieuws',
            'subtitle' => 'Actuele berichten.',
            'icon' => 'fas fa-newspaper',
            'icon_bg_color' => $secondary,
            'url' => api_path('blog'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $hoogte->id,
            'order' => 4,
            'title' => 'Blog',
            'subtitle' => 'Artikelen en inzichten.',
            'icon' => 'fas fa-pen-fancy',
            'icon_bg_color' => $secondary,
            'url' => api_path('blog'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        // 5. Achter OpenPublication – mega menu
        $achter = MegaMenuItem::create([
            'parent_id' => null,
            'order' => 4,
            'title' => 'Achter OpenPublication',
            'subtitle' => 'Ons verhaal, partners en werken bij.',
            'description' => null,
            'icon' => 'fas fa-building',
            'icon_bg_color' => $primary,
            'url' => api_path('page', 'ons-verhaal'),
            'is_mega_menu' => true,
            'is_active' => true,
            'open_in_new_tab' => false,
            'tags' => ['nav', 'dropdown'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $achter->id,
            'order' => 1,
            'title' => 'Ons verhaal',
            'subtitle' => 'Wie is OpenPublication.',
            'icon' => 'fas fa-book-open',
            'icon_bg_color' => $secondary,
            'url' => api_path('page', 'ons-verhaal'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $achter->id,
            'order' => 2,
            'title' => 'Partners',
            'subtitle' => 'ODC-Noord, Logius, SSC-ICT, PaaS.',
            'icon' => 'fas fa-handshake',
            'icon_bg_color' => $secondary,
            'url' => api_path('page', 'partners'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $achter->id,
            'order' => 3,
            'title' => 'Werken bij',
            'subtitle' => 'Codelabs en vacatures.',
            'icon' => 'fas fa-briefcase',
            'icon_bg_color' => $secondary,
            'url' => api_path('vacancies'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        // 6. In gesprek – mega menu
        $gesprek = MegaMenuItem::create([
            'parent_id' => null,
            'order' => 5,
            'title' => 'In gesprek',
            'subtitle' => 'Ondersteuning en contact.',
            'description' => null,
            'icon' => 'fas fa-comments',
            'icon_bg_color' => $primary,
            'url' => api_path('contact'),
            'is_mega_menu' => true,
            'is_active' => true,
            'open_in_new_tab' => false,
            'tags' => ['nav', 'dropdown'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $gesprek->id,
            'order' => 1,
            'title' => 'Ondersteuning',
            'subtitle' => 'Vraag, demo, prijsopgave, sparren.',
            'icon' => 'fas fa-life-ring',
            'icon_bg_color' => $secondary,
            'url' => api_path('page', 'ondersteuning'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        MegaMenuItem::create([
            'parent_id' => $gesprek->id,
            'order' => 2,
            'title' => 'Contact',
            'subtitle' => 'Overzicht en formulieren.',
            'icon' => 'fas fa-envelope',
            'icon_bg_color' => $secondary,
            'url' => api_path('contact'),
            'is_active' => true,
            'tags' => ['dropdown-item'],
        ]);

        // 7. Demo aanvragen (CTA – primair)
        MegaMenuItem::create([
            'parent_id' => null,
            'order' => 6,
            'title' => 'Demo aanvragen',
            'subtitle' => null,
            'description' => null,
            'icon' => 'fas fa-rocket',
            'icon_bg_color' => $primary,
            'url' => api_path('trial'),
            'is_mega_menu' => false,
            'is_active' => true,
            'open_in_new_tab' => false,
            'tags' => ['cta', 'primary'],
        ]);

    }
}
