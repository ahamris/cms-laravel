<?php

namespace App\Services;

use App\Events\FormSubmitted;
use App\Models\Contact;
use App\Models\CrmDeal;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FormSubmissionService
{
    public function processSubmission(Form $form, Request $request): FormSubmission
    {
        $submission = FormSubmission::create([
            'form_id'      => $form->id,
            'data'         => $request->input('fields', []),
            'files'        => $this->handleFiles($form, $request),
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
            'referrer_url' => $request->header('referer'),
            'utm_source'   => $request->input('utm_source'),
            'utm_medium'   => $request->input('utm_medium'),
            'utm_campaign' => $request->input('utm_campaign'),
            'status'       => 'new',
        ]);

        $this->calculateLeadScore($submission);

        if ($form->crm_auto_contact) {
            $this->autoCreateContact($form, $submission);
        }

        if ($form->crm_auto_deal && $submission->converted_contact_id) {
            $this->autoCreateDeal($form, $submission);
        }

        $this->sendNotifications($form, $submission);
        $this->fireSlackWebhook($form, $submission);

        FormSubmitted::dispatch($submission);

        return $submission;
    }

    public function convertToCrm(FormSubmission $submission): void
    {
        $form = $submission->form()->with('fields')->first();

        if (!$submission->converted_contact_id) {
            $this->autoCreateContact($form, $submission);
        }

        if (!$submission->converted_deal_id && $form->crm_auto_deal) {
            $this->autoCreateDeal($form, $submission);
        }

        $submission->update(['status' => 'processed', 'processed_at' => now()]);
    }

    protected function calculateLeadScore(FormSubmission $submission): void
    {
        $score = 10; // base score for submitting

        $data = $submission->data;
        if (!empty($data['email'])) $score += 10;
        if (!empty($data['phone'])) $score += 15;
        if (!empty($data['company']) || !empty($data['company_name'])) $score += 20;
        if (!empty($data['website'])) $score += 5;

        $submission->update(['lead_score' => min($score, 100)]);
    }

    protected function autoCreateContact(Form $form, FormSubmission $submission): void
    {
        $fieldMap = [];
        foreach ($form->fields as $field) {
            if ($field->crm_map_to) {
                $fieldMap[$field->crm_map_to] = $submission->data[$field->name] ?? null;
            }
        }

        $email = $fieldMap['email'] ?? $submission->data['email'] ?? null;
        if (!$email) return;

        $contact = Contact::where('email', $email)->first();

        if (!$contact) {
            $contact = Contact::create([
                'organization_name' => $fieldMap['company'] ?? $fieldMap['company_name'] ?? $email,
                'email'             => $email,
                'phone'             => $fieldMap['phone'] ?? null,
                'website'           => $fieldMap['website'] ?? null,
                'funnel_fase'       => $form->crm_pipeline ?? 'overtuig',
                'lead_source'       => $form->slug,
                'lead_score'        => $submission->lead_score,
            ]);
        } else {
            $contact->update([
                'lead_score'      => max($contact->lead_score ?? 0, $submission->lead_score),
                'last_activity_at' => now(),
            ]);
        }

        $submission->update(['converted_contact_id' => $contact->id]);
    }

    protected function autoCreateDeal(Form $form, FormSubmission $submission): void
    {
        if (!$submission->converted_contact_id) return;

        $deal = CrmDeal::create([
            'contact_id'  => $submission->converted_contact_id,
            'title'       => "Lead from {$form->name}",
            'description' => "Auto-created from form submission #{$submission->id}",
            'stage'       => 'lead',
            'value'       => $form->crm_deal_value ?? 0,
            'currency'    => 'EUR',
            'funnel_fase' => $form->crm_pipeline ?? 'overtuig',
        ]);

        $submission->update(['converted_deal_id' => $deal->id]);
    }

    protected function sendNotifications(Form $form, FormSubmission $submission): void
    {
        if (!$form->notification_emails) return;

        $emails = array_map('trim', explode(',', $form->notification_emails));
        $emails = array_filter($emails);

        foreach ($emails as $email) {
            try {
                Mail::raw(
                    "New submission on form \"{$form->name}\" (#{$submission->id})\n\n" .
                    collect($submission->data)->map(fn ($v, $k) => "{$k}: {$v}")->implode("\n"),
                    function ($message) use ($email, $form) {
                        $message->to($email)
                            ->subject("New form submission: {$form->name}");
                    }
                );
            } catch (\Exception $e) {
                Log::warning("Failed to send form notification to {$email}: {$e->getMessage()}");
            }
        }
    }

    protected function fireSlackWebhook(Form $form, FormSubmission $submission): void
    {
        if (!$form->notification_slack) return;

        try {
            Http::post($form->notification_slack, [
                'text' => "New submission on *{$form->name}* (#{$submission->id})\nLead Score: {$submission->lead_score}",
            ]);
        } catch (\Exception $e) {
            Log::warning("Failed to send Slack webhook: {$e->getMessage()}");
        }
    }

    protected function handleFiles(Form $form, Request $request): ?array
    {
        $files = [];

        foreach ($form->fields as $field) {
            if ($field->type === 'file' && $request->hasFile("fields.{$field->name}")) {
                $file = $request->file("fields.{$field->name}");
                $path = $file->store("form-uploads/{$form->slug}", 'public');
                $files[$field->name] = $path;
            }
        }

        return !empty($files) ? $files : null;
    }
}
