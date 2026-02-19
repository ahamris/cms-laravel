<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\OrganizationName;
use App\Models\Solution;
use Illuminate\Http\Request;

class TrialController extends Controller
{
    use SeoSetTrait;
    public function index()
    {
        // Set SEO tags for trial page
        $this->setSeoTags([
            'google_title' => 'Gratis Proefversie - ' . get_setting('site_name'),
            'google_description' => 'Start vandaag nog met een gratis proefversie van OpenPublicatie.',
            'google_image' => asset('images/trial-og-image.jpg'),
        ]);

        $organisations = OrganizationName::get(['id', 'name', 'abbreviation', 'email', 'address']);
        $solutions = Solution::get(['id', 'title', 'subtitle']);

        return view('front.trial.index', compact('organisations', 'solutions'));
    }

    public function success()
    {
        // Set SEO tags for success page
        $this->setSeoTags([
            'google_title' => 'Aanvraag Ontvangen - ' . get_setting('site_name'),
            'google_description' => 'Bedankt voor je aanvraag. We nemen zo snel mogelijk contact met je op.',
            'google_image' => asset('images/success-og-image.jpg'),
        ]);

        return view('front.trial.success');
    }
}
