<?php

namespace App\Http\Controllers\Admin\VacancyModule;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\VacancyModule\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VacancyController extends AdminBaseController
{
    /**
     * Display a listing of the vacancies.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $vacancies = Vacancy::withCount('applications')->latest()->paginate(15);

        // Calculate stats from database queries instead of collection
        $stats = [
            'total' => Vacancy::count(),
            'active' => Vacancy::where('is_active', true)->count(),
            'inactive' => Vacancy::where('is_active', false)->count(),
            'applications' => \App\Models\VacancyModule\JobApplication::count(),
        ];

        return view('admin.vacancies.index', compact('vacancies', 'stats'));
    }

    /**
     * Show the form for creating a new vacancy.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.vacancies.create');
    }

    /**
     * Store a newly created vacancy.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'slug' => ['nullable', 'string'],
            'location' => ['required', 'string'],
            'short_code' => ['required', 'string', 'in:BE,FE,MM,DO,QA,AI,HR,IT,PM'],
            'type' => ['required', 'string', 'in:full-time,part-time,contract,remote,project-based'],
            'department' => ['required', 'string'],
            'description' => ['required', 'string'],
            'requirements' => ['required', 'string'],
            'responsibilities' => ['required', 'string'],
            'salary_range' => ['required', 'string'],
            'closing_date' => ['required', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Title is required',
            'location.required' => 'Location is required',
            'short_code.required' => 'Short code is required',
            'type.required' => 'Type is required',
            'department.required' => 'Department is required',
            'description.required' => 'Description is required',
            'requirements.required' => 'Requirements is required',
            'responsibilities.required' => 'Responsibilities is required',
            'salary_range.required' => 'Salary range is required',
            'closing_date.required' => 'Closing date is required',
        ]);
        $validated = $this->purifyHtmlKeys($validated, ['description', 'requirements', 'responsibilities']);

        Vacancy::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? Str::slug($validated['title']),
            'location' => $validated['location'],
            'short_code' => $validated['short_code'],
            'type' => $validated['type'],
            'department' => $validated['department'],
            'description' => $validated['description'],
            'requirements' => $validated['requirements'],
            'responsibilities' => $validated['responsibilities'],
            'salary_range' => $validated['salary_range'],
            'closing_date' => $validated['closing_date'],
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.vacancies.index')->with('success', 'Vacancy created successfully.');
    }

    /**
     * Display the specified vacancy.
     *
     * @param  \App\Models\VacancyModule\Vacancy  $vacancy
     * @return \Illuminate\View\View
     */
    public function show(Vacancy $vacancy)
    {
        $vacancy->loadCount('applications');
        return view('admin.vacancies.show', compact('vacancy'));
    }

    /**
     * Show the form for editing the specified vacancy.
     *
     * @param  \App\Models\VacancyModule\Vacancy  $vacancy
     * @return \Illuminate\View\View
     */
    public function edit(Vacancy $vacancy)
    {
        $vacancy->loadCount('applications');
        return view('admin.vacancies.edit', compact('vacancy'));
    }

    /**
     * Update the specified vacancy.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VacancyModule\Vacancy  $vacancy
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Vacancy $vacancy)
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'slug' => ['nullable', 'string'],
            'location' => ['required', 'string'],
            'short_code' => ['required', 'string', 'in:BE,FE,MM,DO,QA,AI,HR,IT,PM'],
            'type' => ['required', 'string', 'in:full-time,part-time,contract,remote,project-based'],
            'department' => ['required', 'string'],
            'description' => ['required', 'string'],
            'requirements' => ['required', 'string'],
            'responsibilities' => ['required', 'string'],
            'salary_range' => ['required', 'string'],
            'closing_date' => ['required', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Title is required',
            'location.required' => 'Location is required',
            'short_code.required' => 'Short code is required',
            'type.required' => 'Type is required',
            'department.required' => 'Department is required',
            'description.required' => 'Description is required',
            'requirements.required' => 'Requirements is required',
            'responsibilities.required' => 'Responsibilities is required',
            'salary_range.required' => 'Salary range is required',
            'closing_date.required' => 'Closing date is required',
        ]);
        $validated = $this->purifyHtmlKeys($validated, ['description', 'requirements', 'responsibilities']);

        $vacancy->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? Str::slug($validated['title']),
            'location' => $validated['location'],
            'short_code' => $validated['short_code'],
            'type' => $validated['type'],
            'department' => $validated['department'],
            'description' => $validated['description'],
            'requirements' => $validated['requirements'],
            'responsibilities' => $validated['responsibilities'],
            'salary_range' => $validated['salary_range'],
            'closing_date' => $validated['closing_date'],
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.vacancies.index')->with('success', 'Vacancy updated successfully.');
    }

    /**
     * Update vacancy status (for inline editing).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VacancyModule\Vacancy  $vacancy
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, Vacancy $vacancy)
    {
        $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $vacancy->update([
            'is_active' => $request->boolean('is_active'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
        ]);
    }

    /**
     * Remove the specified vacancy.
     *
     * @param  \App\Models\VacancyModule\Vacancy  $vacancy
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();

        return redirect()->route('admin.vacancies.index')->with('success', 'Vacancy deleted successfully.');
    }
}

