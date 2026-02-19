<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\Legal;
use Illuminate\View\View;

class LegalController extends Controller
{
    use SeoSetTrait;

    /**
     * Display the specified legal page.
     */
    public function show(Legal $legal): View
    {
        // Only show active legal pages
        if (!$legal->is_active) {
            abort(404);
        }

        // Get all versions for dropdown
        $versions = $legal->versions()
            ->with('creator')
            ->latest('version_number')
            ->get();

        // Check if version is specified in query parameter
        $displayVersion = null;
        $versionNumber = request()->query('version');
        if ($versionNumber) {
            $displayVersion = $legal->getVersion((int)$versionNumber);
            if (!$displayVersion) {
                abort(404, 'Version not found');
            }
        }

        // Set SEO tags for legal page
        $this->setSeoTags([
            'google_title' => $legal->meta_title ?: $legal->title,
            'google_description' => $legal->meta_description ?: $legal->short_body,
            'google_image' => get_image($legal->image, asset('images/legal-og-image.jpg')),
        ]);

        return view('front.legal.show', compact('legal', 'versions', 'displayVersion'));
    }
}
