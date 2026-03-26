<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Contact;
use App\Models\CrmTicket;
use App\Models\User;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CrmTicketController extends AdminBaseController
{
    public function index(Request $request): View
    {
        $query = CrmTicket::with(['contact', 'assignedTo']);

        if ($request->filled('status')) {
            $query->byStatus($request->input('status'));
        }

        if ($request->filled('priority')) {
            $query->byPriority($request->input('priority'));
        }

        $tickets = $query->latest()->paginate(20);

        return view('admin.crm.tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        $contacts = Contact::orderBy('organization_name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.crm.tickets.create', compact('contacts', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'nullable|exists:contacts,id',
            'assigned_to' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:300',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'source' => 'nullable|in:form,email,phone,chat',
        ]);

        $ticket = CrmTicket::create($validated);

        return redirect()->route('admin.crm.tickets.show', $ticket)
            ->with('success', 'Ticket created.');
    }

    public function show(CrmTicket $ticket): View
    {
        $ticket->load(['contact', 'assignedTo', 'replies.user', 'notes.user']);

        return view('admin.crm.tickets.show', compact('ticket'));
    }

    public function edit(CrmTicket $ticket): View
    {
        $contacts = Contact::orderBy('organization_name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.crm.tickets.edit', compact('ticket', 'contacts', 'users'));
    }

    public function update(Request $request, CrmTicket $ticket)
    {
        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:300',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,waiting,resolved,closed',
        ]);

        $ticket->update($validated);

        return redirect()->route('admin.crm.tickets.show', $ticket)
            ->with('success', 'Ticket updated.');
    }

    public function destroy(CrmTicket $ticket)
    {
        $ticket->delete();

        return redirect()->route('admin.crm.tickets.index')
            ->with('success', 'Ticket deleted.');
    }

    public function reply(Request $request, CrmTicket $ticket)
    {
        $request->validate(['body' => 'required|string']);

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'direction' => 'outbound',
            'body' => $request->input('body'),
        ]);

        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Reply added.');
    }

    public function aiReply(CrmTicket $ticket)
    {
        try {
            $aiService = app(AIService::class);
            $context = $ticket->description."\n\n".$ticket->replies->pluck('body')->implode("\n---\n");
            $assist = $aiService->crmStructuredAssist(
                $context,
                $ticket->contact_id ? (int) $ticket->contact_id : null,
                'professional',
                'nl'
            );

            if (! $assist['success']) {
                return response()->json(['error' => $assist['error'] ?? 'AI service unavailable.'], 503);
            }

            return response()->json([
                'draft' => $assist['draft'] ?? '',
                'summary' => $assist['summary'] ?? '',
                'suggested_status' => $assist['suggested_status'] ?? '',
                'risk_flags' => $assist['risk_flags'] ?? [],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'AI service unavailable.'], 503);
        }
    }

    public function changeStatus(Request $request, CrmTicket $ticket)
    {
        $request->validate(['status' => 'required|in:open,in_progress,waiting,resolved,closed']);

        $ticket->update(['status' => $request->input('status')]);

        if ($request->input('status') === 'resolved') {
            $ticket->update(['resolved_at' => now()]);
        }

        return back()->with('success', 'Status updated.');
    }

    public function assign(Request $request, CrmTicket $ticket)
    {
        $request->validate(['assigned_to' => 'required|exists:users,id']);

        $ticket->update(['assigned_to' => $request->input('assigned_to')]);

        return back()->with('success', 'Ticket assigned.');
    }
}
