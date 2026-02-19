<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\Page;

class PageController extends Controller
{
    use SeoSetTrait;

    /**
     * Display a listing of pages
     */
    public function index()
    {
        $pages = Page::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate(12);

        // Set SEO tags for pages index
        $this->setSeoTags([
            'google_title' => 'Pagina\'s - '.get_setting('site_name'),
            'google_description' => 'Bekijk onze pagina\'s.',
            'google_image' => get_image(get_setting('site_logo'), asset('images/pages-og-image.jpg')),
        ]);

        return view('front.page.index', compact('pages'));
    }

    /**
     * Display the specified page
     */
    public function show(Page $page)
    {
        // Check if page is active
        if (! $page->is_active) {
            abort(404);
        }

        $page->load(['marketingPersona', 'contentType']);

        $description = $page->short_body ?? $page->meta_body ?? $page->title;

        $this->setSeoTags([
            'google_title' => $page->meta_title ?? $page->title,
            'google_description' => $description,
            'google_image' => ! empty($page->image) ? asset($page->image) : get_image(get_setting('site_logo')),
        ]);

        return view('front.page.show', compact('page'));
    }

    public static function events(): array
    {
        return [
            [
                'month' => 'July',
                'year' => '2012',
                'description' => 'Teamleader takes its first steps.',
            ],
            [
                'month' => 'May',
                'year' => '2014',
                'description' => '€1 million first investment.',
            ],
            [
                'month' => 'May',
                'year' => '2015',
                'description' => 'Teamleader goes international.',
            ],
            [
                'month' => 'November',
                'year' => '2017',
                'description' => 'Winner of Deloitte Fast 50.',
            ],
            [
                'month' => 'January',
                'year' => '2019',
                'description' => 'Teamleader welcomes its 10,000th customer.',
            ],
            [
                'month' => 'May',
                'year' => '2019',
                'description' => '5th edition of the Work Smarter event.',
            ],
            [
                'month' => 'August',
                'year' => '2019',
                'description' => 'Yadera becomes part of the Teamleader family.',
            ],
            [
                'month' => 'June',
                'year' => '2021',
                'description' => 'Name changed to Teamleader Focus (Teamleader) and Teamleader Orbit (Yadera).',
            ],
            [
                'month' => 'April',
                'year' => '2022',
                'description' => 'Vectera becomes part of the Teamleader family.',
            ],
            [
                'month' => 'June',
                'year' => '2022',
                'description' => 'Teamleader joins Visma.',
            ],
            [
                'month' => 'July',
                'year' => '2022',
                'description' => 'Teamleader celebrates its tenth birthday.',
            ],
        ];
    }
}
