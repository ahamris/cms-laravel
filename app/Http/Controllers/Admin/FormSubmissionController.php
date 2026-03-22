<?php

namespace App\Http\Controllers\Admin;

use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormSubmissionController extends AdminBaseController
{
    public function index(Form $form, Request $request): View
    {
        $query = $form->submissions()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';
            $query->where('data', 'like', $search);
        }

        $submissions = $query->paginate(20);

        return view('admin.forms.submissions.index', compact('form', 'submissions'));
    }

    public function show(Form $form, FormSubmission $submission): View
    {
        $form->load('fields');

        return view('admin.forms.submissions.show', compact('form', 'submission'));
    }

    public function update(Request $request, Form $form, FormSubmission $submission)
    {
        $validated = $request->validate([
            'status'      => 'nullable|string|in:new,read,processed,spam,archived',
            'admin_notes' => 'nullable|string',
        ]);

        $submission->update($validated);

        if ($validated['status'] === 'processed' && !$submission->processed_at) {
            $submission->update(['processed_at' => now()]);
        }

        return back()->with('success', 'Submission updated.');
    }

    public function convert(Request $request, Form $form, FormSubmission $submission)
    {
        $service = app(\App\Services\FormSubmissionService::class);
        $result = $service->convertToCrm($submission);

        return back()->with('success', 'Converted to CRM successfully.');
    }

    public function export(Form $form)
    {
        $submissions = $form->submissions()->latest()->get();
        $form->load('fields');

        $headers = ['ID', 'Date', 'Status', 'Lead Score'];
        foreach ($form->fields as $field) {
            if (!in_array($field->type, ['heading', 'divider'])) {
                $headers[] = $field->label;
            }
        }

        $callback = function () use ($submissions, $form, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($submissions as $sub) {
                $row = [$sub->id, $sub->created_at->format('Y-m-d H:i'), $sub->status, $sub->lead_score];
                foreach ($form->fields as $field) {
                    if (!in_array($field->type, ['heading', 'divider'])) {
                        $row[] = $sub->data[$field->name] ?? '';
                    }
                }
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, "{$form->slug}-submissions.csv", [
            'Content-Type' => 'text/csv',
        ]);
    }
}
