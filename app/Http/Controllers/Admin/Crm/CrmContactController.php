<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CrmContactController extends AdminBaseController
{
    public function index(Request $request): View
    {
        $query = Contact::query();

        if ($request->filled('funnel_fase')) {
            $query->where('funnel_fase', $request->input('funnel_fase'));
        }

        if ($request->filled('lifecycle_stage')) {
            $query->where('lifecycle_stage', $request->input('lifecycle_stage'));
        }

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($search) {
                $q->where('organization_name', 'like', $search)
                  ->orWhere('email', 'like', $search)
                  ->orWhere('phone', 'like', $search);
            });
        }

        $contacts = $query->withCount(['deals', 'tickets'])->latest()->paginate(20);

        return view('admin.crm.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact): View
    {
        $contact->load(['deals', 'tickets', 'appointments', 'notes.user', 'formSubmissions.form']);

        $timeline = collect()
            ->merge($contact->deals->map(fn ($d) => ['type' => 'deal', 'date' => $d->created_at, 'data' => $d]))
            ->merge($contact->tickets->map(fn ($t) => ['type' => 'ticket', 'date' => $t->created_at, 'data' => $t]))
            ->merge($contact->appointments->map(fn ($a) => ['type' => 'appointment', 'date' => $a->created_at, 'data' => $a]))
            ->merge($contact->notes->map(fn ($n) => ['type' => 'note', 'date' => $n->created_at, 'data' => $n]))
            ->merge($contact->formSubmissions->map(fn ($s) => ['type' => 'submission', 'date' => $s->created_at, 'data' => $s]))
            ->sortByDesc('date')
            ->values();

        return view('admin.crm.contacts.show', compact('contact', 'timeline'));
    }

    public function create(): View
    {
        return view('admin.crm.contacts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'email'             => 'nullable|email|max:255',
            'phone'             => 'nullable|string|max:50',
            'website'           => 'nullable|url|max:500',
            'funnel_fase'       => 'nullable|in:interesseer,overtuig,activeer,inspireer',
            'lifecycle_stage'   => 'nullable|string|max:30',
            'company_name'      => 'nullable|string|max:200',
            'job_title'         => 'nullable|string|max:200',
            'notes'             => 'nullable|string',
        ]);

        Contact::create($validated);

        return redirect()->route('admin.crm.contacts.index')
            ->with('success', 'Contact created.');
    }

    public function edit(Contact $contact): View
    {
        return view('admin.crm.contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'email'             => 'nullable|email|max:255',
            'phone'             => 'nullable|string|max:50',
            'website'           => 'nullable|url|max:500',
            'funnel_fase'       => 'nullable|in:interesseer,overtuig,activeer,inspireer',
            'lifecycle_stage'   => 'nullable|string|max:30',
            'company_name'      => 'nullable|string|max:200',
            'job_title'         => 'nullable|string|max:200',
            'notes'             => 'nullable|string',
        ]);

        $contact->update($validated);

        return redirect()->route('admin.crm.contacts.show', $contact)
            ->with('success', 'Contact updated.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.crm.contacts.index')
            ->with('success', 'Contact deleted.');
    }

    public function toggleActive(Contact $contact)
    {
        $contact->update(['is_active' => !$contact->is_active]);

        return back()->with('success', 'Contact status updated.');
    }

    public function timeline(Contact $contact)
    {
        $contact->load(['deals', 'tickets', 'appointments', 'notes.user', 'formSubmissions.form']);

        return response()->json([
            'deals'        => $contact->deals,
            'tickets'      => $contact->tickets,
            'appointments' => $contact->appointments,
            'notes'        => $contact->notes,
            'submissions'  => $contact->formSubmissions,
        ]);
    }

    public function updateFunnel(Request $request, Contact $contact)
    {
        $request->validate(['funnel_fase' => 'required|in:interesseer,overtuig,activeer,inspireer']);

        $contact->update(['funnel_fase' => $request->input('funnel_fase')]);

        return back()->with('success', 'Funnel stage updated.');
    }
}
