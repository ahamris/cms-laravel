<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Mail\ContactFormReplyMail;
use App\Models\ContactForm;
use App\Models\ContactFormMessage;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactFormController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contactForms = ContactForm::latest()->paginate(20);
        return view('admin.contact-forms.index', compact('contactForms'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactForm $contactForm)
    {
        $contactForm->load([
            'messages' => function ($query) {
                $query->orderBy('created_at', 'asc');
            },
            'messages.user'
        ]);

        return view('admin.contact-forms.show', compact('contactForm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactForm $contactForm)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,resolved,closed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $contactForm->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.administrator.contact-forms.show', $contactForm)
            ->with('success', 'Contact form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactForm $contactForm)
    {
        $contactForm->delete();

        return redirect()->route('admin.administrator.contact-forms.index')
            ->with('success', 'Contact form deleted successfully.');
    }

    /**
     * Send a reply to the contact form submitter.
     */
    public function reply(Request $request, ContactForm $contactForm)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Ensure mail settings from database are applied
            MailSetting::updateMailConfigForTesting();

            // Send the email
            Mail::to($contactForm->email)->send(
                new ContactFormReplyMail($contactForm, $request->subject, $request->message)
            );

            // Save the message to the database
            ContactFormMessage::create([
                'contact_form_id' => $contactForm->id,
                'user_id' => auth()->id(),
                'direction' => 'outbound',
                'subject' => $request->subject,
                'message' => $request->message,
                'sent_at' => now(),
                'status' => 'sent',
            ]);

            // Update contact form status to 'contacted' if it's still 'new'
            if ($contactForm->status === 'new') {
                $contactForm->update(['status' => 'contacted']);
            }

            return redirect()->route('admin.administrator.contact-forms.show', $contactForm)
                ->with('success', 'Reply sent successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to send contact form reply', [
                'contact_form_id' => $contactForm->id,
                'error' => $e->getMessage(),
            ]);

            // Save the failed message attempt
            ContactFormMessage::create([
                'contact_form_id' => $contactForm->id,
                'user_id' => auth()->id(),
                'direction' => 'outbound',
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->route('admin.administrator.contact-forms.show', $contactForm)
                ->with('error', 'Failed to send reply: ' . $e->getMessage());
        }
    }
}
