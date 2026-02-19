<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\VacancyListResource;
use App\Http\Resources\VacancyResource;
use App\Models\VacancyModule\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    /**
     * List active vacancies (with filters, paginated).
     */
    public function index(Request $request)
    {
        $query = Vacancy::active();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('department', 'like', '%'.$search.'%');
            });
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

    /**
     * Single vacancy by slug.
     */
    public function show(string $slug)
    {
        $vacancy = Vacancy::where('slug', $slug)->active()->firstOrFail();

        return new VacancyResource($vacancy);
    }
}
