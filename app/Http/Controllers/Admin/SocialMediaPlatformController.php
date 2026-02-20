<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\SocialMediaPlatform;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SocialMediaPlatformController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $platforms = SocialMediaPlatform::ordered()->get();
        
        return view('admin.social-media-platforms.index', compact('platforms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $supportedSlugs = SocialMediaPlatform::supportedAutoPostSlugs();
        $credentialFieldsBySlug = [];
        foreach ($supportedSlugs as $slug) {
            $credentialFieldsBySlug[$slug] = SocialMediaPlatform::getCredentialFieldsForSlug($slug);
        }

        return view('admin.social-media-platforms.create', [
            'supportedSlugs' => $supportedSlugs,
            'credentialFieldsBySlug' => $credentialFieldsBySlug,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:social_media_platforms',
            'icon' => 'nullable|string|max:255',
            'color' => 'required|string|size:7',
            'is_active' => 'nullable',
            'sort_order' => 'nullable|integer|min:0',
            'api_credentials' => 'nullable|array',
            'api_credentials.*' => 'nullable|string|max:2000',
        ]);

        $data = $request->only(['name', 'slug', 'icon', 'color', 'sort_order']);
        $data['is_active'] = $request->input('is_active', '0') === '1';
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['api_credentials'] = $this->sanitizeCredentials(
            $request->input('api_credentials', []),
            $request->input('slug')
        );

        SocialMediaPlatform::create($data);

        return redirect()->route('admin.settings.social-media-platforms.index')
            ->with('success', 'Social media platform created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialMediaPlatform $socialMediaPlatform): View
    {
        return view('admin.social-media-platforms.show', compact('socialMediaPlatform'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialMediaPlatform $socialMediaPlatform): View
    {
        $credentialFields = SocialMediaPlatform::getCredentialFieldsForSlug($socialMediaPlatform->slug);
        $supportedSlugs = SocialMediaPlatform::supportedAutoPostSlugs();

        return view('admin.social-media-platforms.edit', [
            'socialMediaPlatform' => $socialMediaPlatform,
            'credentialFields' => $credentialFields,
            'supportedSlugs' => $supportedSlugs,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocialMediaPlatform $socialMediaPlatform)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:social_media_platforms,slug,' . $socialMediaPlatform->id,
            'icon' => 'nullable|string|max:255',
            'color' => 'required|string|size:7',
            'is_active' => 'nullable',
            'sort_order' => 'nullable|integer|min:0',
            'api_credentials' => 'nullable|array',
            'api_credentials.*' => 'nullable|string|max:2000',
        ]);

        $data = $request->only(['name', 'slug', 'icon', 'color', 'sort_order']);
        $data['is_active'] = $request->input('is_active', '0') === '1';
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['api_credentials'] = $this->sanitizeCredentials(
            $request->input('api_credentials', []),
            $request->input('slug'),
            $socialMediaPlatform->api_credentials ?? []
        );

        $socialMediaPlatform->update($data);

        return redirect()->route('admin.settings.social-media-platforms.index')
            ->with('success', 'Social media platform updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialMediaPlatform $socialMediaPlatform)
    {
        $socialMediaPlatform->delete();

        return redirect()->route('admin.settings.social-media-platforms.index')
            ->with('success', 'Social media platform deleted successfully!');
    }

    /**
     * Toggle platform active status
     */
    public function toggleActive(SocialMediaPlatform $socialMediaPlatform)
    {
        $socialMediaPlatform->update(['is_active' => !$socialMediaPlatform->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $socialMediaPlatform->is_active,
            'message' => $socialMediaPlatform->is_active ? 'Platform activated successfully!' : 'Platform deactivated successfully!',
        ]);
    }

    /**
     * Keep only allowed credential keys for the given slug. On update, keep existing value if input is empty.
     */
    private function sanitizeCredentials(array $input, string $slug, array $existing = []): array
    {
        $fields = SocialMediaPlatform::getCredentialFieldsForSlug($slug);
        $allowedKeys = array_column($fields, 'key');
        $out = [];
        foreach ($allowedKeys as $key) {
            $inVal = $input[$key] ?? null;
            if ($inVal !== null && (string) $inVal !== '') {
                $out[$key] = $inVal;
            } elseif (array_key_exists($key, $existing) && (string) $existing[$key] !== '') {
                $out[$key] = $existing[$key];
            }
        }

        return $out;
    }
}
