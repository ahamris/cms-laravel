<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HandlesNavigationLinks
{
    /**
     * Process and validate the URL based on the selected link type.
     *
     * @param Request $request
     * @param array $validated
     * @return array
     */
    protected function processUrlByLinkType(Request $request, array $validated): array
    {
        $linkType = $request->input('link_type', 'custom');

        // Ensure link_type is set in validated data
        $validated['link_type'] = $linkType;

        if ($linkType === 'page') {
            // For page references, set page_id and clear URL
            $pageId = $request->input('page_id');

            if ($pageId) {
                $validated['page_id'] = $pageId;
                $validated['url'] = null; // Clear URL when using page reference
            } else {
                // If no page_id provided but link_type is 'page', fallback to custom
                $validated['page_id'] = null;
                $validated['url'] = $request->input('url', '#');
            }
        } elseif ($linkType === 'predefined') {
            // Validate predefined route
            $predefinedRoute = $request->input('predefined_route', $request->input('url'));
            $validated['url'] = $predefinedRoute;
            $validated['page_id'] = null;
        } elseif ($linkType === 'system') {
            // Validate system content
            $systemContent = $request->input('system_content', $request->input('url'));
            $validated['url'] = $systemContent;
            $validated['page_id'] = null;
        } else {
            // For custom URLs, use the provided URL as-is but with basic validation
            $customUrl = $request->input('custom_url', $request->input('url', ''));

            if (!empty($customUrl)) {
                // Allow relative paths, absolute URLs, or # for no link
                if (
                    $customUrl !== '#' &&
                    !str_starts_with($customUrl, '/') &&
                    !str_starts_with($customUrl, 'http://') &&
                    !str_starts_with($customUrl, 'https://') &&
                    !str_starts_with($customUrl, 'mailto:') &&
                    !str_starts_with($customUrl, 'tel:')
                ) {
                    // Prepend / for relative paths if it doesn't look like a protocol
                    $customUrl = '/' . ltrim($customUrl, '/');
                }
            }

            $validated['url'] = $customUrl;
            // Clear page_id when using custom URL
            $validated['page_id'] = null;
        }

        // Ensure URL is not empty for validation purposes (only if not using page reference)
        if ($linkType !== 'page' && empty($validated['url'])) {
            $validated['url'] = '#';
        }

        return $validated;
    }
}
