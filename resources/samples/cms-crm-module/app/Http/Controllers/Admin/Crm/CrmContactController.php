<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CRM Contact Controller
 *
 * Manages contacts with funnel stage tracking.
 * Funnel stages map to the existing funnel_fase field:
 *   interesseer = Attract (Visitor)
 *   overtuig    = Convert (Lead)
 *   activeer    = Close   (Customer candidate)
 *   inspireer   = Delight (Promoter)
 */
class CrmContactController extends AdminBaseController
{
    public function index(Request $request): View
    {
        $contacts = Contact::query()
            ->when($request->search, fn($q, $s) =>
                $q->where('organisation_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhereRaw("CONCAT(billing_attention, ' ', '') like ?", ["%{$s}%"])
            )
            ->when($request->type === 'Customer', fn($q) => $q->where('is_customer', true))
            ->when($request->type === 'Supplier', fn($q) => $q->where('is_supplier', true))
            ->when($request->funnel_fase, fn($q, $f) => $q->where('funnel_fase', $f))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.crm.contacts.index', compact('contacts'));
    }

    public function create(): View
    {
        return view('admin.crm.contacts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'nullable|email|max:255',
            'phone'             => 'nullable|string|max:50',
            'organisation_name' => 'nullable|string|max:255',
            'website'           => 'nullable|url|max:255',
            'funnel_fase'       => 'nullable|in:interesseer,overtuig,activeer,inspireer',
            'is_customer'       => 'boolean',
            'is_supplier'       => 'boolean',
            'is_active'         => 'boolean',
            'notes'             => 'nullable|string',
            // Billing
            'invoice_email'     => 'nullable|email|max:255',
            'payment_due_days'  => 'nullable|integer|min:0|max:365',
            'currency'          => 'nullable|string|max:3',
            // Address
            'billing_street'    => 'nullable|string|max:255',
            'billing_city'      => 'nullable|string|max:255',
            'billing_zipcode'   => 'nullable|string|max:20',
            'billing_country'   => 'nullable|string|max:100',
        ]);

        $contact = Contact::create($validated);
        $this->logCreate($contact);

        return redirect()
            ->route('admin.crm.contacts.show', $contact)
            ->with('success', 'Contact created successfully.');
    }

    public function show(Contact $contact): View
    {
        $contact->load([]);

        // Related contact forms (messages) for this email
        $messages = \App\Models\ContactForm::where('email', $contact->email)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.crm.contacts.show', compact('contact', 'messages'));
    }

    public function edit(Contact $contact): View
    {
        return view('admin.crm.contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'nullable|email|max:255',
            'phone'             => 'nullable|string|max:50',
            'organisation_name' => 'nullable|string|max:255',
            'website'           => 'nullable|url|max:255',
            'funnel_fase'       => 'nullable|in:interesseer,overtuig,activeer,inspireer',
            'is_customer'       => 'boolean',
            'is_supplier'       => 'boolean',
            'is_active'         => 'boolean',
            'notes'             => 'nullable|string',
            'invoice_email'     => 'nullable|email|max:255',
            'payment_due_days'  => 'nullable|integer',
            'currency'          => 'nullable|string|max:3',
        ]);

        $contact->update($validated);
        $this->logUpdate($contact);

        return redirect()
            ->route('admin.crm.contacts.show', $contact)
            ->with('success', 'Contact updated.');
    }

    public function destroy(Contact $contact)
    {
        $this->logDelete($contact);
        $contact->delete();

        return redirect()
            ->route('admin.crm.contacts.index')
            ->with('success', 'Contact deleted.');
    }

    /** Toggle active status via AJAX */
    public function toggleActive(Contact $contact)
    {
        $contact->update(['is_active' => !$contact->is_active]);
        return response()->json(['is_active' => $contact->is_active]);
    }

    /** Toggle customer flag */
    public function toggleCustomer(Contact $contact)
    {
        $contact->update(['is_customer' => !$contact->is_customer]);
        return response()->json(['is_customer' => $contact->is_customer]);
    }

    /** Convert to lead: set funnel_fase = overtuig */
    public function convertToLead(Contact $contact)
    {
        $contact->update(['funnel_fase' => 'overtuig']);
        return redirect()->back()->with('success', 'Contact moved to Convert stage.');
    }

    /** Full activity timeline for a contact */
    public function timeline(Contact $contact)
    {
        $messages = \App\Models\ContactForm::where('email', $contact->email)->get();
        return view('admin.crm.contacts.timeline', compact('contact', 'messages'));
    }
}
