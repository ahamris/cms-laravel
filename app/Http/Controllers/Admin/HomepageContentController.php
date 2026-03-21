<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\HomepageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomepageContentController extends AdminBaseController
{
    /**
     * Show the form for editing homepage sections (single "Edit Homepage" page).
     */
    public function edit(): View
    {
        $sections = collect(HomepageSection::SECTION_KEYS)->mapWithKeys(function (string $key) {
            $section = HomepageSection::firstOrCreate(
                ['section_key' => $key],
                [
                    'section_name' => $key,
                    'module_type' => 'content',
                    'sort_order' => array_search($key, HomepageSection::SECTION_KEYS),
                    'is_active' => true,
                    'content' => self::defaultContent($key),
                ]
            );
            return [$key => $section];
        });

        return view('admin.homepage.edit', compact('sections'));
    }

    /**
     * Update homepage sections.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'sections.hero.image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:20480',
            'sections.about_opms.image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:20480',
        ], [
            'sections.hero.image.max' => 'Hero image may not be greater than 20 MB.',
            'sections.about_opms.image.max' => 'About image may not be greater than 20 MB.',
        ]);

        $sections = $request->input('sections', []);
        $keys = HomepageSection::SECTION_KEYS;

        foreach ($keys as $key) {
            $data = $sections[$key] ?? [];
            if (! is_array($data)) {
                continue;
            }

            $section = HomepageSection::firstOrCreate(
                ['section_key' => $key],
                [
                    'section_name' => $key,
                    'module_type' => 'content',
                    'sort_order' => array_search($key, $keys),
                    'is_active' => true,
                ]
            );

            $content = $section->content ?? self::defaultContent($key);

            switch ($key) {
                case 'hero':
                    $content['label'] = $data['label'] ?? $content['label'] ?? '';
                    $content['heading'] = $data['heading'] ?? $content['heading'] ?? '';
                    $content['paragraph'] = $data['paragraph'] ?? $content['paragraph'] ?? '';
                    $content['bullets'] = array_values($data['bullets'] ?? $content['bullets'] ?? []);
                    $content['cta_primary_text'] = $data['cta_primary_text'] ?? $content['cta_primary_text'] ?? '';
                    $content['cta_primary_url'] = $data['cta_primary_url'] ?? $content['cta_primary_url'] ?? '';
                    $content['cta_secondary_text'] = $data['cta_secondary_text'] ?? $content['cta_secondary_text'] ?? '';
                    $content['cta_secondary_url'] = $data['cta_secondary_url'] ?? $content['cta_secondary_url'] ?? '';
                    if ($request->filled('remove_hero_image') && ! empty($content['image'])) {
                        $this->deleteImage($content['image']);
                        $content['image'] = '';
                    } elseif ($request->hasFile("sections.{$key}.image")) {
                        if (! empty($content['image'])) {
                            $this->deleteImage($content['image']);
                        }
                        $content['image'] = $this->uploadImage($request->file("sections.{$key}.image"), 'homepage');
                    }
                    $content = $this->purifyHtmlKeys($content, ['heading', 'paragraph']);
                    foreach ($content['bullets'] ?? [] as $i => $bullet) {
                        if (isset($bullet['text']) && is_string($bullet['text'])) {
                            $content['bullets'][$i]['text'] = \Mews\Purifier\Facades\Purifier::clean($bullet['text']);
                        }
                    }
                    break;
                case 'feature_cards':
                    $content['title'] = $data['title'] ?? $content['title'] ?? '';
                    $content['cards'] = array_values($data['cards'] ?? $content['cards'] ?? []);
                    foreach ($content['cards'] as $i => $card) {
                        $content['cards'][$i]['description'] = \Mews\Purifier\Facades\Purifier::clean($content['cards'][$i]['description'] ?? '');
                    }
                    break;
                case 'about_opms':
                    $content['label'] = $data['label'] ?? $content['label'] ?? '';
                    $content['heading'] = $data['heading'] ?? $content['heading'] ?? '';
                    $content['paragraph'] = $data['paragraph'] ?? $content['paragraph'] ?? '';
                    $content['bullets'] = array_values($data['bullets'] ?? $content['bullets'] ?? []);
                    $content['link_text'] = $data['link_text'] ?? $content['link_text'] ?? '';
                    $content['link_url'] = $data['link_url'] ?? $content['link_url'] ?? '';
                    if ($request->filled('remove_about_opms_image') && ! empty($content['image'])) {
                        $this->deleteImage($content['image']);
                        $content['image'] = '';
                    } elseif ($request->hasFile("sections.{$key}.image")) {
                        if (! empty($content['image'])) {
                            $this->deleteImage($content['image']);
                        }
                        $content['image'] = $this->uploadImage($request->file("sections.{$key}.image"), 'homepage');
                    }
                    $content = $this->purifyHtmlKeys($content, ['heading', 'paragraph']);
                    foreach ($content['bullets'] ?? [] as $i => $bullet) {
                        if (isset($bullet['text']) && is_string($bullet['text'])) {
                            $content['bullets'][$i]['text'] = \Mews\Purifier\Facades\Purifier::clean($bullet['text']);
                        }
                    }
                    break;
                case 'how_it_works':
                    $content['title'] = $data['title'] ?? $content['title'] ?? '';
                    $content['steps'] = array_values($data['steps'] ?? $content['steps'] ?? []);
                    foreach ($content['steps'] as $i => $step) {
                        if (isset($step['description']) && is_string($step['description'])) {
                            $content['steps'][$i]['description'] = \Mews\Purifier\Facades\Purifier::clean($step['description']);
                        }
                    }
                    break;
                case 'user_features':
                    $content['left_title'] = $data['left_title'] ?? $content['left_title'] ?? '';
                    $leftText = $data['left_items_text'] ?? null;
                    $content['left_items'] = $leftText !== null && $leftText !== ''
                        ? array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $leftText))))
                        : array_values(array_filter(array_map('trim', $data['left_items'] ?? $content['left_items'] ?? [])));
                    $content['right_title'] = $data['right_title'] ?? $content['right_title'] ?? '';
                    $rightText = $data['right_items_text'] ?? null;
                    $content['right_items'] = $rightText !== null && $rightText !== ''
                        ? array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $rightText))))
                        : array_values(array_filter(array_map('trim', $data['right_items'] ?? $content['right_items'] ?? [])));
                    break;
                case 'competition':
                    $content['heading'] = $data['heading'] ?? $content['heading'] ?? '';
                    $content['paragraph'] = $data['paragraph'] ?? $content['paragraph'] ?? '';
                    $content['boxes'] = array_values($data['boxes'] ?? $content['boxes'] ?? []);
                    $content = $this->purifyHtmlKeys($content, ['heading', 'paragraph']);
                    break;
                case 'latest_updates':
                    $content['title'] = $data['title'] ?? $content['title'] ?? '';
                    break;
                case 'bottom_cta':
                    $content['heading'] = $data['heading'] ?? $content['heading'] ?? '';
                    $content['subtext'] = $data['subtext'] ?? $content['subtext'] ?? '';
                    $content['cta_primary_text'] = $data['cta_primary_text'] ?? $content['cta_primary_text'] ?? '';
                    $content['cta_primary_url'] = $data['cta_primary_url'] ?? $content['cta_primary_url'] ?? '';
                    $content['cta_secondary_text'] = $data['cta_secondary_text'] ?? $content['cta_secondary_text'] ?? '';
                    $content['cta_secondary_url'] = $data['cta_secondary_url'] ?? $content['cta_secondary_url'] ?? '';
                    $content = $this->purifyHtmlKeys($content, ['heading', 'subtext']);
                    break;
                default:
                    $content = array_merge($content, $data);
            }

            $section->content = $content;
            $section->is_active = true;
            $section->save();
        }

        $this->logUpdate($section ?? null);

        return redirect()->route('admin.homepage.edit')->with('success', 'Homepage content updated successfully.');
    }

    private static function defaultContent(string $key): array
    {
        return match ($key) {
            'hero' => [
                'label' => '',
                'heading' => '',
                'paragraph' => '',
                'bullets' => [['icon' => 'check', 'text' => ''], ['icon' => 'check', 'text' => '']],
                'cta_primary_text' => '',
                'cta_primary_url' => '',
                'cta_secondary_text' => '',
                'cta_secondary_url' => '',
                'image' => '',
            ],
            'feature_cards' => [
                'title' => '',
                'cards' => [
                    ['icon' => '', 'title' => '', 'description' => '', 'link_text' => 'Read more', 'link_url' => ''],
                    ['icon' => '', 'title' => '', 'description' => '', 'link_text' => 'Read more', 'link_url' => ''],
                ],
            ],
            'about_opms' => [
                'label' => '',
                'heading' => '',
                'paragraph' => '',
                'bullets' => [['icon' => 'check', 'text' => ''], ['icon' => 'check', 'text' => '']],
                'link_text' => '',
                'link_url' => '',
                'image' => '',
            ],
            'how_it_works' => [
                'title' => '',
                'steps' => [
                    ['number' => '1', 'title' => '', 'description' => ''],
                    ['number' => '2', 'title' => '', 'description' => ''],
                ],
            ],
            'user_features' => [
                'left_title' => '',
                'left_items' => [],
                'right_title' => '',
                'right_items' => [],
            ],
            'competition' => [
                'heading' => '',
                'paragraph' => '',
                'boxes' => [
                    ['value' => '', 'label' => ''],
                    ['value' => '', 'label' => ''],
                ],
            ],
            'latest_updates' => ['title' => ''],
            'bottom_cta' => [
                'heading' => '',
                'subtext' => '',
                'cta_primary_text' => '',
                'cta_primary_url' => '',
                'cta_secondary_text' => '',
                'cta_secondary_url' => '',
            ],
            default => [],
        };
    }
}
