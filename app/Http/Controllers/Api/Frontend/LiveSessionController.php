<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\LiveSessionListResource;
use App\Http\Resources\LiveSessionResource;
use App\Models\LiveSession;
use App\Models\SessionRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class LiveSessionController extends Controller
{
    #[OA\Get(path: '/api/live-sessions', summary: 'List live sessions', description: 'Upcoming and past live sessions.', tags: ['Live sessions'], responses: [
        new OA\Response(response: 200, description: 'Upcoming and past with past_meta'),
    ])]
    public function index()
    {
        $upcoming = LiveSession::active()->upcoming()->ordered()->with(['presenters'])->get();
        $past = LiveSession::completed()->ordered()->paginate(12);

        return response()->json([
            'upcoming' => LiveSessionListResource::collection($upcoming),
            'past' => LiveSessionListResource::collection($past),
            'past_meta' => [
                'current_page' => $past->currentPage(),
                'last_page' => $past->lastPage(),
                'per_page' => $past->perPage(),
                'total' => $past->total(),
            ],
        ]);
    }

    #[OA\Get(path: '/api/academy/live-sessions/recordings', summary: 'Live session recordings', description: 'Paginated past sessions.', tags: ['Live sessions'], parameters: [
        new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 12)),
    ], responses: [
        new OA\Response(response: 200, description: 'Past sessions with meta'),
    ])]
    public function recordings(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->input('per_page', 12), 50));
        $past = LiveSession::completed()->ordered()->paginate($perPage);

        return response()->json([
            'data' => LiveSessionListResource::collection($past->getCollection()),
            'meta' => [
                'current_page' => $past->currentPage(),
                'last_page' => $past->lastPage(),
                'per_page' => $past->perPage(),
                'total' => $past->total(),
            ],
        ]);
    }

    #[OA\Get(path: '/api/live-sessions/{slug}', summary: 'Live session by slug', tags: ['Live sessions'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Live session'),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function show(string $slug)
    {
        $session = LiveSession::where('slug', $slug)->where('is_active', true)->with(['presenters'])->firstOrFail();

        return new LiveSessionResource($session);
    }

    #[OA\Post(path: '/api/academy/live-sessions/{slug}/register', summary: 'Register for live session', description: 'Body: name, email, organization, marketing_consent?', tags: ['Live sessions'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 201, description: 'Registered'),
        new OA\Response(response: 422, description: 'Validation error'),
    ])]
    public function register(Request $request, string $slug): JsonResponse
    {
        $session = LiveSession::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'organization' => 'required|string|max:255',
            'marketing_consent' => 'nullable|boolean',
        ]);

        $registration = new SessionRegistration($validated);
        $registration->live_session_id = $session->id;
        $registration->status = 'registered';
        $registration->marketing_consent = $request->boolean('marketing_consent');
        $registration->save();

        return response()->json([
            'success' => true,
            'message' => 'Je bent succesvol geregistreerd voor deze sessie!',
            'data' => ['id' => $registration->id],
        ], 201);
    }
}
