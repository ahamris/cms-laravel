<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModuleListResource;
use App\Http\Resources\ModuleResource;
use App\Models\Module;

class ModuleController extends Controller
{
    /**
     * List active modules (with features).
     */
    public function index()
    {
        $modules = Module::active()
            ->ordered()
            ->with(['features' => fn ($q) => $q->where('is_active', true)->ordered()])
            ->get();

        return ModuleListResource::collection($modules);
    }

    /**
     * Single module by slug.
     */
    public function show(string $slug)
    {
        $module = Module::where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'features' => fn ($q) => $q->where('is_active', true)->ordered(),
                'solutions' => fn ($q) => $q->where('is_active', true)->ordered(),
            ])
            ->firstOrFail();

        return new ModuleResource($module);
    }
}
