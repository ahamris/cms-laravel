<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\VacancyListResource;
use App\Http\Resources\VacancyResource;
use App\Models\VacancyModule\JobApplication;
use App\Models\VacancyModule\Vacancy;
use App\Notifications\NewJobApplicationNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

class VacancyController extends Controller
{
    #[OA\Get(path: '/api/vacancies', summary: 'List vacancies', description: 'Paginated with filters: search, type, location, department, category, per_page.', tags: ['Vacancies'], parameters: [
        new OA\Parameter(name: 'search', in: 'query', schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'type', in: 'query', schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'location', in: 'query', schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'department', in: 'query', schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'category', in: 'query', schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 10)),
    ], responses: [
        new OA\Response(response: 200, description: 'Vacancies with meta and filters'),
    ])]
    public function index(Request $request)
    {
        $query = Vacancy::active();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereAny(['title', 'description', 'department'], 'like', '%'.$search.'%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%'.$request->input('location').'%');
        }
        if ($request->filled('department')) {
            $query->where('department', $request->input('department'));
        }
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $perPage = max(1, min((int) $request->input('per_page', 10), 50));
        $vacancies = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $data = VacancyListResource::collection($vacancies->getCollection())->resolve();
        $vacancies->setCollection(collect($data));

        return response()->json([
            'data' => $vacancies->items(),
            'template' => 'vacancies-list',
            'meta' => [
                'current_page' => $vacancies->currentPage(),
                'last_page' => $vacancies->lastPage(),
                'per_page' => $vacancies->perPage(),
                'total' => $vacancies->total(),
                'from' => $vacancies->firstItem(),
                'to' => $vacancies->lastItem(),
            ],
            'filters' => [
                'departments' => Vacancy::active()->whereNotNull('department')->distinct()->pluck('department'),
                'locations' => Vacancy::active()->whereNotNull('location')->distinct()->pluck('location'),
                'categories' => Vacancy::active()->whereNotNull('category')->distinct()->pluck('category'),
            ],
        ]);
    }

    #[OA\Get(path: '/api/vacancies/search', summary: 'Search vacancies', description: 'Search in title, description, department. Throttled. Query: q, per_page.', tags: ['Vacancies'], parameters: [
        new OA\Parameter(name: 'q', in: 'query', required: true, schema: new OA\Schema(type: 'string', minLength: 2)),
        new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 20)),
    ], responses: [
        new OA\Response(response: 200, description: 'Search results'),
        new OA\Response(response: 429, description: 'Too many requests'),
    ])]
    public function search(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('q', ''));
        $perPage = max(1, min((int) $request->input('per_page', 20), 50));

        if (strlen($query) < 2) {
            return response()->json([
                'data' => [],
                'template' => 'vacancies-search',
                'query' => $query,
                'count' => 0,
                'meta' => ['current_page' => 1, 'last_page' => 1, 'per_page' => $perPage, 'total' => 0],
            ]);
        }

        $search = $query;
        $vacancies = Vacancy::active()
            ->whereAny(['title', 'description', 'department'], 'like', '%'.$search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $resolved = VacancyListResource::collection($vacancies->getCollection())->resolve();

        return response()->json([
            'data' => $resolved['data'] ?? $resolved,
            'template' => 'vacancies-search',
            'query' => $query,
            'count' => $vacancies->total(),
            'meta' => [
                'current_page' => $vacancies->currentPage(),
                'last_page' => $vacancies->lastPage(),
                'per_page' => $vacancies->perPage(),
                'total' => $vacancies->total(),
            ],
        ]);
    }

    #[OA\Get(path: '/api/vacancies/{slug}', summary: 'Vacancy by slug', tags: ['Vacancies'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Vacancy'),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function show(string $slug)
    {
        $vacancy = Vacancy::where('slug', $slug)->active()->first();
        if (! $vacancy) {
            return response()->json(['message' => 'Vacancy not found.'], 404);
        }

        return new VacancyResource($vacancy);
    }

    #[OA\Get(path: '/api/vacancies/{slug}/apply', summary: 'Vacancy apply data', description: 'Vacancy details for application form.', tags: ['Vacancies'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Vacancy data'),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function apply(string $slug): JsonResponse
    {
        $vacancy = Vacancy::where('slug', $slug)->active()->first();
        if (! $vacancy) {
            return response()->json(['message' => 'Vacancy not found.'], 404);
        }

        return response()->json(['data' => new VacancyResource($vacancy)]);
    }

    #[OA\Post(path: '/api/vacancies/{slug}/apply', summary: 'Submit job application', description: 'Body: name, email, phone?, cover_letter?, resume?, linkedin_url?, portfolio_url?, repo_url?', tags: ['Vacancies'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 201, description: 'Application submitted'),
        new OA\Response(response: 422, description: 'Validation error'),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function submit(Request $request, string $slug): JsonResponse
    {
        $vacancy = Vacancy::where('slug', $slug)->active()->first();
        if (! $vacancy) {
            return response()->json(['message' => 'Vacancy not found.'], 404);
        }

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'phone' => ['nullable', 'string', 'max:20'],
                'cover_letter' => ['nullable', 'string'],
                'resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
                'linkedin_url' => ['nullable', 'url', 'max:255'],
                'portfolio_url' => ['nullable', 'url', 'max:255'],
                'repo_url' => ['nullable', 'url', 'max:255'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        if (! empty($validated['cover_letter'])) {
            $validated['cover_letter'] = \Mews\Purifier\Facades\Purifier::clean($validated['cover_letter']);
        }

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        $application = JobApplication::create([
            'vacancy_id' => $vacancy->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'resume_path' => $resumePath,
            'linkedin_url' => $validated['linkedin_url'] ?? null,
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'repo_url' => $validated['repo_url'] ?? null,
            'status' => 'pending',
            'is_processed' => false,
        ]);

        try {
            $adminEmail = config('mail.from.address');
            Notification::route('mail', $adminEmail)
                ->notify(new NewJobApplicationNotification($application));
        } catch (\Exception $e) {
            // Log but don't fail the request
        }

        return response()->json([
            'success' => true,
            'message' => 'Je sollicitatie is succesvol verzonden!',
            'data' => ['id' => $application->id],
        ], 201);
    }
}
