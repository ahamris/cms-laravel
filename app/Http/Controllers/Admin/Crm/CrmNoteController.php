<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\CrmNote;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CrmNoteController extends AdminBaseController
{
    public function index(Request $request): View
    {
        $query = CrmNote::with(['user', 'contact', 'deal', 'ticket']);

        if ($request->filled('contact_id')) {
            $query->forContact($request->input('contact_id'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $notes = $query->orderByDesc('is_pinned')->latest()->paginate(20);

        return view('admin.crm.notes.index', compact('notes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'nullable|exists:contacts,id',
            'deal_id'    => 'nullable|exists:crm_deals,id',
            'ticket_id'  => 'nullable|exists:crm_tickets,id',
            'body'       => 'required|string',
            'type'       => 'nullable|in:note,call_log,email_log,meeting_log',
        ]);

        $validated['user_id'] = auth()->id();

        CrmNote::create($validated);

        return back()->with('success', 'Note added.');
    }

    public function update(Request $request, CrmNote $note)
    {
        $validated = $request->validate([
            'body' => 'required|string',
            'type' => 'nullable|in:note,call_log,email_log,meeting_log',
        ]);

        $note->update($validated);

        return back()->with('success', 'Note updated.');
    }

    public function destroy(CrmNote $note)
    {
        $note->delete();

        return back()->with('success', 'Note deleted.');
    }

    public function togglePin(CrmNote $note)
    {
        $note->update(['is_pinned' => !$note->is_pinned]);

        return back()->with('success', $note->is_pinned ? 'Note pinned.' : 'Note unpinned.');
    }

    public function forContact(int $contactId)
    {
        $notes = CrmNote::forContact($contactId)
            ->with('user')
            ->orderByDesc('is_pinned')
            ->latest()
            ->get();

        return response()->json(['data' => $notes]);
    }
}
