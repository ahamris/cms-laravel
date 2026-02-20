<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModuleListResource;
use App\Http\Resources\ModuleResource;
use App\Models\Module;
use OpenApi\Attributes as OA;

class ModuleController extends Controller
{
    #[OA\Get(path: '/api/modules', summary: 'List modules', description: 'Active modules with features.', tags: ['Modules'], responses: [
        new OA\Response(response: 200, description: 'Modules collection'),
    ])]
    public function index()
    {
        $modules = Module::active()
            ->ordered()
            ->with(['features' => fn ($q) => $q->where('is_active', true)->ordered()])
            ->get();

        return ModuleListResource::collection($modules);
    }

    #[OA\Get(path: '/api/modules/{slug}', summary: 'Module by slug', tags: ['Modules'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Module'),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
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
