<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OrganizationName;
use App\Models\Solution;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class TrialController extends Controller
{
    #[OA\Get(path: '/api/proefversie', summary: 'Trial page data', description: 'Organisations and solutions for trial form.', tags: ['Trial'], responses: [
        new OA\Response(response: 200, description: 'Organisations and solutions'),
    ])]
    public function index(): JsonResponse
    {
        $organisations = OrganizationName::get(['id', 'name', 'abbreviation', 'email', 'address']);
        $solutions = Solution::get(['id', 'title', 'subtitle']);

        return response()->json([
            'data' => [
                'organisations' => $organisations,
                'solutions' => $solutions,
            ],
        ]);
    }

    #[OA\Get(path: '/api/proefversie/success', summary: 'Trial success message', tags: ['Trial'], responses: [
        new OA\Response(response: 200, description: 'Success message'),
    ])]
    public function success(): JsonResponse
    {
        return response()->json([
            'data' => [
                'message' => 'Aanvraag ontvangen. We nemen zo snel mogelijk contact met je op.',
            ],
        ]);
    }
}
