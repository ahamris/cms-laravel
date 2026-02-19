<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\PricingBooster;
use App\Models\PricingFeature;
use App\Models\PricingPlan;
use Illuminate\View\View;

class PricingController extends Controller
{
    use SeoSetTrait;
    /**
     * Display the pricing page.
     */
    public function index(): View
    {
        // Set SEO tags for pricing page
        $this->setSeoTags([
            'google_title' => 'Prijzen & Pakketten - ' . get_setting('site_name'),
            'google_description' => 'Bekijk onze transparante prijzen en kies het pakket dat bij jouw organisatie past.',
            'google_image' => get_image(get_setting('site_logo'), asset('images/pricing-og-image.jpg')),
        ]);

        // Get cached pricing data
        $plans = PricingPlan::getCached();
        $boosters = PricingBooster::getCached();
        $features = PricingFeature::getCachedGrouped();

        return view('front.pricing.index', compact('plans', 'boosters', 'features'));
    }

    /**
     * Display the pricing configurator.
     */
    public function configurator(): View
    {
        // Set SEO tags for configurator
        $this->setSeoTags([
            'google_title' => 'Prijs Configurator - ' . get_setting('site_name'),
            'google_description' => 'Stel je eigen pakket samen en bereken direct de prijs.',
            'google_image' => asset('images/configurator-og-image.jpg'),
        ]);

        $boosters = PricingBooster::getCached();
        
        return view('front.pricing.configurator', compact('boosters'));
    }

    /**
     * Display a specific pricing plan.
     */
    public function show(string $slug): View
    {
        $plan = PricingPlan::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Set SEO tags for pricing plan
        $this->setSeoTags([
            'google_title' => $plan->name . ' - Prijzen - ' . get_setting('site_name'),
            'google_description' => $plan->description ?: 'Bekijk de details van het ' . $plan->name . ' pakket.',
            'google_image' => asset('images/pricing-og-image.jpg'),
        ]);

        // Get all plans for comparison
        $plans = PricingPlan::getCached();
        $boosters = PricingBooster::getCached();
        
        // Get features available in this plan
        $features = PricingFeature::active()
            ->ordered()
            ->get()
            ->filter(fn($feature) => $feature->isAvailableInPlan($slug));

        return view('front.pricing.show', compact('plan', 'plans', 'boosters', 'features'));
    }
}
