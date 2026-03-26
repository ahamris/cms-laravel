<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ContactPageSettingsController extends AdminBaseController
{
    /**
     * Keys used for contact page content (group: contact).
     * Add new keys here and in the form when you need more editable fields.
     */
    private const CONTACT_KEYS = [
        'contact_card1_title',
        'contact_card1_description',
        'contact_card1_cta',
        'contact_card2_badge',
        'contact_card2_title',
        'contact_card2_description',
        'contact_card2_cta',
        'contact_demo_heading',
        'contact_demo_intro',
        'contact_demo_support_text',
        'contact_demo_support_url',
        'contact_demo_al_klant_text',
        'contact_demo_meeting_duration',
        'contact_demo_bottom_note',
        'contact_info_heading',
        'contact_info_intro',
        'contact_address_label',
        'contact_address_text',
        'contact_phone_label',
        'contact_phone_number',
        'contact_phone_note',
        'contact_company_line',
        'contact_company_line_url',
        'contact_company_line_link_text',
        'contact_form_heading',
        'contact_form_intro',
        'contact_form_submit_button',
        'contact_avg_label',
        'contact_privacy_url',
        'contact_reden_options',
    ];

    /**
     * Default values for contact page (used when key is missing).
     */
    private const DEFAULTS = [
        'contact_card1_title' => 'Waarmee kunnen we helpen?',
        'contact_card1_description' => "Voor al je vragen over ons platform.\nOnze specialisten zorgen voor een snelle en duidelijke reactie.",
        'contact_card1_cta' => 'Stel hier je vraag',
        'contact_card2_badge' => 'Nog geen klant?',
        'contact_card2_title' => 'Plan een demo',
        'contact_card2_description' => 'Plan een kort gesprek (± 15 min) en een consultant belt je op het moment dat jou uitkomt.',
        'contact_card2_cta' => 'Boek nu',
        'contact_demo_heading' => 'Plan een demo:',
        'contact_demo_intro' => 'Kun je nu niet chatten? Geen probleem. Vul het onderstaande formulier in om een kort gesprek (± 15 min) in te plannen, vertel ons over je uitdagingen en een consultant belt je op het moment dat jou uitkomt.',
        'contact_demo_support_text' => 'Neem contact op met support',
        'contact_demo_support_url' => '#',
        'contact_demo_al_klant_text' => 'Al een klant?',
        'contact_demo_meeting_duration' => '30 min.',
        'contact_demo_bottom_note' => 'Maak je gratis afspraak boekingspagina om sneller vergaderingen in te plannen.',
        'contact_info_heading' => 'Contactinformatie',
        'contact_info_intro' => 'We staan voor je klaar. Neem contact op via het formulier of gebruik de onderstaande gegevens.',
        'contact_address_label' => 'Adres',
        'contact_address_text' => "Kampenringweg 45C\n2803 PE Gouda",
        'contact_phone_label' => 'Telefoon',
        'contact_phone_number' => '+31 (0)85 212 9557',
        'contact_phone_note' => 'Bereikbaar op werkdagen van 09:00 - 17:30',
        'contact_company_line' => 'OpenPublicatie is een product van',
        'contact_company_line_url' => 'https://code-labs.nl',
        'contact_company_line_link_text' => 'CodeLabs B.V.',
        'contact_form_heading' => 'Stel je vraag',
        'contact_form_intro' => 'Vul het formulier in en we nemen zo snel mogelijk contact op.',
        'contact_form_submit_button' => 'Verstuur bericht',
        'contact_avg_label' => 'Ja, ik ga ermee akkoord dat mijn gegevens worden verwerkt om mijn aanvraag te behandelen. Lees ons privacybeleid. *',
        'contact_privacy_url' => '#',
        'contact_reden_options' => '[{"value":"ondersteuning","label":"Ondersteuning"},{"value":"kennismaking","label":"Kennismaking"},{"value":"demo","label":"Plan een demo"}]',
    ];

    /**
     * Display the contact page settings form.
     */
    public function index(): View
    {
        return view('admin.settings.contact.index', [
            'defaults' => self::DEFAULTS,
        ]);
    }

    /**
     * Update contact page settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $rules = [];
        foreach (self::CONTACT_KEYS as $key) {
            $rules[$key] = ['nullable', 'string', 'max:65535'];
        }
        $validated = $request->validate($rules);

        foreach (self::CONTACT_KEYS as $key) {
            $value = $validated[$key] ?? '';
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => 'text',
                    'group' => 'contact',
                    'display_name' => ucfirst(str_replace('_', ' ', preg_replace('/^contact_/', '', $key))),
                    'description' => null,
                    'order' => 0,
                ]
            );
        }

        Setting::forgetAggregateCache();
        foreach (self::CONTACT_KEYS as $key) {
            Cache::forget("settings.{$key}");
        }

        return redirect()->route('admin.settings.contact.index')
            ->with('status', 'settings-updated');
    }

    /**
     * Get default value for a contact key (for use in views).
     */
    public static function getDefault(string $key): string
    {
        return self::DEFAULTS[$key] ?? '';
    }
}
