<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;

class HomeController extends Controller
{
    use SeoSetTrait;

    /**
     * Display the home page.
     */
    public function index()
    {
        $this->setSeoTags([
            'google_title' => trim(get_setting('site_name', config('app.name')).(get_setting('site_tagline') ? ' - '.get_setting('site_tagline') : '')),
            'google_description' => get_setting('site_description'),
            'google_image' => get_image(get_setting('site_logo')),
        ]);

        return view('front.home.index');
    }
}
