<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Services\FormSubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function __construct()
    {
        // Public API - no auth required
    }

    public function show(string $slug): JsonResponse
    {
        $form = Form::where('slug', $slug)
            ->where('is_active', true)
            ->with('fields')
            ->firstOrFail();

        if (!$form->isAcceptingSubmissions()) {
            return response()->json([
                'message' => 'This form is not currently accepting submissions.',
            ], 422);
        }

        return response()->json([
            'data' => [
                'id'              => $form->id,
                'name'            => $form->name,
                'slug'            => $form->slug,
                'description'     => $form->description,
                'type'            => $form->type,
                'success_message' => $form->success_message,
                'redirect_url'    => $form->redirect_url,
                'honeypot_field'  => $form->honeypot_field,
                'styling'         => $form->styling,
                'fields'          => $form->fields->map(fn ($f) => [
                    'name'          => $f->name,
                    'label'         => $f->label,
                    'type'          => $f->type,
                    'placeholder'   => $f->placeholder,
                    'help_text'     => $f->help_text,
                    'is_required'   => $f->is_required,
                    'options'       => $f->options,
                    'default_value' => $f->default_value,
                    'width'         => $f->width,
                    'conditional_on' => $f->conditional_on,
                ]),
            ],
        ]);
    }

    public function submit(string $slug, Request $request, FormSubmissionService $service): JsonResponse
    {
        $form = Form::where('slug', $slug)
            ->where('is_active', true)
            ->with('fields')
            ->firstOrFail();

        if (!$form->isAcceptingSubmissions()) {
            return response()->json([
                'message' => 'This form is not currently accepting submissions.',
            ], 422);
        }

        // Honeypot check
        if ($form->honeypot_field && $request->filled("fields.{$form->honeypot_field}")) {
            return response()->json([
                'message' => $form->success_message ?? 'Thank you for your submission.',
            ]);
        }

        // Validate
        $rules = $form->getValidationRules();
        $request->validate($rules);

        $submission = $service->processSubmission($form, $request);

        return response()->json([
            'message'      => $form->success_message ?? 'Thank you for your submission.',
            'redirect_url' => $form->redirect_url,
            'submission_id' => $submission->id,
        ], 201);
    }
}
