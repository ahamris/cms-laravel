<?php

namespace App\Http\Controllers\Admin\VacancyModule;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\VacancyModule\JobApplication;
use App\Models\VacancyModule\Vacancy;
use Illuminate\Http\Request;

class JobApplicationController extends AdminBaseController
{
    /**
     * Display a listing of the job applications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = JobApplication::with('vacancy');

        // Filter by vacancy if provided
        $vacancyId = $request->get('vacancy_id');
        $selectedVacancy = null;

        if ($vacancyId) {
            $query->where('vacancy_id', $vacancyId);
            $selectedVacancy = \App\Models\VacancyModule\Vacancy::find($vacancyId);
        }

        $applications = $query->latest()->paginate(15);

        // Calculate stats from database queries instead of collection
        $stats = [
            'total' => JobApplication::count(),
            'pending' => JobApplication::where('status', 'pending')->count(),
            'reviewed' => JobApplication::where('status', 'reviewed')->count(),
            'shortlisted' => JobApplication::where('status', 'shortlisted')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
            'hired' => JobApplication::where('status', 'hired')->count(),
        ];

        return view('admin.job-applications.index', compact('applications', 'stats', 'selectedVacancy'));
    }

    /**
     * Show the form for creating a new job application.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $vacancies = Vacancy::where('is_active', true)->orderBy('title')->get();
        return view('admin.job-applications.create', compact('vacancies'));
    }

    /**
     * Store a newly created job application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vacancy_id' => ['required', 'exists:vacancies,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'cover_letter' => ['nullable', 'string'],
            'resume_path' => ['nullable', 'string'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'repo_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'in:pending,reviewed,shortlisted,rejected,hired'],
            'is_processed' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
        ], [
            'vacancy_id.required' => 'Vacancy is required.',
            'vacancy_id.exists' => 'Selected vacancy does not exist.',
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
        ]);
        $validated = $this->purifyHtmlKeys($validated, ['cover_letter', 'notes']);

        JobApplication::create([
            'vacancy_id' => $validated['vacancy_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'resume_path' => $validated['resume_path'] ?? null,
            'linkedin_url' => $validated['linkedin_url'] ?? null,
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'repo_url' => $validated['repo_url'] ?? null,
            'status' => $validated['status'],
            'is_processed' => $request->has('is_processed') ? 1 : 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.job-applications.index')->with('success', 'Job application created successfully.');
    }

    /**
     * Display the specified job application.
     *
     * @param  \App\Models\VacancyModule\JobApplication  $jobApplication
     * @return \Illuminate\View\View
     */
    public function show(JobApplication $jobApplication)
    {
        $jobApplication->load('vacancy');
        return view('admin.job-applications.show', compact('jobApplication'));
    }

    /**
     * Show the form for editing the specified job application.
     *
     * @param  \App\Models\VacancyModule\JobApplication  $jobApplication
     * @return \Illuminate\View\View
     */
    public function edit(JobApplication $jobApplication)
    {
        $jobApplication->load('vacancy');
        $vacancies = Vacancy::where('is_active', true)->orderBy('title')->get();
        return view('admin.job-applications.edit', compact('jobApplication', 'vacancies'));
    }

    /**
     * Update the specified job application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VacancyModule\JobApplication  $jobApplication
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, JobApplication $jobApplication)
    {
        $validated = $request->validate([
            'vacancy_id' => ['required', 'exists:vacancies,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'cover_letter' => ['nullable', 'string'],
            'resume_path' => ['nullable', 'string'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'repo_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'in:pending,reviewed,shortlisted,rejected,hired'],
            'is_processed' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
        ], [
            'vacancy_id.required' => 'Vacancy is required.',
            'vacancy_id.exists' => 'Selected vacancy does not exist.',
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
        ]);
        $validated = $this->purifyHtmlKeys($validated, ['cover_letter', 'notes']);

        $jobApplication->update([
            'vacancy_id' => $validated['vacancy_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'resume_path' => $validated['resume_path'] ?? null,
            'linkedin_url' => $validated['linkedin_url'] ?? null,
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'repo_url' => $validated['repo_url'] ?? null,
            'status' => $validated['status'],
            'is_processed' => $request->has('is_processed') ? 1 : 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.job-applications.index')->with('success', 'Job application updated successfully.');
    }

    /**
     * Remove the specified job application.
     *
     * @param  \App\Models\VacancyModule\JobApplication  $jobApplication
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(JobApplication $jobApplication)
    {
        $jobApplication->delete();

        return redirect()->route('admin.job-applications.index')->with('success', 'Job application deleted successfully.');
    }

    /**
     * Toggle the processed status of a job application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobApplication  $jobApplication
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleProcessed(Request $request, JobApplication $jobApplication)
    {
        $jobApplication->update([
            'is_processed' => !$jobApplication->is_processed,
        ]);

        return response()->json([
            'success' => true,
            'is_processed' => $jobApplication->is_processed,
        ]);
    }
}

