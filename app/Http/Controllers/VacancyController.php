<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\VacancyModule\JobApplication;
use App\Models\VacancyModule\Vacancy;
use App\Notifications\NewJobApplicationNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class VacancyController extends Controller
{
    use SeoSetTrait;


    public function index(Request $request): View
    {
        $this->setSeoTags([
            'google_title' => 'Vacatures - OpenPublicatie',
            'google_description' => 'Ontdek carrièremogelijkheden bij OpenPublicatie. Sluit je aan bij ons team en werk mee aan innovatieve oplossingen.',
            'google_keywords' => 'vacatures,banen,carrière,werk,openpublicatie vacatures,openstaande posities',
        ]);

        $search = $request->get('search', '');
        $filterType = $request->get('filterType', '');
        $filterLocation = $request->get('filterLocation', '');
        $filterDepartment = $request->get('filterDepartment', '');
        $filterCategory = $request->get('filterCategory', '');

        $vacancies = Vacancy::active()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%')
                      ->orWhere('department', 'like', '%' . $search . '%');
                });
            })
            ->when($filterType, function ($query) use ($filterType) {
                $query->where('type', $filterType);
            })
            ->when($filterLocation, function ($query) use ($filterLocation) {
                $query->where('location', 'like', '%' . $filterLocation . '%');
            })
            ->when($filterDepartment, function ($query) use ($filterDepartment) {
                $query->where('department', $filterDepartment);
            })
            ->when($filterCategory, function ($query) use ($filterCategory) {
                $query->where('category', $filterCategory);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Get unique values for filters
        $departments = Vacancy::active()->whereNotNull('department')->distinct()->pluck('department');
        $locations = Vacancy::active()->whereNotNull('location')->distinct()->pluck('location');
        $categories = Vacancy::active()->whereNotNull('category')->distinct()->pluck('category');
        $totalCount = Vacancy::active()->count();

        return view('front.vacancy.index', [
            'vacancies' => $vacancies,
            'search' => $search,
            'filterType' => $filterType,
            'filterLocation' => $filterLocation,
            'filterDepartment' => $filterDepartment,
            'filterCategory' => $filterCategory,
            'departments' => $departments,
            'locations' => $locations,
            'categories' => $categories,
            'totalCount' => $totalCount,
        ]);
    }

    public function show(Vacancy $vacancy): View
    {
        $this->setSeoTags([
            'google_title' => $vacancy->title . ' - Vacatures - OpenPublicatie',
            'google_description' => substr(strip_tags($vacancy->description), 0, 160),
            'google_keywords' => 'vacatures,banen,' . strtolower($vacancy->type) . ',' . strtolower($vacancy->location),
        ]);

        $relatedVacancies = Vacancy::active()
            ->where('id', '!=', $vacancy->id)
            ->limit(6)
            ->get();

        return view('front.vacancy.show', [
            'vacancy' => $vacancy,
            'relatedVacancies' => $relatedVacancies,
        ]);
    }

    public function apply(Vacancy $vacancy): View
    {
        $this->setSeoTags([
            'google_title' => 'Solliciteren - ' . $vacancy->title . ' - OpenPublicatie',
            'google_description' => 'Dien je sollicitatie in voor de functie ' . $vacancy->title . '.',
            'google_keywords' => 'sollicitatie,solliciteren,vacatures,' . strtolower($vacancy->title),
        ]);

        return view('front.vacancy.apply', [
            'vacancy' => $vacancy,
        ]);
    }

    public function submit(Request $request, Vacancy $vacancy): RedirectResponse
    {
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

            // Send notification to admin
            $adminEmail = config('mail.from.address');
            Notification::route('mail', $adminEmail)
                ->notify(new NewJobApplicationNotification($application));

            return redirect()->route('career.index')->with('success', 'Je sollicitatie is succesvol verzonden!');

        } catch (ValidationException $e) {
            return redirect()->route('career.apply', $vacancy)
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('career.apply', $vacancy)
                ->with('error', 'Er is iets misgegaan bij het versturen van je sollicitatie. Probeer het later opnieuw.')
                ->withInput();
        }
    }
}

