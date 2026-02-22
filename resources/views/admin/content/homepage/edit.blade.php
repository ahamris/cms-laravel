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

    <form action="{{ route('admin.content.homepage.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <x-ui.accordion :exclusive="false" class="space-y-2">
            @php
                $hero = $sections['hero']->content ?? [];
                $heroBullets = $hero['bullets'] ?? [['icon' => 'check', 'text' => ''], ['icon' => 'check', 'text' => ''], ['icon' => 'check', 'text' => '']];
                $heroBullets = array_slice($heroBullets, 0, 3);
            @endphp
            <x-ui.accordion-item heading="Hero" icon="image" :expanded="true">
                <div class="space-y-4 pt-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Label (uppercase)</label>
                        <input type="text" name="sections[hero][label]" value="{{ old('sections.hero.label', $hero['label'] ?? '') }}"
                            class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md" placeholder="e.g. OPMS OPEN PUBLICATION PLATFORM">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Heading</label>
                        <input type="text" name="sections[hero][heading]" value="{{ old('sections.hero.heading', $hero['heading'] ?? '') }}"
                            class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md" placeholder="Main headline">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Paragraph</label>
                        <textarea name="sections[hero][paragraph]" rows="3" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md">{{ old('sections.hero.paragraph', $hero['paragraph'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Bullets (3)</label>
                        @foreach($heroBullets as $i => $b)
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="sections[hero][bullets][{{ $i }}][icon]" value="{{ old("sections.hero.bullets.{$i}.icon", $b['icon'] ?? 'check') }}" placeholder="Icon (e.g. check)" class="w-24 px-3 py-2 text-sm border border-gray-200 rounded-md">
                                <input type="text" name="sections[hero][bullets][{{ $i }}][text]" value="{{ old("sections.hero.bullets.{$i}.text", $b['text'] ?? '') }}" placeholder="Bullet text" class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-md">
                            </div>
                        @endforeach
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Primary CTA text</label>
                            <input type="text" name="sections[hero][cta_primary_text]" value="{{ old('sections.hero.cta_primary_text', $hero['cta_primary_text'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Primary CTA URL</label>
                            <input type="text" name="sections[hero][cta_primary_url]" value="{{ old('sections.hero.cta_primary_url', $hero['cta_primary_url'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Secondary CTA text</label>
                            <input type="text" name="sections[hero][cta_secondary_text]" value="{{ old('sections.hero.cta_secondary_text', $hero['cta_secondary_text'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Secondary CTA URL</label>
                            <input type="text" name="sections[hero][cta_secondary_url]" value="{{ old('sections.hero.cta_secondary_url', $hero['cta_secondary_url'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Hero image</label>
                        @if(!empty($hero['image']))
                            <div class="mb-2"><img src="{{ get_image($hero['image']) }}" alt="" class="h-24 object-cover rounded border"></div>
                        @endif
                        <input type="file" name="sections[hero][image]" accept="image/*" class="w-full text-sm">
                    </div>
                </div>
            </x-ui.accordion-item>

            @php $cards = $sections['feature_cards']->content['cards'] ?? array_fill(0, 3, ['icon' => '', 'title' => '', 'description' => '', 'link_text' => 'Read more', 'link_url' => '']); $cards = array_slice($cards, 0, 3); @endphp
            <x-ui.accordion-item heading="Feature cards (3)" icon="th-large">
                <div class="space-y-6 pt-2">
                    @foreach($cards as $i => $c)
                        <div class="p-4 bg-gray-50 rounded border border-gray-200">
                            <h4 class="text-sm font-medium mb-3">Card {{ $i + 1 }}</h4>
                            <div class="space-y-3">
                                <input type="text" name="sections[feature_cards][cards][{{ $i }}][icon]" value="{{ old("sections.feature_cards.cards.{$i}.icon", $c['icon'] ?? '') }}" placeholder="Icon (e.g. cog)" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                                <input type="text" name="sections[feature_cards][cards][{{ $i }}][title]" value="{{ old("sections.feature_cards.cards.{$i}.title", $c['title'] ?? '') }}" placeholder="Title" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                                <textarea name="sections[feature_cards][cards][{{ $i }}][description]" rows="2" placeholder="Description" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">{{ old("sections.feature_cards.cards.{$i}.description", $c['description'] ?? '') }}</textarea>
                                <div class="flex gap-2">
                                    <input type="text" name="sections[feature_cards][cards][{{ $i }}][link_text]" value="{{ old("sections.feature_cards.cards.{$i}.link_text", $c['link_text'] ?? 'Read more') }}" placeholder="Link text" class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-md">
                                    <input type="text" name="sections[feature_cards][cards][{{ $i }}][link_url]" value="{{ old("sections.feature_cards.cards.{$i}.link_url", $c['link_url'] ?? '') }}" placeholder="Link URL" class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-md">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.accordion-item>

            @php
                $about = $sections['about_opms']->content ?? [];
                $aboutBullets = $about['bullets'] ?? [['icon' => 'check', 'text' => ''], ['icon' => 'check', 'text' => ''], ['icon' => 'check', 'text' => '']];
                $aboutBullets = array_slice($aboutBullets, 0, 3);
            @endphp
            <x-ui.accordion-item heading="About OPMS (Slimmer besturen)" icon="info-circle">
                <div class="space-y-4 pt-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Label</label>
                        <input type="text" name="sections[about_opms][label]" value="{{ old('sections.about_opms.label', $about['label'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Heading</label>
                        <input type="text" name="sections[about_opms][heading]" value="{{ old('sections.about_opms.heading', $about['heading'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Paragraph</label>
                        <textarea name="sections[about_opms][paragraph]" rows="3" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">{{ old('sections.about_opms.paragraph', $about['paragraph'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Bullets (3)</label>
                        @foreach($aboutBullets as $i => $b)
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="sections[about_opms][bullets][{{ $i }}][icon]" value="{{ old("sections.about_opms.bullets.{$i}.icon", $b['icon'] ?? 'check') }}" class="w-24 px-3 py-2 text-sm border border-gray-200 rounded-md">
                                <input type="text" name="sections[about_opms][bullets][{{ $i }}][text]" value="{{ old("sections.about_opms.bullets.{$i}.text", $b['text'] ?? '') }}" class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-md">
                            </div>
                        @endforeach
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Link text</label>
                            <input type="text" name="sections[about_opms][link_text]" value="{{ old('sections.about_opms.link_text', $about['link_text'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Link URL</label>
                            <input type="text" name="sections[about_opms][link_url]" value="{{ old('sections.about_opms.link_url', $about['link_url'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Image</label>
                        @if(!empty($about['image']))
                            <div class="mb-2"><img src="{{ get_image($about['image']) }}" alt="" class="h-24 object-cover rounded border"></div>
                        @endif
                        <input type="file" name="sections[about_opms][image]" accept="image/*" class="w-full text-sm">
                    </div>
                </div>
            </x-ui.accordion-item>

            @php $steps = $sections['how_it_works']->content['steps'] ?? array_fill(0, 3, ['number' => '', 'title' => '', 'description' => '']); $steps = array_slice($steps, 0, 3); @endphp
            <x-ui.accordion-item heading="How it works" icon="list-ol">
                <div class="space-y-4 pt-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Section title</label>
                        <input type="text" name="sections[how_it_works][title]" value="{{ old('sections.how_it_works.title', $sections['how_it_works']->content['title'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md" placeholder="Hoe het werkt">
                    </div>
                    @foreach($steps as $i => $s)
                        <div class="p-4 bg-gray-50 rounded border">
                            <h4 class="text-sm font-medium mb-2">Step {{ $i + 1 }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                <input type="text" name="sections[how_it_works][steps][{{ $i }}][number]" value="{{ old("sections.how_it_works.steps.{$i}.number", $s['number'] ?? (string)($i + 1)) }}" placeholder="Number" class="px-3 py-2 text-sm border border-gray-200 rounded-md">
                                <input type="text" name="sections[how_it_works][steps][{{ $i }}][title]" value="{{ old("sections.how_it_works.steps.{$i}.title", $s['title'] ?? '') }}" placeholder="Title" class="px-3 py-2 text-sm border border-gray-200 rounded-md">
                                <textarea name="sections[how_it_works][steps][{{ $i }}][description]" rows="1" placeholder="Description" class="px-3 py-2 text-sm border border-gray-200 rounded-md">{{ old("sections.how_it_works.steps.{$i}.description", $s['description'] ?? '') }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.accordion-item>

            @php
                $uf = $sections['user_features']->content ?? [];
                $leftItems = $uf['left_items'] ?? [];
                $rightItems = $uf['right_items'] ?? [];
            @endphp
            <x-ui.accordion-item heading="User features (developer / user)" icon="users">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Left column title (e.g. Voor de ontwikkelaar)</label>
                        <input type="text" name="sections[user_features][left_title]" value="{{ old('sections.user_features.left_title', $uf['left_title'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                        <label class="block text-xs font-medium text-gray-700 mt-3 mb-1">Left items (one per line)</label>
                        <textarea name="sections[user_features][left_items_text]" rows="6" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md" placeholder="API&#10;SDK&#10;Webhook">@foreach($leftItems as $item){{ $item }}{{ "\n" }}@endforeach</textarea>
                        <p class="text-xs text-gray-500 mt-1">Enter one feature per line; saved as list.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Right column title (e.g. Voor de gebruiker)</label>
                        <input type="text" name="sections[user_features][right_title]" value="{{ old('sections.user_features.right_title', $uf['right_title'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                        <label class="block text-xs font-medium text-gray-700 mt-3 mb-1">Right items (one per line)</label>
                        <textarea name="sections[user_features][right_items_text]" rows="6" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">@foreach($rightItems as $item){{ $item }}{{ "\n" }}@endforeach</textarea>
                    </div>
                </div>
            </x-ui.accordion-item>

            @php $boxes = $sections['competition']->content['boxes'] ?? array_fill(0, 4, ['value' => '', 'label' => '']); $boxes = array_slice($boxes, 0, 4); $comp = $sections['competition']->content ?? []; @endphp
            <x-ui.accordion-item heading="Why OPMS eliminates competition" icon="trophy">
                <div class="space-y-4 pt-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Heading</label>
                        <input type="text" name="sections[competition][heading]" value="{{ old('sections.competition.heading', $comp['heading'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Paragraph</label>
                        <textarea name="sections[competition][paragraph]" rows="3" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">{{ old('sections.competition.paragraph', $comp['paragraph'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Metric boxes (4)</label>
                        @foreach($boxes as $i => $box)
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="sections[competition][boxes][{{ $i }}][value]" value="{{ old("sections.competition.boxes.{$i}.value", $box['value'] ?? '') }}" placeholder="Value (e.g. 100%, GWV)" class="w-32 px-3 py-2 text-sm border border-gray-200 rounded-md">
                                <input type="text" name="sections[competition][boxes][{{ $i }}][label]" value="{{ old("sections.competition.boxes.{$i}.label", $box['label'] ?? '') }}" placeholder="Label" class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-md">
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-ui.accordion-item>

            <x-ui.accordion-item heading="Latest updates (section title only)" icon="newspaper">
                <div class="pt-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Section title</label>
                    <input type="text" name="sections[latest_updates][title]" value="{{ old('sections.latest_updates.title', $sections['latest_updates']->content['title'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md" placeholder="Laatste updates">
                    <p class="text-xs text-gray-500 mt-1">Articles are loaded from the blog API; only the section title is stored here.</p>
                </div>
            </x-ui.accordion-item>

            @php $cta = $sections['bottom_cta']->content ?? []; @endphp
            <x-ui.accordion-item heading="Bottom CTA" icon="hand-pointer">
                <div class="space-y-4 pt-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Heading</label>
                        <input type="text" name="sections[bottom_cta][heading]" value="{{ old('sections.bottom_cta.heading', $cta['heading'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md" placeholder="Slimmer werken begint met een demo.">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Subtext</label>
                        <textarea name="sections[bottom_cta][subtext]" rows="2" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">{{ old('sections.bottom_cta.subtext', $cta['subtext'] ?? '') }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Primary CTA text</label>
                            <input type="text" name="sections[bottom_cta][cta_primary_text]" value="{{ old('sections.bottom_cta.cta_primary_text', $cta['cta_primary_text'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Primary CTA URL</label>
                            <input type="text" name="sections[bottom_cta][cta_primary_url]" value="{{ old('sections.bottom_cta.cta_primary_url', $cta['cta_primary_url'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Secondary CTA text</label>
                            <input type="text" name="sections[bottom_cta][cta_secondary_text]" value="{{ old('sections.bottom_cta.cta_secondary_text', $cta['cta_secondary_text'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Secondary CTA URL</label>
                            <input type="text" name="sections[bottom_cta][cta_secondary_url]" value="{{ old('sections.bottom_cta.cta_secondary_url', $cta['cta_secondary_url'] ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md">
                        </div>
                    </div>
                </div>
            </x-ui.accordion-item>
        </x-ui.accordion>

        <div class="flex justify-end gap-2 pt-4">
            <button type="submit" class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:opacity-90 transition-colors">
                Save homepage
            </button>
        </div>
    </form>
</x-layouts.admin>
