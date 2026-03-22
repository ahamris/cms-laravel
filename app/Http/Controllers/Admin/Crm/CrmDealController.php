<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Contact;
use App\Models\CrmDeal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CrmDealController extends AdminBaseController
{
    public function index(Request $request): View
    {
        $query = CrmDeal::with(['contact', 'assignedTo']);

        if ($request->filled('stage')) {
            $query->byStage($request->input('stage'));
        }

        $deals = $query->latest()->paginate(20);

        // Kanban data
        $kanban = [];
        foreach (['lead', 'qualified', 'proposal', 'negotiation', 'won'] as $stage) {
            $kanban[$stage] = CrmDeal::with('contact')
                ->byStage($stage)
                ->where('is_active', true)
                ->latest()
                ->take(20)
                ->get();
        }

        return view('admin.crm.deals.index', compact('deals', 'kanban'));
    }

    public function create(): View
    {
        $contacts = Contact::orderBy('organization_name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.crm.deals.create', compact('contacts', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id'          => 'required|exists:contacts,id',
            'assigned_to'         => 'nullable|exists:users,id',
            'title'               => 'required|string|max:200',
            'description'         => 'nullable|string',
            'stage'               => 'required|in:lead,qualified,proposal,negotiation,won,lost',
            'value'               => 'nullable|integer|min:0',
            'probability'         => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
        ]);

        CrmDeal::create($validated);

        return redirect()->route('admin.crm.deals.index')
            ->with('success', 'Deal created.');
    }

    public function show(CrmDeal $deal): View
    {
        $deal->load(['contact', 'assignedTo', 'notes.user', 'appointments']);

        return view('admin.crm.deals.show', compact('deal'));
    }

    public function edit(CrmDeal $deal): View
    {
        $contacts = Contact::orderBy('organization_name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.crm.deals.edit', compact('deal', 'contacts', 'users'));
    }

    public function update(Request $request, CrmDeal $deal)
    {
        $validated = $request->validate([
            'contact_id'          => 'required|exists:contacts,id',
            'assigned_to'         => 'nullable|exists:users,id',
            'title'               => 'required|string|max:200',
            'description'         => 'nullable|string',
            'stage'               => 'required|in:lead,qualified,proposal,negotiation,won,lost',
            'value'               => 'nullable|integer|min:0',
            'probability'         => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
        ]);

        $deal->update($validated);

        return redirect()->route('admin.crm.deals.show', $deal)
            ->with('success', 'Deal updated.');
    }

    public function destroy(CrmDeal $deal)
    {
        $deal->delete();

        return redirect()->route('admin.crm.deals.index')
            ->with('success', 'Deal deleted.');
    }

    public function moveStage(Request $request, CrmDeal $deal)
    {
        $request->validate(['stage' => 'required|in:lead,qualified,proposal,negotiation,won,lost']);

        $deal->moveToStage($request->input('stage'));

        return back()->with('success', 'Deal moved to ' . $request->input('stage') . '.');
    }

    public function markWon(CrmDeal $deal)
    {
        $deal->markWon();

        return back()->with('success', 'Deal marked as won.');
    }

    public function markLost(Request $request, CrmDeal $deal)
    {
        $deal->markLost($request->input('lost_reason'));

        return back()->with('success', 'Deal marked as lost.');
    }
}
