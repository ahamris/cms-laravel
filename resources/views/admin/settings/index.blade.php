<x-layouts.admin title="General Settings">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">General Settings</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage your site configuration and preferences</p>
            </div>
            <x-button variant="primary" type="button" icon="save" icon-position="left" onclick="alert('Settings saved successfully!')">
                Save All Settings
            </x-button>
        </div>

        <!-- Settings Tabs -->
        <x-ui.tabs :tabs="[
            'identity' => 'Site Identity',
            'contact' => 'Contact',
            'media' => 'Media',
            'mail' => 'Mail',
            'social' => 'Social Media',
            'analytics' => 'Analytics'
        ]" active="identity">
            
            <!-- Tab 1: Site Identity -->
            <x-ui.tab-panel name="identity">
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <i class="fa-solid fa-globe text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Site Identity</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Basic information about your website</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input 
                                label="Site Name" 
                                name="site_name" 
                                type="text" 
                                placeholder="My Laravel App"
                                icon="signature"
                                value=""
                            />
                            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">The name of your website</p>
                        </div>
                        <div>
                            <x-input 
                                label="Site Title (Title Tag)" 
                                name="site_title" 
                                type="text" 
                                placeholder="Best Admin Panel Solutions"
                                icon="heading"
                                value=""
                            />
                            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Appears in browser tabs</p>
                        </div>
                    </div>

                    <div>
                        <x-textarea
                            label="Site Description (Meta Description)"
                            name="site_description"
                            placeholder="A brief description of your website for search engines..."
                            rows="3"
                        />
                        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Used by search engines (max 160 characters recommended)</p>
                    </div>

                    <div>
                        <x-input 
                            label="Slogan / Tagline" 
                            name="site_slogan" 
                            type="text" 
                            placeholder="Manage the future today"
                            icon="quote-left"
                            value=""
                        />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-ui.select
                                label="Default Language"
                                name="default_language"
                                value="en"
                                :options="[
                                    ['label' => 'English', 'value' => 'en'],
                                    ['label' => 'Türkçe', 'value' => 'tr'],
                                    ['label' => 'Deutsch', 'value' => 'de'],
                                    ['label' => 'Français', 'value' => 'fr'],
                                    ['label' => 'Español', 'value' => 'es'],
                                ]"
                            />
                        </div>
                        <div>
                            <x-ui.select
                                label="Timezone"
                                name="timezone"
                                value="Europe/Istanbul"
                                :options="[
                                    ['label' => 'UTC', 'value' => 'UTC'],
                                    ['label' => 'Europe/Istanbul (UTC+3)', 'value' => 'Europe/Istanbul'],
                                    ['label' => 'Europe/London (UTC+0)', 'value' => 'Europe/London'],
                                    ['label' => 'America/New_York (UTC-5)', 'value' => 'America/New_York'],
                                    ['label' => 'America/Los_Angeles (UTC-8)', 'value' => 'America/Los_Angeles'],
                                    ['label' => 'Asia/Tokyo (UTC+9)', 'value' => 'Asia/Tokyo'],
                                ]"
                            />
                        </div>
                    </div>

                    <div class="p-4 bg-zinc-50 dark:bg-zinc-900/50 rounded-lg border border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-zinc-900 dark:text-white">Maintenance Mode</h4>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">When enabled, visitors will see a maintenance page</p>
                            </div>
                            <x-ui.toggle name="maintenance_mode" />
                        </div>
                    </div>
                </div>
            </x-ui.tab-panel>

            <!-- Tab 2: Contact -->
            <x-ui.tab-panel name="contact">
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <i class="fa-solid fa-address-book text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Contact Information</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Contact details visible on your website</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input 
                                label="Email Address" 
                                name="contact_email" 
                                type="email" 
                                placeholder="info@yoursite.com"
                                icon="envelope"
                                value=""
                            />
                        </div>
                        <div>
                            <x-input 
                                label="Phone Number" 
                                name="contact_phone" 
                                type="text" 
                                placeholder="+90 212 000 00 00"
                                icon="phone"
                                value=""
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input 
                                label="WhatsApp Number" 
                                name="contact_whatsapp" 
                                type="text" 
                                placeholder="+90 532 000 00 00"
                                icon="whatsapp"
                                icon-type="brand"
                                value=""
                            />
                        </div>
                        <div>
                            <x-input 
                                label="Working Hours" 
                                name="working_hours" 
                                type="text" 
                                placeholder="Mon-Fri: 09:00 - 18:00"
                                icon="clock"
                                value=""
                            />
                        </div>
                    </div>

                    <div>
                        <x-textarea
                            label="Address"
                            name="contact_address"
                            placeholder="Your full business address..."
                            rows="3"
                        />
                    </div>

                    <div>
                        <x-textarea
                            label="Google Maps Embed Code"
                            name="google_maps_embed"
                            placeholder="<iframe src='https://www.google.com/maps/embed?...' ...></iframe>"
                            rows="4"
                        />
                        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Paste the full iframe embed code from Google Maps</p>
                    </div>
                </div>
            </x-ui.tab-panel>

            <!-- Tab 3: Media -->
            <x-ui.tab-panel name="media">
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <i class="fa-solid fa-image text-purple-600 dark:text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Media Assets</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Logos, favicon, and brand images</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Main Logo Light -->
                        <div class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">Main Logo (Light Mode)</label>
                            <div class="flex items-center gap-4">
                                <div class="w-32 h-16 bg-zinc-100 dark:bg-zinc-900 rounded-lg flex items-center justify-center border-2 border-dashed border-zinc-300 dark:border-zinc-600">
                                    <i class="fa-solid fa-cloud-arrow-up text-zinc-400 text-2xl"></i>
                                </div>
                                <div>
                                    <button type="button" class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-700 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors">
                                        Upload Logo
                                    </button>
                                    <p class="mt-1 text-xs text-zinc-500">PNG, SVG (max 2MB)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Main Logo Dark -->
                        <div class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">Main Logo (Dark Mode)</label>
                            <div class="flex items-center gap-4">
                                <div class="w-32 h-16 bg-zinc-800 rounded-lg flex items-center justify-center border-2 border-dashed border-zinc-600">
                                    <i class="fa-solid fa-cloud-arrow-up text-zinc-500 text-2xl"></i>
                                </div>
                                <div>
                                    <button type="button" class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-700 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors">
                                        Upload Logo
                                    </button>
                                    <p class="mt-1 text-xs text-zinc-500">PNG, SVG (max 2MB)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Logo -->
                        <div class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">Admin Panel Logo</label>
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-zinc-100 dark:bg-zinc-900 rounded-lg flex items-center justify-center border-2 border-dashed border-zinc-300 dark:border-zinc-600">
                                    <i class="fa-solid fa-cloud-arrow-up text-zinc-400 text-xl"></i>
                                </div>
                                <div>
                                    <button type="button" class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-700 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors">
                                        Upload
                                    </button>
                                    <p class="mt-1 text-xs text-zinc-500">Square format recommended</p>
                                </div>
                            </div>
                        </div>

                        <!-- Favicon -->
                        <div class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">Favicon</label>
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-zinc-100 dark:bg-zinc-900 rounded-lg flex items-center justify-center border-2 border-dashed border-zinc-300 dark:border-zinc-600">
                                    <i class="fa-solid fa-cloud-arrow-up text-zinc-400 text-xl"></i>
                                </div>
                                <div>
                                    <button type="button" class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-700 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors">
                                        Upload
                                    </button>
                                    <p class="mt-1 text-xs text-zinc-500">ICO, PNG (32x32 px)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- OG Image -->
                    <div class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">Default Share Image (OG Image)</label>
                        <div class="flex items-center gap-4">
                            <div class="w-48 h-24 bg-zinc-100 dark:bg-zinc-900 rounded-lg flex items-center justify-center border-2 border-dashed border-zinc-300 dark:border-zinc-600">
                                <i class="fa-solid fa-cloud-arrow-up text-zinc-400 text-2xl"></i>
                            </div>
                            <div>
                                <button type="button" class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-700 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors">
                                    Upload Image
                                </button>
                                <p class="mt-1 text-xs text-zinc-500">Recommended: 1200x630 px for social sharing</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.tab-panel>

            <!-- Tab 4: Mail -->
            <x-ui.tab-panel name="mail">
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                            <i class="fa-solid fa-envelope-open-text text-orange-600 dark:text-orange-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">SMTP / Mail Configuration</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Configure email sending for your application</p>
                        </div>
                    </div>

                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-200 dark:border-yellow-800">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-triangle-exclamation text-yellow-600 dark:text-yellow-400 mt-0.5"></i>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                These settings affect how system emails are sent. Make sure to test after making changes.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-ui.select
                                label="Mail Driver"
                                name="mail_driver"
                                value="smtp"
                                :options="[
                                    ['label' => 'SMTP', 'value' => 'smtp'],
                                    ['label' => 'Sendmail', 'value' => 'sendmail'],
                                    ['label' => 'Mailgun', 'value' => 'mailgun'],
                                    ['label' => 'Amazon SES', 'value' => 'ses'],
                                    ['label' => 'Postmark', 'value' => 'postmark'],
                                ]"
                            />
                        </div>
                        <div>
                            <x-ui.select
                                label="Encryption"
                                name="mail_encryption"
                                value="tls"
                                :options="[
                                    ['label' => 'TLS', 'value' => 'tls'],
                                    ['label' => 'SSL', 'value' => 'ssl'],
                                    ['label' => 'None', 'value' => ''],
                                ]"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input 
                                label="SMTP Host" 
                                name="smtp_host" 
                                type="text" 
                                placeholder="smtp.gmail.com"
                                icon="server"
                                value=""
                            />
                        </div>
                        <div>
                            <x-input 
                                label="SMTP Port" 
                                name="smtp_port" 
                                type="number" 
                                placeholder="587"
                                icon="hashtag"
                                value=""
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input 
                                label="SMTP Username" 
                                name="smtp_username" 
                                type="text" 
                                placeholder="noreply@yoursite.com"
                                icon="user"
                                value=""
                            />
                        </div>
                        <div>
                            <x-input 
                                label="SMTP Password" 
                                name="smtp_password" 
                                type="password" 
                                placeholder="••••••••"
                                icon="lock"
                                value=""
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input 
                                label="From Email Address" 
                                name="mail_from_address" 
                                type="email" 
                                placeholder="noreply@yoursite.com"
                                icon="envelope"
                                value=""
                            />
                        </div>
                        <div>
                            <x-input 
                                label="From Name" 
                                name="mail_from_name" 
                                type="text" 
                                placeholder="Your Site Support"
                                icon="signature"
                                value=""
                            />
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <button type="button" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-700 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors">
                            <i class="fa-solid fa-paper-plane"></i>
                            Send Test Email
                        </button>
                    </div>
                </div>
            </x-ui.tab-panel>

            <!-- Tab 5: Social Media -->
            <x-ui.tab-panel name="social">
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 bg-pink-100 dark:bg-pink-900/30 rounded-lg">
                            <i class="fa-solid fa-share-nodes text-pink-600 dark:text-pink-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Social Media Links</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Add your social media profile URLs</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input 
                                label="Facebook" 
                                name="social_facebook" 
                                type="url" 
                                placeholder="https://facebook.com/yourpage"
                                icon="facebook"
                                icon-type="brand"
                                value=""
                            />
                        </div>
                        <div>
                            <x-input 
                                label="Instagram" 
                                name="social_instagram" 
                                type="url" 
                                placeholder="https://instagram.com/yourprofile"
                                icon="instagram"
                                icon-type="brand"
                                value=""
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input 
                                label="X (Twitter)" 
                                name="social_twitter" 
                                type="url" 
                                placeholder="https://x.com/yourprofile"
                                icon="x-twitter"
                                icon-type="brand"
                                value=""
                            />
                        </div>
                        <div>
                            <x-input 
                                label="LinkedIn" 
                                name="social_linkedin" 
                                type="url" 
                                placeholder="https://linkedin.com/company/yourcompany"
                                icon="linkedin"
                                icon-type="brand"
                                value=""
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input 
                                label="YouTube" 
                                name="social_youtube" 
                                type="url" 
                                placeholder="https://youtube.com/@yourchannel"
                                icon="youtube"
                                icon-type="brand"
                                value=""
                            />
                        </div>
                        <div>
                            <x-input 
                                label="GitHub" 
                                name="social_github" 
                                type="url" 
                                placeholder="https://github.com/yourprofile"
                                icon="github"
                                icon-type="brand"
                                value=""
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input 
                                label="TikTok" 
                                name="social_tiktok" 
                                type="url" 
                                placeholder="https://tiktok.com/@yourprofile"
                                icon="tiktok"
                                icon-type="brand"
                                value=""
                            />
                        </div>
                        <div>
                            <x-input 
                                label="Discord" 
                                name="social_discord" 
                                type="url" 
                                placeholder="https://discord.gg/yourserver"
                                icon="discord"
                                icon-type="brand"
                                value=""
                            />
                        </div>
                    </div>
                </div>
            </x-ui.tab-panel>

            <!-- Tab 6: Analytics -->
            <x-ui.tab-panel name="analytics">
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg">
                            <i class="fa-solid fa-chart-line text-cyan-600 dark:text-cyan-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Analytics & Scripts</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Add tracking codes and custom scripts</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input 
                                label="Google Analytics ID" 
                                name="google_analytics_id" 
                                type="text" 
                                placeholder="G-XXXXXXXXXX"
                                icon="chart-simple"
                                value=""
                            />
                            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Your Google Analytics 4 measurement ID</p>
                        </div>
                        <div>
                            <x-input 
                                label="Google Tag Manager ID" 
                                name="gtm_id" 
                                type="text" 
                                placeholder="GTM-XXXXXXX"
                                icon="tags"
                                value=""
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input 
                                label="Google Search Console" 
                                name="google_search_console" 
                                type="text" 
                                placeholder="Verification code or meta tag"
                                icon="magnifying-glass-chart"
                                value=""
                            />
                        </div>
                        <div>
                            <x-input 
                                label="Facebook Pixel ID" 
                                name="facebook_pixel_id" 
                                type="text" 
                                placeholder="000000000000000"
                                icon="facebook"
                                icon-type="brand"
                                value=""
                            />
                        </div>
                    </div>

                    <div>
                        <x-textarea
                            label="Custom Header Scripts"
                            name="custom_header_scripts"
                            placeholder="<!-- Add custom CSS or scripts to be included in <head> -->"
                            rows="5"
                        />
                        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">These scripts will be added before the closing &lt;/head&gt; tag</p>
                    </div>

                    <div>
                        <x-textarea
                            label="Custom Footer Scripts"
                            name="custom_footer_scripts"
                            placeholder="<!-- Add live chat widgets, analytics scripts, etc. -->"
                            rows="5"
                        />
                        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">These scripts will be added before the closing &lt;/body&gt; tag (e.g., Tawk.to, Intercom)</p>
                    </div>
                </div>
            </x-ui.tab-panel>
        </x-ui.tabs>
    </div>
</x-layouts.admin>
