<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\ContactForm;
use App\Models\ContactFormMessage;
use App\Models\CrmDeal;
use App\Models\CrmTicket;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CrmMessageController extends AdminBaseController
{
    public function index(Request $request): View
    {
        $query = ContactForm::with('messages')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', $search)
                  ->orWhere('last_name', 'like', $search)
                  ->orWhere('email', 'like', $search)
                  ->orWhere('bericht', 'like', $search);
            });
        }

        $messages = $query->paginate(20);

        return view('admin.crm.messages.index', compact('messages'));
    }

    public function show(ContactForm $message): View
    {
        $message->load('messages');

        return view('admin.crm.messages.show', compact('message'));
    }

    public function destroy(ContactForm $message)
    {
        $message->delete();

        return redirect()->route('admin.crm.messages.index')
            ->with('success', 'Message deleted.');
    }

    public function reply(Request $request, ContactForm $message)
    {
        $request->validate(['body' => 'required|string']);

        $message->messages()->create([
            'body'      => $request->input('body'),
            'direction' => 'outbound',
        ]);

        $message->update(['status' => 'replied']);

        return back()->with('success', 'Reply sent.');
    }

    public function aiReply(ContactForm $message)
    {
        try {
            $aiService = app(\App\Services\AIService::class);
            $draft = $aiService->draftReply($message->bericht, 'professional', 'nl');

            return response()->json(['draft' => $draft]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'AI service unavailable.'], 503);
        }
    }

    public function resolve(ContactForm $message)
    {
        $message->update(['status' => 'resolved']);

        return back()->with('success', 'Message resolved.');
    }

    public function convertToTicket(ContactForm $message)
    {
        $ticket = CrmTicket::create([
            'contact_id'  => $message->converted_contact_id,
            'subject'     => "Message from {$message->first_name} {$message->last_name}",
            'description' => $message->bericht,
            'source'      => 'form',
            'priority'    => 'medium',
        ]);

        return redirect()->route('admin.crm.tickets.show', $ticket)
            ->with('success', 'Converted to ticket.');
    }

    public function convertToDeal(ContactForm $message)
    {
        if (!$message->converted_contact_id) {
            return back()->with('error', 'Convert to contact first.');
        }

        $deal = CrmDeal::create([
            'contact_id' => $message->converted_contact_id,
            'title'      => "Lead from {$message->first_name} {$message->last_name}",
            'stage'      => 'lead',
            'value'      => 0,
        ]);

        $message->update(['converted_deal_id' => $deal->id]);

        return redirect()->route('admin.crm.deals.show', $deal)
            ->with('success', 'Converted to deal.');
    }
}
