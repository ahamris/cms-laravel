<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\Page;
use Illuminate\View\View;

class HomeController extends Controller
{
    use SeoSetTrait;

    /**
     * Display the home page.
     */
    public function index()
    {
        // Check if a homepage page is set
        $homepagePage = Page::getHomepage();

        if ($homepagePage) {

            // Set SEO tags for homepage
            $this->setSeoTags([
                'google_title' => trim(get_setting('site_name', config('app.name')).(get_setting('site_tagline') ? ' - '.get_setting('site_tagline') : '')),
                'google_description' => get_setting('site_description'),
                'google_image' => get_image(get_setting('site_logo')),
            ]);

            // Use PageController to display the homepage page
            $pageController = new PageController;

            return $pageController->show($homepagePage);
        }

        // Fallback to default homepage view

        return view('front.home.index');
    }
}
