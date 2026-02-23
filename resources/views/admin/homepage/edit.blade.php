<x-layouts.admin title="Edit Homepage">
    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4">
            <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
        </div>
    @endif
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-home text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Edit Homepage</h2>
                <p>Content for hero, feature cards, about OPMS, how it works, and more (header/footer use their own CRUD)</p>
            </div>
        </div>
    </div>

    <div class="bg-gray-50/50 dark:bg-white/5 rounded-md border border-gray-200 dark:border-white/10">
        <form action="{{ route('admin.homepage.update') }}" method="POST" enctype="multipart/form-data" class="space-y-0">
            @csrf
            @method('PUT')

            @php
                $hero = $sections['hero']->content ?? [];
                $heroBullets = $hero['bullets'] ?? [['icon' => 'check', 'text' => ''], ['icon' => 'check', 'text' => ''], ['icon' => 'check', 'text' => '']];
                $heroBullets = array_slice($heroBullets, 0, 3);
            @endphp

            {{-- Hero --}}
            <div class="p-6 border-b border-gray-200 dark:border-white/10">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="flex items-center justify-center w-8 h-8 rounded-md bg-primary/10 mr-2"><i class="fa-solid fa-image text-primary text-sm"></i></span>
                    Hero
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <x-ui.input name="sections[hero][label]" id="hero_label" label="Label (uppercase)" :value="old('sections.hero.label', $hero['label'] ?? '')" placeholder="e.g. OPMS OPEN PUBLICATION PLATFORM" />
                        <x-ui.input name="sections[hero][heading]" id="hero_heading" label="Heading" :value="old('sections.hero.heading', $hero['heading'] ?? '')" placeholder="Main headline" />
                        <x-ui.textarea name="sections[hero][paragraph]" id="hero_paragraph" label="Paragraph" :value="old('sections.hero.paragraph', $hero['paragraph'] ?? '')" :rows="3" />
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bullets (3)</label>
                            @foreach($heroBullets as $i => $b)
                                <div class="flex gap-2 mb-2">
                                    <x-ui.input name="sections[hero][bullets][{{ $i }}][icon]" :id="'hero_bullet_icon_'.$i" :value="old('sections.hero.bullets.'.$i.'.icon', $b['icon'] ?? 'check')" placeholder="Icon" />
                                    <x-ui.input name="sections[hero][bullets][{{ $i }}][text]" :id="'hero_bullet_text_'.$i" :value="old('sections.hero.bullets.'.$i.'.text', $b['text'] ?? '')" placeholder="Bullet text" class="flex-1" />
                                </div>
                            @endforeach
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

            @php $cards = $sections['feature_cards']->content['cards'] ?? array_fill(0, 3, ['icon' => '', 'title' => '', 'description' => '', 'link_text' => 'Read more', 'link_url' => '']); $cards = array_slice($cards, 0, 3); @endphp
            {{-- Feature cards --}}
            <div class="p-6 border-b border-gray-200 dark:border-white/10">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="flex items-center justify-center w-8 h-8 rounded-md bg-primary/10 mr-2"><i class="fa-solid fa-th-large text-primary text-sm"></i></span>
                    Feature cards (3)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($cards as $i => $c)
                        <div class="bg-white dark:bg-white/5 rounded-md border border-gray-200 dark:border-white/10 p-4 space-y-3">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Card {{ $i + 1 }}</h4>
                            <x-ui.input name="sections[feature_cards][cards][{{ $i }}][icon]" :id="'card_'.$i.'_icon'" label="Icon" :value="old('sections.feature_cards.cards.'.$i.'.icon', $c['icon'] ?? '')" placeholder="e.g. cog" />
                            <x-ui.input name="sections[feature_cards][cards][{{ $i }}][title]" :id="'card_'.$i.'_title'" label="Title" :value="old('sections.feature_cards.cards.'.$i.'.title', $c['title'] ?? '')" />
                            <x-ui.textarea name="sections[feature_cards][cards][{{ $i }}][description]" :id="'card_'.$i.'_description'" label="Description" :value="old('sections.feature_cards.cards.'.$i.'.description', $c['description'] ?? '')" :rows="2" />
                            <x-ui.input name="sections[feature_cards][cards][{{ $i }}][link_text]" :id="'card_'.$i.'_link_text'" label="Link text" :value="old('sections.feature_cards.cards.'.$i.'.link_text', $c['link_text'] ?? 'Read more')" />
                            <x-ui.url-selector name="sections[feature_cards][cards][{{ $i }}][link_url]" :id="'card_'.$i.'_link_url'" label="Link URL" :value="old('sections.feature_cards.cards.'.$i.'.link_url', $c['link_url'] ?? '')" />
                        </div>
                    @endforeach
                </div>
            </div>

            @php $about = $sections['about_opms']->content ?? []; $aboutBullets = $about['bullets'] ?? [['icon' => 'check', 'text' => ''], ['icon' => 'check', 'text' => ''], ['icon' => 'check', 'text' => '']]; $aboutBullets = array_slice($aboutBullets, 0, 3); @endphp
            {{-- About OPMS --}}
            <div class="p-6 border-b border-gray-200 dark:border-white/10">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="flex items-center justify-center w-8 h-8 rounded-md bg-primary/10 mr-2"><i class="fa-solid fa-info-circle text-primary text-sm"></i></span>
                    About OPMS (Slimmer besturen)
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <x-ui.input name="sections[about_opms][label]" id="about_label" label="Label" :value="old('sections.about_opms.label', $about['label'] ?? '')" />
                        <x-ui.input name="sections[about_opms][heading]" id="about_heading" label="Heading" :value="old('sections.about_opms.heading', $about['heading'] ?? '')" />
                        <x-ui.textarea name="sections[about_opms][paragraph]" id="about_paragraph" label="Paragraph" :value="old('sections.about_opms.paragraph', $about['paragraph'] ?? '')" :rows="3" />
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bullets (3)</label>
                            @foreach($aboutBullets as $i => $b)
                                <div class="flex gap-2 mb-2">
                                    <x-ui.input name="sections[about_opms][bullets][{{ $i }}][icon]" :id="'about_bullet_icon_'.$i" :value="old('sections.about_opms.bullets.'.$i.'.icon', $b['icon'] ?? 'check')" placeholder="Icon" />
                                    <x-ui.input name="sections[about_opms][bullets][{{ $i }}][text]" :id="'about_bullet_text_'.$i" :value="old('sections.about_opms.bullets.'.$i.'.text', $b['text'] ?? '')" placeholder="Text" class="flex-1" />
                                </div>
                            @endforeach
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

            @php $steps = $sections['how_it_works']->content['steps'] ?? array_fill(0, 3, ['number' => '', 'title' => '', 'description' => '']); $steps = array_slice($steps, 0, 3); @endphp
            {{-- How it works --}}
            <div class="p-6 border-b border-gray-200 dark:border-white/10">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="flex items-center justify-center w-8 h-8 rounded-md bg-primary/10 mr-2"><i class="fa-solid fa-list-ol text-primary text-sm"></i></span>
                    How it works
                </h3>
                <x-ui.input name="sections[how_it_works][title]" id="how_title" label="Section title" :value="old('sections.how_it_works.title', $sections['how_it_works']->content['title'] ?? '')" placeholder="Hoe het werkt" class="mb-4" />
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($steps as $i => $s)
                        <div class="bg-white dark:bg-white/5 rounded-md border border-gray-200 dark:border-white/10 p-4 space-y-2">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Step {{ $i + 1 }}</h4>
                            <x-ui.input name="sections[how_it_works][steps][{{ $i }}][number]" :id="'step_'.$i.'_number'" label="Number" :value="old('sections.how_it_works.steps.'.$i.'.number', $s['number'] ?? (string)($i + 1))" />
                            <x-ui.input name="sections[how_it_works][steps][{{ $i }}][title]" :id="'step_'.$i.'_title'" label="Title" :value="old('sections.how_it_works.steps.'.$i.'.title', $s['title'] ?? '')" />
                            <x-ui.textarea name="sections[how_it_works][steps][{{ $i }}][description]" :id="'step_'.$i.'_description'" label="Description" :value="old('sections.how_it_works.steps.'.$i.'.description', $s['description'] ?? '')" :rows="2" />
                        </div>
                    @endforeach
                </div>
            </div>

            @php $uf = $sections['user_features']->content ?? []; $leftItems = $uf['left_items'] ?? []; $rightItems = $uf['right_items'] ?? []; @endphp
            {{-- User features --}}
            <div class="p-6 border-b border-gray-200 dark:border-white/10">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="flex items-center justify-center w-8 h-8 rounded-md bg-primary/10 mr-2"><i class="fa-solid fa-users text-primary text-sm"></i></span>
                    User features (developer / user)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <x-ui.input name="sections[user_features][left_title]" id="uf_left_title" label="Left column title" :value="old('sections.user_features.left_title', $uf['left_title'] ?? '')" placeholder="e.g. Voor de ontwikkelaar" />
                        <x-ui.textarea name="sections[user_features][left_items_text]" id="uf_left_items" label="Left items (one per line)" :value="old('sections.user_features.left_items_text', implode("\n", $leftItems))" :rows="6" placeholder="API&#10;SDK&#10;Webhook" />
                        <p class="text-xs text-gray-500 dark:text-gray-400">Enter one feature per line; saved as list.</p>
                    </div>
                    <div class="space-y-3">
                        <x-ui.input name="sections[user_features][right_title]" id="uf_right_title" label="Right column title" :value="old('sections.user_features.right_title', $uf['right_title'] ?? '')" placeholder="e.g. Voor de gebruiker" />
                        <x-ui.textarea name="sections[user_features][right_items_text]" id="uf_right_items" label="Right items (one per line)" :value="old('sections.user_features.right_items_text', implode("\n", $rightItems))" :rows="6" />
                    </div>
                </div>
            </div>

            @php $boxes = $sections['competition']->content['boxes'] ?? array_fill(0, 4, ['value' => '', 'label' => '']); $boxes = array_slice($boxes, 0, 4); $comp = $sections['competition']->content ?? []; @endphp
            {{-- Competition --}}
            <div class="p-6 border-b border-gray-200 dark:border-white/10">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="flex items-center justify-center w-8 h-8 rounded-md bg-primary/10 mr-2"><i class="fa-solid fa-trophy text-primary text-sm"></i></span>
                    Why OPMS eliminates competition
                </h3>
                <div class="space-y-4">
                    <x-ui.input name="sections[competition][heading]" id="comp_heading" label="Heading" :value="old('sections.competition.heading', $comp['heading'] ?? '')" />
                    <x-ui.textarea name="sections[competition][paragraph]" id="comp_paragraph" label="Paragraph" :value="old('sections.competition.paragraph', $comp['paragraph'] ?? '')" :rows="3" />
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Metric boxes (4)</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($boxes as $i => $box)
                                <div class="flex gap-2">
                                    <x-ui.input name="sections[competition][boxes][{{ $i }}][value]" :id="'comp_box_value_'.$i" label="Value" :value="old('sections.competition.boxes.'.$i.'.value', $box['value'] ?? '')" placeholder="e.g. 100%, GWV" />
                                    <x-ui.input name="sections[competition][boxes][{{ $i }}][label]" :id="'comp_box_label_'.$i" label="Label" :value="old('sections.competition.boxes.'.$i.'.label', $box['label'] ?? '')" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Latest updates --}}
            <div class="p-6 border-b border-gray-200 dark:border-white/10">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="flex items-center justify-center w-8 h-8 rounded-md bg-primary/10 mr-2"><i class="fa-solid fa-newspaper text-primary text-sm"></i></span>
                    Latest updates (section title only)
                </h3>
                <x-ui.input name="sections[latest_updates][title]" id="latest_title" label="Section title" :value="old('sections.latest_updates.title', $sections['latest_updates']->content['title'] ?? '')" placeholder="Laatste updates" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Articles are loaded from the blog API; only the section title is stored here.</p>
            </div>

            @php $cta = $sections['bottom_cta']->content ?? []; @endphp
            {{-- Bottom CTA --}}
            <div class="p-6 border-b border-gray-200 dark:border-white/10">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="flex items-center justify-center w-8 h-8 rounded-md bg-primary/10 mr-2"><i class="fa-solid fa-hand-pointer text-primary text-sm"></i></span>
                    Bottom CTA
                </h3>
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

            <div class="p-6 flex justify-end">
                <x-ui.button type="submit" variant="primary">Save homepage</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.admin>
