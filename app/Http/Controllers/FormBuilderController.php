<?php

namespace App\Http\Controllers;

use App\Models\FormBuilder;
use App\Models\FormSubmission;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class FormBuilderController extends Controller
{
    /**
     * Submit a form builder form (uses FormBuilder - same as admin and front display).
     */
    public function submit(Request $request, string $identifier)
    {
        $form = FormBuilder::getByIdentifier($identifier);

        if (!$form || !$form->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Form not found or inactive.'
            ], 404);
        }

        $fields = $form->fields ?? [];
        $rules = [];
        $messages = [];

        foreach ($fields as $field) {
            $fieldName = $field['name'] ?? null;
            if (!$fieldName) continue;

            $rule = [];
            if (isset($field['required']) && $field['required']) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            if (isset($field['type'])) {
                if ($field['type'] === 'email') {
                    $rule[] = 'email';
                } elseif ($field['type'] === 'tel') {
                    $rule[] = 'string';
                } elseif ($field['type'] === 'checkbox') {
                    $rule[] = 'boolean';
                } else {
                    $rule[] = 'string';
                }
            } else {
                $rule[] = 'string';
            }

            $rules[$fieldName] = implode('|', $rule);

            if (isset($field['required']) && $field['required']) {
                $messages[$fieldName . '.required'] = ($field['label'] ?? $fieldName) . ' is required.';
            }
            if (isset($field['type']) && $field['type'] === 'email') {
                $messages[$fieldName . '.email'] = 'Please enter a valid email address.';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($form->is_api_form) {
            return $this->handleApiFormSubmission($request, $form);
        }

        $submission = FormSubmission::create([
            'form_builder_id' => $form->id,
            'data' => $request->except(['_token', '_method']),
            'ip_address' => client_ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($form->send_email_notification && $form->notification_emails) {
            try {
                $emails = array_map('trim', explode(',', $form->notification_emails));
                foreach ($emails as $email) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        Mail::raw($this->formatSubmissionEmail($form, $submission), function ($message) use ($email, $form) {
                            $message->to($email)
                                ->subject('New Form Submission: ' . $form->title);
                        });
                    }
                }
            } catch (\Exception $e) {
                // Log error but don't fail the submission
            }
        }

        $successMessage = $form->success_message ?? 'Thank you for your submission!';
        return response()->json([
            'success' => true,
            'message' => $successMessage
        ]);
    }

    private function handleApiFormSubmission(Request $request, FormBuilder $form)
    {
        $apiUrl = $form->api_url ?? null;
        $apiToken = $form->api_token ?? null;

        if (!$apiUrl || !$apiToken) {
            return response()->json([
                'success' => false,
                'message' => 'API form configuration is incomplete.'
            ], 500);
        }

        $formData = $request->except(['_token', '_method']);
        $payload = array_merge($formData, [
            'form_identifier' => $form->identifier,
            'form_title' => $form->title,
            'submitted_at' => now()->toIso8601String(),
            'ip_address' => client_ip(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'X-API-Token' => $apiToken,
                    'X-Form-Identifier' => $form->identifier,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($apiUrl, $payload);

            \Log::info('API Form Submission', [
                'form_identifier' => $form->identifier,
                'api_url' => $apiUrl,
                'status_code' => $response->status(),
                'response' => $response->body(),
            ]);

            if ($response->successful()) {
                $successMessage = $form->success_message ?? 'Thank you for your submission!';
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'api_response' => $response->json() ?? $response->body()
                ]);
            }

            $errorMessage = $response->json()['message'] ?? $response->json()['error'] ?? 'External API returned an error.';
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'api_status' => $response->status(),
            ], $response->status() >= 500 ? 502 : 422);
        } catch (ConnectionException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to connect to the external service. Please try again later.'
            ], 502);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your submission. Please try again later.'
            ], 500);
        }
    }

    private function formatSubmissionEmail(FormBuilder $form, FormSubmission $submission): string
    {
        $email = "New form submission from: {$form->title}\n\n";
        $email .= "Submitted: " . $submission->created_at->format('Y-m-d H:i:s') . "\n";
        $email .= "IP Address: {$submission->ip_address}\n\n";
        $email .= "Form Data:\n";
        $email .= str_repeat('-', 50) . "\n\n";

        $fields = $form->fields ?? [];
        foreach ($submission->data as $key => $value) {
            $label = $key;
            foreach ($fields as $field) {
                if (($field['name'] ?? '') === $key) {
                    $label = $field['label'] ?? $key;
                    break;
                }
            }
            $email .= "{$label}: " . (is_array($value) ? implode(', ', $value) : $value) . "\n";
        }

        return $email;
    }
}
