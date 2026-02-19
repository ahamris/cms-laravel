<x-layouts.admin title="Contact Page Settings">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Contact Page</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Manage content of the front contact page (cards, demo section, contact info, form labels).</p>
        </div>
    </div>

    @if (session('status') === 'settings-updated')
        <x-ui.alert variant="success" icon="check-circle" message="Settings updated successfully!" class="mb-6" />
    @endif

    <form method="POST" action="{{ route('admin.settings.contact.update') }}" id="contact-page-settings-form">
        @csrf
        @method('PUT')

        <div class="space-y-8">
            {{-- Contact Cards --}}
            <x-ui.card>
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Contact Cards (two cards)</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Text for the two clickable cards above the form.</p>
                </div>
                <div class="space-y-4">
                    <x-ui.input
                        id="contact_card1_title"
                        name="contact_card1_title"
                        :value="old('contact_card1_title', get_setting('contact_card1_title', $defaults['contact_card1_title'] ?? ''))"
                        label="Card 1 – Title"
                        :error="$errors->has('contact_card1_title')"
                        :errorMessage="$errors->first('contact_card1_title')"
                    />
                    <x-ui.textarea
                        id="contact_card1_description"
                        name="contact_card1_description"
                        :value="old('contact_card1_description', get_setting('contact_card1_description', $defaults['contact_card1_description'] ?? ''))"
                        label="Card 1 – Description (newlines allowed)"
                        :rows="3"
                        :error="$errors->has('contact_card1_description')"
                        :errorMessage="$errors->first('contact_card1_description')"
                    />
                    <x-ui.input
                        id="contact_card1_cta"
                        name="contact_card1_cta"
                        :value="old('contact_card1_cta', get_setting('contact_card1_cta', $defaults['contact_card1_cta'] ?? ''))"
                        label="Card 1 – CTA text"
                        :error="$errors->has('contact_card1_cta')"
                        :errorMessage="$errors->first('contact_card1_cta')"
                    />
                    <x-ui.divider />
                    <x-ui.input
                        id="contact_card2_badge"
                        name="contact_card2_badge"
                        :value="old('contact_card2_badge', get_setting('contact_card2_badge', $defaults['contact_card2_badge'] ?? ''))"
                        label="Card 2 – Badge (small label)"
                        :error="$errors->has('contact_card2_badge')"
                        :errorMessage="$errors->first('contact_card2_badge')"
                    />
                    <x-ui.input
                        id="contact_card2_title"
                        name="contact_card2_title"
                        :value="old('contact_card2_title', get_setting('contact_card2_title', $defaults['contact_card2_title'] ?? ''))"
                        label="Card 2 – Title"
                        :error="$errors->has('contact_card2_title')"
                        :errorMessage="$errors->first('contact_card2_title')"
                    />
                    <x-ui.textarea
                        id="contact_card2_description"
                        name="contact_card2_description"
                        :value="old('contact_card2_description', get_setting('contact_card2_description', $defaults['contact_card2_description'] ?? ''))"
                        label="Card 2 – Description"
                        :rows="2"
                        :error="$errors->has('contact_card2_description')"
                        :errorMessage="$errors->first('contact_card2_description')"
                    />
                    <x-ui.input
                        id="contact_card2_cta"
                        name="contact_card2_cta"
                        :value="old('contact_card2_cta', get_setting('contact_card2_cta', $defaults['contact_card2_cta'] ?? ''))"
                        label="Card 2 – CTA text"
                        :error="$errors->has('contact_card2_cta')"
                        :errorMessage="$errors->first('contact_card2_cta')"
                    />
                </div>
            </x-ui.card>

            {{-- Demo section --}}
            <x-ui.card>
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Schedule a demo section</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Headings and links for the demo booking block.</p>
                </div>
                <div class="space-y-4">
                    <x-ui.input
                        id="contact_demo_heading"
                        name="contact_demo_heading"
                        :value="old('contact_demo_heading', get_setting('contact_demo_heading', $defaults['contact_demo_heading'] ?? ''))"
                        label="Demo heading"
                        :error="$errors->has('contact_demo_heading')"
                        :errorMessage="$errors->first('contact_demo_heading')"
                    />
                    <x-ui.textarea
                        id="contact_demo_intro"
                        name="contact_demo_intro"
                        :value="old('contact_demo_intro', get_setting('contact_demo_intro', $defaults['contact_demo_intro'] ?? ''))"
                        label="Demo intro paragraph"
                        :rows="3"
                        :error="$errors->has('contact_demo_intro')"
                        :errorMessage="$errors->first('contact_demo_intro')"
                    />
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-ui.input
                            id="contact_demo_al_klant_text"
                            name="contact_demo_al_klant_text"
                            :value="old('contact_demo_al_klant_text', get_setting('contact_demo_al_klant_text', $defaults['contact_demo_al_klant_text'] ?? ''))"
                            label="&quot;Al een klant?&quot; text"
                            :error="$errors->has('contact_demo_al_klant_text')"
                            :errorMessage="$errors->first('contact_demo_al_klant_text')"
                        />
                        <x-ui.input
                            id="contact_demo_support_text"
                            name="contact_demo_support_text"
                            :value="old('contact_demo_support_text', get_setting('contact_demo_support_text', $defaults['contact_demo_support_text'] ?? ''))"
                            label="Support link text"
                            :error="$errors->has('contact_demo_support_text')"
                            :errorMessage="$errors->first('contact_demo_support_text')"
                        />
                    </div>
                    <x-ui.input
                        id="contact_demo_support_url"
                        name="contact_demo_support_url"
                        type="url"
                        :value="old('contact_demo_support_url', get_setting('contact_demo_support_url', $defaults['contact_demo_support_url'] ?? ''))"
                        label="Support link URL"
                        :error="$errors->has('contact_demo_support_url')"
                        :errorMessage="$errors->first('contact_demo_support_url')"
                    />
                    <x-ui.input
                        id="contact_demo_meeting_duration"
                        name="contact_demo_meeting_duration"
                        :value="old('contact_demo_meeting_duration', get_setting('contact_demo_meeting_duration', $defaults['contact_demo_meeting_duration'] ?? ''))"
                        label="Meeting duration label (e.g. 30 min.)"
                        :error="$errors->has('contact_demo_meeting_duration')"
                        :errorMessage="$errors->first('contact_demo_meeting_duration')"
                    />
                    <x-ui.input
                        id="contact_demo_bottom_note"
                        name="contact_demo_bottom_note"
                        :value="old('contact_demo_bottom_note', get_setting('contact_demo_bottom_note', $defaults['contact_demo_bottom_note'] ?? ''))"
                        label="Bottom note (below calendar)"
                        :error="$errors->has('contact_demo_bottom_note')"
                        :errorMessage="$errors->first('contact_demo_bottom_note')"
                    />
                </div>
            </x-ui.card>

            {{-- Contact info (address, phone, company line) --}}
            <x-ui.card>
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Contact information block</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Left column of the contact form section (address, phone, company line).</p>
                </div>
                <div class="space-y-4">
                    <x-ui.input id="contact_info_heading" name="contact_info_heading" :value="old('contact_info_heading', get_setting('contact_info_heading', $defaults['contact_info_heading'] ?? ''))" label="Block heading" :error="$errors->has('contact_info_heading')" :errorMessage="$errors->first('contact_info_heading')" />
                    <x-ui.textarea id="contact_info_intro" name="contact_info_intro" :value="old('contact_info_intro', get_setting('contact_info_intro', $defaults['contact_info_intro'] ?? ''))" label="Intro paragraph" :rows="2" :error="$errors->has('contact_info_intro')" :errorMessage="$errors->first('contact_info_intro')" />
                    <x-ui.input id="contact_address_label" name="contact_address_label" :value="old('contact_address_label', get_setting('contact_address_label', $defaults['contact_address_label'] ?? ''))" label="Address label" :error="$errors->has('contact_address_label')" :errorMessage="$errors->first('contact_address_label')" />
                    <x-ui.textarea id="contact_address_text" name="contact_address_text" :value="old('contact_address_text', get_setting('contact_address_text', $defaults['contact_address_text'] ?? ''))" label="Address (newlines for line breaks)" :rows="2" :error="$errors->has('contact_address_text')" :errorMessage="$errors->first('contact_address_text')" />
                    <x-ui.input id="contact_phone_label" name="contact_phone_label" :value="old('contact_phone_label', get_setting('contact_phone_label', $defaults['contact_phone_label'] ?? ''))" label="Phone label" :error="$errors->has('contact_phone_label')" :errorMessage="$errors->first('contact_phone_label')" />
                    <x-ui.input id="contact_phone_number" name="contact_phone_number" :value="old('contact_phone_number', get_setting('contact_phone_number', $defaults['contact_phone_number'] ?? ''))" label="Phone number" :error="$errors->has('contact_phone_number')" :errorMessage="$errors->first('contact_phone_number')" />
                    <x-ui.input id="contact_phone_note" name="contact_phone_note" :value="old('contact_phone_note', get_setting('contact_phone_note', $defaults['contact_phone_note'] ?? ''))" label="Phone note (e.g. opening hours)" :error="$errors->has('contact_phone_note')" :errorMessage="$errors->first('contact_phone_note')" />
                    <x-ui.divider />
                    <x-ui.input id="contact_company_line" name="contact_company_line" :value="old('contact_company_line', get_setting('contact_company_line', $defaults['contact_company_line'] ?? ''))" label="Company line (text before link)" :error="$errors->has('contact_company_line')" :errorMessage="$errors->first('contact_company_line')" />
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-ui.input id="contact_company_line_url" name="contact_company_line_url" type="url" :value="old('contact_company_line_url', get_setting('contact_company_line_url', $defaults['contact_company_line_url'] ?? ''))" label="Company link URL" :error="$errors->has('contact_company_line_url')" :errorMessage="$errors->first('contact_company_line_url')" />
                        <x-ui.input id="contact_company_line_link_text" name="contact_company_line_link_text" :value="old('contact_company_line_link_text', get_setting('contact_company_line_link_text', $defaults['contact_company_line_link_text'] ?? ''))" label="Company link text" :error="$errors->has('contact_company_line_link_text')" :errorMessage="$errors->first('contact_company_line_link_text')" />
                    </div>
                </div>
            </x-ui.card>

            {{-- Form section --}}
            <x-ui.card>
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Contact form (right column)</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Heading, intro, submit button, GDPR text and “Waarmee kunnen we je helpen?” dropdown options.</p>
                </div>
                <div class="space-y-4">
                    <x-ui.input id="contact_form_heading" name="contact_form_heading" :value="old('contact_form_heading', get_setting('contact_form_heading', $defaults['contact_form_heading'] ?? ''))" label="Form heading" :error="$errors->has('contact_form_heading')" :errorMessage="$errors->first('contact_form_heading')" />
                    <x-ui.textarea id="contact_form_intro" name="contact_form_intro" :value="old('contact_form_intro', get_setting('contact_form_intro', $defaults['contact_form_intro'] ?? ''))" label="Form intro" :rows="2" :error="$errors->has('contact_form_intro')" :errorMessage="$errors->first('contact_form_intro')" />
                    <x-ui.input id="contact_form_submit_button" name="contact_form_submit_button" :value="old('contact_form_submit_button', get_setting('contact_form_submit_button', $defaults['contact_form_submit_button'] ?? ''))" label="Submit button text" :error="$errors->has('contact_form_submit_button')" :errorMessage="$errors->first('contact_form_submit_button')" />
                    <x-ui.textarea id="contact_avg_label" name="contact_avg_label" :value="old('contact_avg_label', get_setting('contact_avg_label', $defaults['contact_avg_label'] ?? ''))" label="GDPR/AVG opt-in label (use &quot;privacybeleid&quot; for link placeholder)" :rows="2" :error="$errors->has('contact_avg_label')" :errorMessage="$errors->first('contact_avg_label')" />
                    <x-ui.input id="contact_privacy_url" name="contact_privacy_url" type="url" :value="old('contact_privacy_url', get_setting('contact_privacy_url', $defaults['contact_privacy_url'] ?? ''))" label="Privacy policy URL (for AVG link)" :error="$errors->has('contact_privacy_url')" :errorMessage="$errors->first('contact_privacy_url')" />
                    <x-ui.textarea id="contact_reden_options" name="contact_reden_options" :value="old('contact_reden_options', get_setting('contact_reden_options', $defaults['contact_reden_options'] ?? '[]'))" label="&quot;Waarmee kunnen we je helpen?&quot; options (JSON)" :rows="4" hint="Format: [{\"value\":\"ondersteuning\",\"label\":\"Ondersteuning\"},{\"value\":\"kennismaking\",\"label\":\"Kennismaking\"},{\"value\":\"demo\",\"label\":\"Plan een demo\"}]" :error="$errors->has('contact_reden_options')" :errorMessage="$errors->first('contact_reden_options')" class="font-mono text-sm" />
                </div>
            </x-ui.card>

            <div class="flex justify-end">
                <x-ui.button type="submit" variant="primary">Save contact page settings</x-ui.button>
            </div>
        </div>
    </form>
</x-layouts.admin>
