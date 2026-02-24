<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModuleListResource;
use App\Http\Resources\ModuleResource;
use App\Models\Module;
use OpenApi\Attributes as OA;

class ModuleController extends Controller
{
    #[OA\Get(path: '/api/modules', summary: 'List modules', description: 'Active modules with parent feature. Hierarchy: solution → feature → module.', tags: ['Solution'], responses: [
        new OA\Response(response: 200, description: 'Modules collection', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/ModuleListItem')),
        ])),
    ])]
    public function index()
    {
        $modules = Module::active()
            ->ordered()
            ->with(['feature'])
            ->get();

        return ModuleListResource::collection($modules)->additional(['template' => 'modules-list']);
    }

    #[OA\Get(path: '/api/modules/{slug}', summary: 'Module by slug', tags: ['Solution'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Single module with parent feature', content: new OA\JsonContent(ref: '#/components/schemas/Module')),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function show(string $slug)
    {
        $module = Module::where('slug', $slug)
            ->where('is_active', true)
            ->with(['feature.solution'])
            ->first();
        if (! $module) {
            return response()->json(['message' => 'Module not found.'], 404);
        }

        return new ModuleResource($module);
    }
}
