<x-layouts.admin title="Edit Homepage">
    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 p-4">
            <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row lg:items-start gap-8">
        {{-- Sticky section nav (sidebar on large screens) --}}
        <nav class="lg:w-56 flex-shrink-0 lg:sticky lg:top-6 order-2 lg:order-1">
            <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-4 shadow-sm">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Jump to section</p>
                <ul class="space-y-1 text-sm">
                    <li><a href="#section-hero" class="block rounded-lg py-2 px-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/10">Hero</a></li>
                    <li><a href="#section-feature-cards" class="block rounded-lg py-2 px-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/10">Feature cards</a></li>
                    <li><a href="#section-about-opms" class="block rounded-lg py-2 px-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/10">About OPMS</a></li>
                    <li><a href="#section-how-it-works" class="block rounded-lg py-2 px-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/10">How it works</a></li>
                    <li><a href="#section-user-features" class="block rounded-lg py-2 px-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/10">User features</a></li>
                    <li><a href="#section-competition" class="block rounded-lg py-2 px-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/10">Competition / stats</a></li>
                    <li><a href="#section-latest-updates" class="block rounded-lg py-2 px-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/10">Latest updates</a></li>
                    <li><a href="#section-bottom-cta" class="block rounded-lg py-2 px-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/10">Bottom CTA</a></li>
                </ul>
            </div>
        </nav>

        <div class="flex-1 min-w-0 order-1 lg:order-2">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Edit Homepage</h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Content for hero, feature cards, about, how it works, and more. Header and footer are in General Settings.</p>
            </div>

            <form action="{{ route('admin.homepage.update') }}" method="POST" enctype="multipart/form-data" id="homepage-form">
                @csrf
                @method('PUT')

                @php
                    $hero = $sections['hero']->content ?? [];
                    $heroBullets = $hero['bullets'] ?? [['icon' => 'check', 'text' => '']];
                @endphp

                {{-- Hero --}}
                <section id="section-hero" class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm overflow-hidden mb-8 scroll-mt-6">
                    <div class="border-b border-gray-200 dark:border-white/10 bg-gray-50/80 dark:bg-white/5 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                            <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-[var(--color-accent)]/10 text-[var(--color-accent)]"><i class="fa-solid fa-image text-sm"></i></span>
                            Hero
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Main banner: label, heading, bullets and CTAs.</p>
                    </div>
                    <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <x-ui.input name="sections[hero][label]" id="hero_label" label="Label (uppercase)" :value="old('sections.hero.label', $hero['label'] ?? '')" placeholder="e.g. OPMS OPEN PUBLICATION PLATFORM" />
                        <x-ui.input name="sections[hero][heading]" id="hero_heading" label="Heading" :value="old('sections.hero.heading', $hero['heading'] ?? '')" placeholder="Main headline" />
                        <x-ui.textarea name="sections[hero][paragraph]" id="hero_paragraph" label="Paragraph" :value="old('sections.hero.paragraph', $hero['paragraph'] ?? '')" :rows="3" />
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bullets</label>
                            <div id="hero-bullets-list" class="space-y-2">
                                @foreach($heroBullets as $i => $b)
                                    <div class="homepage-dynamic-row flex gap-2 items-start">
                                        <x-ui.input name="sections[hero][bullets][{{ $i }}][icon]" :id="'hero_bullet_icon_'.$i" :value="old('sections.hero.bullets.'.$i.'.icon', $b['icon'] ?? 'check')" placeholder="Icon" class="w-24" />
                                        <x-ui.input name="sections[hero][bullets][{{ $i }}][text]" :id="'hero_bullet_text_'.$i" :value="old('sections.hero.bullets.'.$i.'.text', $b['text'] ?? '')" placeholder="Bullet text" class="flex-1" />
                                        <button type="button" class="homepage-remove-row mt-2 p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" title="Remove"><i class="fa-solid fa-times"></i></button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="hero-add-bullet" class="mt-2 inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/10"><i class="fa-solid fa-plus text-xs"></i> Add bullet</button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-ui.input name="sections[hero][cta_primary_text]" id="hero_cta_primary_text" label="Primary CTA text" :value="old('sections.hero.cta_primary_text', $hero['cta_primary_text'] ?? '')" />
                            <x-ui.url-selector name="sections[hero][cta_primary_url]" id="hero_cta_primary_url" label="Primary CTA URL" :value="old('sections.hero.cta_primary_url', $hero['cta_primary_url'] ?? '')" />
                            <x-ui.input name="sections[hero][cta_secondary_text]" id="hero_cta_secondary_text" label="Secondary CTA text" :value="old('sections.hero.cta_secondary_text', $hero['cta_secondary_text'] ?? '')" />
                            <x-ui.url-selector name="sections[hero][cta_secondary_url]" id="hero_cta_secondary_url" label="Secondary CTA URL" :value="old('sections.hero.cta_secondary_url', $hero['cta_secondary_url'] ?? '')" />
                        </div>
                    </div>
                    <div>
                        <x-ui.image-upload
                            id="hero_image"
                            name="sections[hero][image]"
                            label="Hero image"
                            :current-image="!empty($hero['image']) ? get_image($hero['image']) : null"
                            current-image-alt="Hero"
                            help-text="JPEG, PNG, WebP up to 2MB"
                            :max-size="2048"
                            :required="false"
                        />
                    </div>
                </div>
                    </div>
                </section>

            @php
                $fcContent = $sections['feature_cards']->content ?? [];
                $cards = $fcContent['cards'] ?? [['icon' => '', 'title' => '', 'description' => '', 'link_text' => 'Read more', 'link_url' => '']];
                if (empty($cards)) { $cards = [['icon' => '', 'title' => '', 'description' => '', 'link_text' => 'Read more', 'link_url' => '']]; }
            @endphp
            {{-- Feature cards --}}
            <section id="section-feature-cards" class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm overflow-hidden mb-8 scroll-mt-6">
                <div class="border-b border-gray-200 dark:border-white/10 bg-gray-50/80 dark:bg-white/5 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-[var(--color-accent)]/10 text-[var(--color-accent)]"><i class="fa-solid fa-th-large text-sm"></i></span>
                        Feature cards
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Cards with icon, title, description and link.</p>
                </div>
                <div class="p-6">
                <x-ui.input name="sections[feature_cards][title]" id="feature_cards_title" label="Section title" :value="old('sections.feature_cards.title', $fcContent['title'] ?? '')" placeholder="e.g. Wat we bieden" class="mb-4" />
                <div id="feature-cards-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($cards as $i => $c)
                        <div class="homepage-dynamic-row bg-white dark:bg-white/5 rounded-md border border-gray-200 dark:border-white/10 p-4 space-y-3 relative">
                            <button type="button" class="homepage-remove-row absolute top-2 right-2 p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" title="Remove card"><i class="fa-solid fa-times text-sm"></i></button>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 pr-8">Card {{ $i + 1 }}</h4>
                            <x-ui.input name="sections[feature_cards][cards][{{ $i }}][icon]" :id="'card_'.$i.'_icon'" label="Icon" :value="old('sections.feature_cards.cards.'.$i.'.icon', $c['icon'] ?? '')" placeholder="e.g. cog" />
                            <x-ui.input name="sections[feature_cards][cards][{{ $i }}][title]" :id="'card_'.$i.'_title'" label="Title" :value="old('sections.feature_cards.cards.'.$i.'.title', $c['title'] ?? '')" />
                            <x-ui.textarea name="sections[feature_cards][cards][{{ $i }}][description]" :id="'card_'.$i.'_description'" label="Description" :value="old('sections.feature_cards.cards.'.$i.'.description', $c['description'] ?? '')" :rows="2" />
                            <x-ui.input name="sections[feature_cards][cards][{{ $i }}][link_text]" :id="'card_'.$i.'_link_text'" label="Link text" :value="old('sections.feature_cards.cards.'.$i.'.link_text', $c['link_text'] ?? 'Read more')" />
                            <x-ui.url-selector name="sections[feature_cards][cards][{{ $i }}][link_url]" :id="'card_'.$i.'_link_url'" label="Link URL" :value="old('sections.feature_cards.cards.'.$i.'.link_url', $c['link_url'] ?? '')" />
                        </div>
                    @endforeach
                </div>
                <button type="button" id="feature-cards-add" class="mt-4 inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/10"><i class="fa-solid fa-plus text-xs"></i> Add card</button>
                </div>
            </section>

            @php $about = $sections['about_opms']->content ?? []; $aboutBullets = $about['bullets'] ?? [['icon' => 'check', 'text' => '']]; if (empty($aboutBullets)) { $aboutBullets = [['icon' => 'check', 'text' => '']]; } @endphp
            {{-- About OPMS --}}
            <section id="section-about-opms" class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm overflow-hidden mb-8 scroll-mt-6">
                <div class="border-b border-gray-200 dark:border-white/10 bg-gray-50/80 dark:bg-white/5 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-[var(--color-accent)]/10 text-[var(--color-accent)]"><i class="fa-solid fa-info-circle text-sm"></i></span>
                        About OPMS
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Label, heading, paragraph, bullets and image.</p>
                </div>
                <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <x-ui.input name="sections[about_opms][label]" id="about_label" label="Label" :value="old('sections.about_opms.label', $about['label'] ?? '')" />
                        <x-ui.input name="sections[about_opms][heading]" id="about_heading" label="Heading" :value="old('sections.about_opms.heading', $about['heading'] ?? '')" />
                        <x-ui.textarea name="sections[about_opms][paragraph]" id="about_paragraph" label="Paragraph" :value="old('sections.about_opms.paragraph', $about['paragraph'] ?? '')" :rows="3" />
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bullets</label>
                            <div id="about-bullets-list" class="space-y-2">
                                @foreach($aboutBullets as $i => $b)
                                    <div class="homepage-dynamic-row flex gap-2 items-start">
                                        <x-ui.input name="sections[about_opms][bullets][{{ $i }}][icon]" :id="'about_bullet_icon_'.$i" :value="old('sections.about_opms.bullets.'.$i.'.icon', $b['icon'] ?? 'check')" placeholder="Icon" class="w-24" />
                                        <x-ui.input name="sections[about_opms][bullets][{{ $i }}][text]" :id="'about_bullet_text_'.$i" :value="old('sections.about_opms.bullets.'.$i.'.text', $b['text'] ?? '')" placeholder="Text" class="flex-1" />
                                        <button type="button" class="homepage-remove-row mt-2 p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" title="Remove"><i class="fa-solid fa-times"></i></button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="about-add-bullet" class="mt-2 inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/10"><i class="fa-solid fa-plus text-xs"></i> Add bullet</button>
                        </div>
                        <x-ui.input name="sections[about_opms][link_text]" id="about_link_text" label="Link text" :value="old('sections.about_opms.link_text', $about['link_text'] ?? '')" />
                        <x-ui.url-selector name="sections[about_opms][link_url]" id="about_link_url" label="Link URL" :value="old('sections.about_opms.link_url', $about['link_url'] ?? '')" />
                    </div>
                    <div>
                        <x-ui.image-upload
                            id="about_opms_image"
                            name="sections[about_opms][image]"
                            label="Image"
                            :current-image="!empty($about['image']) ? get_image($about['image']) : null"
                            current-image-alt="About OPMS"
                            help-text="JPEG, PNG, WebP up to 2MB"
                            :max-size="2048"
                            :required="false"
                        />
                    </div>
                </div>
                </div>
            </section>

            @php $steps = $sections['how_it_works']->content['steps'] ?? [['number' => '1', 'title' => '', 'description' => '']]; if (empty($steps)) { $steps = [['number' => '1', 'title' => '', 'description' => '']]; } @endphp
            {{-- How it works --}}
            <section id="section-how-it-works" class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm overflow-hidden mb-8 scroll-mt-6">
                <div class="border-b border-gray-200 dark:border-white/10 bg-gray-50/80 dark:bg-white/5 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-[var(--color-accent)]/10 text-[var(--color-accent)]"><i class="fa-solid fa-list-ol text-sm"></i></span>
                        How it works
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Section title and numbered steps.</p>
                </div>
                <div class="p-6">
                <x-ui.input name="sections[how_it_works][title]" id="how_title" label="Section title" :value="old('sections.how_it_works.title', $sections['how_it_works']->content['title'] ?? '')" placeholder="Hoe het werkt" class="mb-4" />
                <div id="how-steps-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($steps as $i => $s)
                        <div class="homepage-dynamic-row bg-white dark:bg-white/5 rounded-md border border-gray-200 dark:border-white/10 p-4 space-y-2 relative">
                            <button type="button" class="homepage-remove-row absolute top-2 right-2 p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" title="Remove step"><i class="fa-solid fa-times text-sm"></i></button>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 pr-8">Step {{ $i + 1 }}</h4>
                            <x-ui.input name="sections[how_it_works][steps][{{ $i }}][number]" :id="'step_'.$i.'_number'" label="Number" :value="old('sections.how_it_works.steps.'.$i.'.number', $s['number'] ?? (string)($i + 1))" />
                            <x-ui.input name="sections[how_it_works][steps][{{ $i }}][title]" :id="'step_'.$i.'_title'" label="Title" :value="old('sections.how_it_works.steps.'.$i.'.title', $s['title'] ?? '')" />
                            <x-ui.textarea name="sections[how_it_works][steps][{{ $i }}][description]" :id="'step_'.$i.'_description'" label="Description" :value="old('sections.how_it_works.steps.'.$i.'.description', $s['description'] ?? '')" :rows="2" />
                        </div>
                    @endforeach
                </div>
                <button type="button" id="how-add-step" class="mt-4 inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/10"><i class="fa-solid fa-plus text-xs"></i> Add step</button>
                </div>
            </section>

            @php $uf = $sections['user_features']->content ?? []; $leftItems = $uf['left_items'] ?? []; $rightItems = $uf['right_items'] ?? []; if (empty($leftItems)) { $leftItems = ['']; } if (empty($rightItems)) { $rightItems = ['']; } @endphp
            {{-- User features --}}
            <section id="section-user-features" class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm overflow-hidden mb-8 scroll-mt-6">
                <div class="border-b border-gray-200 dark:border-white/10 bg-gray-50/80 dark:bg-white/5 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-[var(--color-accent)]/10 text-[var(--color-accent)]"><i class="fa-solid fa-users text-sm"></i></span>
                        User features
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Two columns with titles and bullet lists (e.g. developer / user).</p>
                </div>
                <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <x-ui.input name="sections[user_features][left_title]" id="uf_left_title" label="Left column title" :value="old('sections.user_features.left_title', $uf['left_title'] ?? '')" placeholder="e.g. API-first & Headless architectuur" />
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Left items</label>
                        <div id="user-features-left-list" class="space-y-2">
                            @foreach($leftItems as $i => $item)
                                <div class="homepage-dynamic-row flex gap-2 items-center">
                                    <x-ui.input name="sections[user_features][left_items][{{ $i }}]" :id="'uf_left_'.$i" :value="old('sections.user_features.left_items.'.$i, is_string($item) ? $item : '')" placeholder="e.g. API, SDK" class="flex-1" />
                                    <button type="button" class="homepage-remove-row flex-shrink-0 p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" title="Remove"><i class="fa-solid fa-times"></i></button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="user-features-add-left" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/10"><i class="fa-solid fa-plus text-xs"></i> Add item</button>
                    </div>
                    <div class="space-y-3">
                        <x-ui.input name="sections[user_features][right_title]" id="uf_right_title" label="Right column title" :value="old('sections.user_features.right_title', $uf['right_title'] ?? '')" placeholder="e.g. Voor de gebruiker" />
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Right items</label>
                        <div id="user-features-right-list" class="space-y-2">
                            @foreach($rightItems as $i => $item)
                                <div class="homepage-dynamic-row flex gap-2 items-center">
                                    <x-ui.input name="sections[user_features][right_items][{{ $i }}]" :id="'uf_right_'.$i" :value="old('sections.user_features.right_items.'.$i, is_string($item) ? $item : '')" placeholder="e.g. Makkelijk te gebruiken" class="flex-1" />
                                    <button type="button" class="homepage-remove-row flex-shrink-0 p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" title="Remove"><i class="fa-solid fa-times"></i></button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="user-features-add-right" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/10"><i class="fa-solid fa-plus text-xs"></i> Add item</button>
                    </div>
                </div>
                </div>
            </section>

            @php $comp = $sections['competition']->content ?? []; $boxes = $comp['boxes'] ?? [['value' => '', 'label' => '']]; if (empty($boxes)) { $boxes = [['value' => '', 'label' => '']]; } @endphp
            {{-- Competition --}}
            <section id="section-competition" class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm overflow-hidden mb-8 scroll-mt-6">
                <div class="border-b border-gray-200 dark:border-white/10 bg-gray-50/80 dark:bg-white/5 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-[var(--color-accent)]/10 text-[var(--color-accent)]"><i class="fa-solid fa-trophy text-sm"></i></span>
                        Competition / stats
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Heading, paragraph and metric boxes (value + label).</p>
                </div>
                <div class="p-6">
                <div class="space-y-4">
                    <x-ui.input name="sections[competition][heading]" id="comp_heading" label="Section heading" :value="old('sections.competition.heading', $comp['heading'] ?? '')" placeholder="e.g. Waarom OPMS de concurrentie uitschakelt" />
                    <x-ui.textarea name="sections[competition][paragraph]" id="comp_paragraph" label="Paragraph" :value="old('sections.competition.paragraph', $comp['paragraph'] ?? '')" :rows="3" />
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Metric boxes</label>
                        <div id="competition-boxes-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($boxes as $i => $box)
                                <div class="homepage-dynamic-row flex gap-2 items-end p-3 bg-white dark:bg-white/5 rounded-md border border-gray-200 dark:border-white/10">
                                    <div class="flex-1 space-y-1">
                                        <x-ui.input name="sections[competition][boxes][{{ $i }}][value]" :id="'comp_box_value_'.$i" label="Value" :value="old('sections.competition.boxes.'.$i.'.value', $box['value'] ?? '')" placeholder="e.g. 100%, GWV" />
                                        <x-ui.input name="sections[competition][boxes][{{ $i }}][label]" :id="'comp_box_label_'.$i" label="Label" :value="old('sections.competition.boxes.'.$i.'.label', $box['label'] ?? '')" />
                                    </div>
                                    <button type="button" class="homepage-remove-row p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded mb-1" title="Remove"><i class="fa-solid fa-times"></i></button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="competition-add-box" class="mt-2 inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/10"><i class="fa-solid fa-plus text-xs"></i> Add box</button>
                    </div>
                </div>
                </div>
            </section>

            {{-- Latest updates --}}
            <section id="section-latest-updates" class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm overflow-hidden mb-8 scroll-mt-6">
                <div class="border-b border-gray-200 dark:border-white/10 bg-gray-50/80 dark:bg-white/5 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-[var(--color-accent)]/10 text-[var(--color-accent)]"><i class="fa-solid fa-newspaper text-sm"></i></span>
                        Latest updates
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Section title above the blog list (articles come from the blog API).</p>
                </div>
                <div class="p-6">
                <x-ui.input name="sections[latest_updates][title]" id="latest_title" label="Section title" :value="old('sections.latest_updates.title', $sections['latest_updates']->content['title'] ?? '')" placeholder="Laatste updates" />
                </div>
            </section>

            @php $cta = $sections['bottom_cta']->content ?? []; @endphp
            {{-- Bottom CTA --}}
            <section id="section-bottom-cta" class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm overflow-hidden mb-8 scroll-mt-6">
                <div class="border-b border-gray-200 dark:border-white/10 bg-gray-50/80 dark:bg-white/5 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-[var(--color-accent)]/10 text-[var(--color-accent)]"><i class="fa-solid fa-hand-pointer text-sm"></i></span>
                        Bottom CTA
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Call-to-action block: heading, subtext and buttons.</p>
                </div>
                <div class="p-6">
                <div class="space-y-4">
                    <x-ui.input name="sections[bottom_cta][heading]" id="cta_heading" label="Heading" :value="old('sections.bottom_cta.heading', $cta['heading'] ?? '')" placeholder="Slimmer werken begint met een demo." />
                    <x-ui.textarea name="sections[bottom_cta][subtext]" id="cta_subtext" label="Subtext" :value="old('sections.bottom_cta.subtext', $cta['subtext'] ?? '')" :rows="2" />
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-ui.input name="sections[bottom_cta][cta_primary_text]" id="cta_primary_text" label="Primary CTA text" :value="old('sections.bottom_cta.cta_primary_text', $cta['cta_primary_text'] ?? '')" />
                        <x-ui.url-selector name="sections[bottom_cta][cta_primary_url]" id="cta_primary_url" label="Primary CTA URL" :value="old('sections.bottom_cta.cta_primary_url', $cta['cta_primary_url'] ?? '')" />
                        <x-ui.input name="sections[bottom_cta][cta_secondary_text]" id="cta_secondary_text" label="Secondary CTA text" :value="old('sections.bottom_cta.cta_secondary_text', $cta['cta_secondary_text'] ?? '')" />
                        <x-ui.url-selector name="sections[bottom_cta][cta_secondary_url]" id="cta_secondary_url" label="Secondary CTA URL" :value="old('sections.bottom_cta.cta_secondary_url', $cta['cta_secondary_url'] ?? '')" />
                    </div>
                </div>
                </div>
            </section>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-4 pb-2">
                    <x-ui.button type="submit" variant="primary">Save homepage</x-ui.button>
                </div>
            </form>
        </div>
    </div>

    {{-- Templates for dynamic rows (hidden) --}}
    <template id="hero-bullet-tmpl">
        <div class="homepage-dynamic-row flex gap-2 items-start">
            <input type="text" name="sections[hero][bullets][__INDEX__][icon]" placeholder="Icon" value="check" class="w-24 rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm" />
            <input type="text" name="sections[hero][bullets][__INDEX__][text]" placeholder="Bullet text" class="flex-1 rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm" />
            <button type="button" class="homepage-remove-row mt-2 p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded"><i class="fa-solid fa-times"></i></button>
        </div>
    </template>
    <template id="about-bullet-tmpl">
        <div class="homepage-dynamic-row flex gap-2 items-start">
            <input type="text" name="sections[about_opms][bullets][__INDEX__][icon]" placeholder="Icon" value="check" class="w-24 rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm" />
            <input type="text" name="sections[about_opms][bullets][__INDEX__][text]" placeholder="Text" class="flex-1 rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm" />
            <button type="button" class="homepage-remove-row mt-2 p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded"><i class="fa-solid fa-times"></i></button>
        </div>
    </template>
    <template id="user-features-left-tmpl">
        <div class="homepage-dynamic-row flex gap-2 items-center">
            <input type="text" name="sections[user_features][left_items][__INDEX__]" placeholder="e.g. API, SDK" class="flex-1 rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm" />
            <button type="button" class="homepage-remove-row flex-shrink-0 p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded"><i class="fa-solid fa-times"></i></button>
        </div>
    </template>
    <template id="user-features-right-tmpl">
        <div class="homepage-dynamic-row flex gap-2 items-center">
            <input type="text" name="sections[user_features][right_items][__INDEX__]" placeholder="e.g. Makkelijk te gebruiken" class="flex-1 rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm" />
            <button type="button" class="homepage-remove-row flex-shrink-0 p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded"><i class="fa-solid fa-times"></i></button>
        </div>
    </template>
    <template id="feature-card-tmpl">
        <div class="homepage-dynamic-row bg-white dark:bg-white/5 rounded-md border border-gray-200 dark:border-white/10 p-4 space-y-3 relative">
            <button type="button" class="homepage-remove-row absolute top-2 right-2 p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded"><i class="fa-solid fa-times text-sm"></i></button>
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 pr-8">Card</h4>
            <input type="text" name="sections[feature_cards][cards][__INDEX__][icon]" placeholder="Icon" class="block w-full rounded-lg border border-gray-300 dark:border-white/10 px-3 py-2 text-sm mb-2" />
            <input type="text" name="sections[feature_cards][cards][__INDEX__][title]" placeholder="Title" class="block w-full rounded-lg border border-gray-300 dark:border-white/10 px-3 py-2 text-sm mb-2" />
            <textarea name="sections[feature_cards][cards][__INDEX__][description]" rows="2" placeholder="Description" class="block w-full rounded-lg border border-gray-300 dark:border-white/10 px-3 py-2 text-sm mb-2"></textarea>
            <input type="text" name="sections[feature_cards][cards][__INDEX__][link_text]" placeholder="Link text" value="Read more" class="block w-full rounded-lg border border-gray-300 dark:border-white/10 px-3 py-2 text-sm mb-2" />
            <input type="text" name="sections[feature_cards][cards][__INDEX__][link_url]" placeholder="Link URL" class="block w-full rounded-lg border border-gray-300 dark:border-white/10 px-3 py-2 text-sm" />
        </div>
    </template>
    <template id="how-step-tmpl">
        <div class="homepage-dynamic-row bg-white dark:bg-white/5 rounded-md border border-gray-200 dark:border-white/10 p-4 space-y-2 relative">
            <button type="button" class="homepage-remove-row absolute top-2 right-2 p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded"><i class="fa-solid fa-times text-sm"></i></button>
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 pr-8">Step</h4>
            <input type="text" name="sections[how_it_works][steps][__INDEX__][number]" placeholder="Number" class="block w-full rounded-lg border border-gray-300 dark:border-white/10 px-3 py-2 text-sm mb-2" />
            <input type="text" name="sections[how_it_works][steps][__INDEX__][title]" placeholder="Title" class="block w-full rounded-lg border border-gray-300 dark:border-white/10 px-3 py-2 text-sm mb-2" />
            <textarea name="sections[how_it_works][steps][__INDEX__][description]" rows="2" placeholder="Description" class="block w-full rounded-lg border border-gray-300 dark:border-white/10 px-3 py-2 text-sm"></textarea>
        </div>
    </template>
    <template id="competition-box-tmpl">
        <div class="homepage-dynamic-row flex gap-2 items-end p-3 bg-white dark:bg-white/5 rounded-md border border-gray-200 dark:border-white/10">
            <div class="flex-1 space-y-1">
                <input type="text" name="sections[competition][boxes][__INDEX__][value]" placeholder="Value" class="block w-full rounded-lg border border-gray-300 dark:border-white/10 px-3 py-2 text-sm mb-1" />
                <input type="text" name="sections[competition][boxes][__INDEX__][label]" placeholder="Label" class="block w-full rounded-lg border border-gray-300 dark:border-white/10 px-3 py-2 text-sm" />
            </div>
            <button type="button" class="homepage-remove-row p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded mb-1"><i class="fa-solid fa-times"></i></button>
        </div>
    </template>

    <script>
    (function() {
        var nextIndex = 10000;

        function nextId() { return nextIndex++; }

        function addFromTemplate(templateId, containerId) {
            var tmpl = document.getElementById(templateId);
            var container = document.getElementById(containerId);
            if (!tmpl || !container) return;
            var id = nextId();
            var html = tmpl.innerHTML.replace(/__INDEX__/g, id);
            var wrap = document.createElement('div');
            wrap.innerHTML = html.trim();
            var node = wrap.firstChild;
            container.appendChild(node);
            bindRemove(node);
        }

        function bindRemove(row) {
            var btn = row.querySelector && row.querySelector('.homepage-remove-row');
            if (btn) btn.addEventListener('click', function() { row.remove(); });
        }

        document.querySelectorAll('.homepage-dynamic-row').forEach(function(row) {
            row.querySelectorAll('.homepage-remove-row').forEach(function(btn) {
                btn.addEventListener('click', function() { row.remove(); });
            });
        });

        document.getElementById('hero-add-bullet') && document.getElementById('hero-add-bullet').addEventListener('click', function() { addFromTemplate('hero-bullet-tmpl', 'hero-bullets-list'); });
        document.getElementById('about-add-bullet') && document.getElementById('about-add-bullet').addEventListener('click', function() { addFromTemplate('about-bullet-tmpl', 'about-bullets-list'); });
        document.getElementById('feature-cards-add') && document.getElementById('feature-cards-add').addEventListener('click', function() { addFromTemplate('feature-card-tmpl', 'feature-cards-list'); });
        document.getElementById('how-add-step') && document.getElementById('how-add-step').addEventListener('click', function() { addFromTemplate('how-step-tmpl', 'how-steps-list'); });
        document.getElementById('competition-add-box') && document.getElementById('competition-add-box').addEventListener('click', function() { addFromTemplate('competition-box-tmpl', 'competition-boxes-list'); });
        document.getElementById('user-features-add-left') && document.getElementById('user-features-add-left').addEventListener('click', function() { addFromTemplate('user-features-left-tmpl', 'user-features-left-list'); });
        document.getElementById('user-features-add-right') && document.getElementById('user-features-add-right').addEventListener('click', function() { addFromTemplate('user-features-right-tmpl', 'user-features-right-list'); });
    })();
    </script>
</x-layouts.admin>
