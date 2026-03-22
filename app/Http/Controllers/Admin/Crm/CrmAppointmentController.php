<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Contact;
use App\Models\CrmAppointment;
use App\Models\CrmDeal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CrmAppointmentController extends AdminBaseController
{
    public function index(Request $request): View
    {
        $query = CrmAppointment::with(['contact', 'assignedTo', 'deal']);

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $appointments = $query->orderBy('starts_at', 'desc')->paginate(20);

        $calendarEvents = CrmAppointment::with('contact')
            ->whereDate('starts_at', '>=', now()->startOfMonth())
            ->whereDate('starts_at', '<=', now()->endOfMonth()->addMonth())
            ->get()
            ->map(fn ($a) => [
                'id'     => $a->id,
                'title'  => $a->title,
                'start'  => $a->starts_at->toIso8601String(),
                'end'    => $a->ends_at?->toIso8601String(),
                'type'   => $a->type,
                'status' => $a->status,
            ]);

        return view('admin.crm.appointments.index', compact('appointments', 'calendarEvents'));
    }

    public function create(): View
    {
        $contacts = Contact::orderBy('organization_name')->get();
        $deals = CrmDeal::open()->get();
        $users = User::orderBy('name')->get();

        return view('admin.crm.appointments.create', compact('contacts', 'deals', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id'  => 'nullable|exists:contacts,id',
            'deal_id'     => 'nullable|exists:crm_deals,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title'       => 'required|string|max:200',
            'notes'       => 'nullable|string',
            'type'        => 'required|in:demo,call,follow_up,onboarding,meeting,other',
            'starts_at'   => 'required|date',
            'ends_at'     => 'nullable|date|after:starts_at',
            'location'    => 'nullable|string|max:500',
            'is_online'   => 'nullable|boolean',
        ]);

        $validated['is_online'] = $request->boolean('is_online', true);

        CrmAppointment::create($validated);

        return redirect()->route('admin.crm.appointments.index')
            ->with('success', 'Appointment created.');
    }

    public function show(CrmAppointment $appointment): View
    {
        $appointment->load(['contact', 'deal', 'assignedTo']);

        return view('admin.crm.appointments.show', compact('appointment'));
    }

    public function edit(CrmAppointment $appointment): View
    {
        $contacts = Contact::orderBy('organization_name')->get();
        $deals = CrmDeal::open()->get();
        $users = User::orderBy('name')->get();

        return view('admin.crm.appointments.edit', compact('appointment', 'contacts', 'deals', 'users'));
    }

    public function update(Request $request, CrmAppointment $appointment)
    {
        $validated = $request->validate([
            'contact_id'  => 'nullable|exists:contacts,id',
            'deal_id'     => 'nullable|exists:crm_deals,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title'       => 'required|string|max:200',
            'notes'       => 'nullable|string',
            'type'        => 'required|in:demo,call,follow_up,onboarding,meeting,other',
            'starts_at'   => 'required|date',
            'ends_at'     => 'nullable|date|after:starts_at',
            'status'      => 'required|in:scheduled,completed,cancelled,no_show',
            'location'    => 'nullable|string|max:500',
            'is_online'   => 'nullable|boolean',
        ]);

        $validated['is_online'] = $request->boolean('is_online', true);

        $appointment->update($validated);

        return redirect()->route('admin.crm.appointments.index')
            ->with('success', 'Appointment updated.');
    }

    public function destroy(CrmAppointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.crm.appointments.index')
            ->with('success', 'Appointment deleted.');
    }

    public function complete(CrmAppointment $appointment)
    {
        $appointment->complete();

        return back()->with('success', 'Appointment completed.');
    }
}
