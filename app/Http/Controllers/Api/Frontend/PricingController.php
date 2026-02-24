<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PricingBooster;
use App\Models\PricingFeature;
use App\Models\PricingPlan;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PricingController extends Controller
{
    #[OA\Get(path: '/api/prijzen', summary: 'Pricing index', description: 'Plans, boosters, and features.', tags: ['Pricing'], responses: [
        new OA\Response(response: 200, description: 'Pricing data (plans, boosters, features)'),
    ])]
    public function index(): JsonResponse
    {
        $plans = PricingPlan::getCached();
        $boosters = PricingBooster::getCached();
        $features = PricingFeature::getCachedGrouped();

        return response()->json([
            'template' => 'pricing',
            'data' => [
                'plans' => $plans,
                'boosters' => $boosters,
                'features' => $features,
            ],
        ]);
    }

    #[OA\Get(path: '/api/prijzen/configurator', summary: 'Pricing configurator', description: 'Boosters for configurator.', tags: ['Pricing'], responses: [
        new OA\Response(response: 200, description: 'Boosters data'),
    ])]
    public function configurator(): JsonResponse
    {
        $boosters = PricingBooster::getCached();

        return response()->json(['template' => 'pricing-configurator', 'data' => ['boosters' => $boosters]]);
    }

    #[OA\Get(path: '/api/prijzen/{slug}', summary: 'Pricing plan by slug', tags: ['Pricing'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Plan with plans, boosters, features'),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function show(string $slug): JsonResponse
    {
        $plan = PricingPlan::where('slug', $slug)->where('is_active', true)->first();
        if (! $plan) {
            return response()->json(['message' => 'Plan not found.'], 404);
        }

        $plans = PricingPlan::getCached();
        $boosters = PricingBooster::getCached();
        $features = PricingFeature::active()
            ->ordered()
            ->get()
            ->filter(fn ($f) => $f->isAvailableInPlan($slug))
            ->values();

        return response()->json([
            'template' => 'pricing-plan',
            'data' => [
                'plan' => $plan,
                'plans' => $plans,
                'boosters' => $boosters,
                'features' => $features,
            ],
        ]);
    }
}
