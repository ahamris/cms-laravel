<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\Module;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    use SeoSetTrait;
    public function index()
    {
        $this->setSeoTags([
            'google_title' => 'Modules - ' . get_setting('site_name'),
            'google_description' => 'Ontdek alle modules en functionaliteiten van ons platform.',
            'google_image' => get_image(get_setting('site_logo'), asset('images/modules-og-image.jpg')),
        ]);

        $modules = Module::active()->ordered()
            ->with(['features' => function ($q) {
                $q->where('is_active', true)->ordered();
            }])
            ->get();

        return view('front.module.index', compact('modules'));
    }

    public function show(Module $module)
    {
        if (! $module->is_active) {
            abort(404);
        }

        $module->load([
            'features' => function ($q) {
                $q->where('is_active', true)->ordered();
            },
            'solutions' => function ($q) {
                $q->where('is_active', true)->ordered();
            },
        ]);

        $this->setSeoTags([
            'google_title' => $module->meta_title ?: $module->title,
            'google_description' => $module->meta_description ?: Str::limit(strip_tags($module->short_body), 160),
            'google_image' => get_image($module->image, asset('images/modules-og-image.jpg')),
        ]);

        return view('front.module.show', compact('module'));
    }
}
