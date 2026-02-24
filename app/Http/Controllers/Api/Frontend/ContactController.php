<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SeoSetTrait;
use App\Mail\ContactFormSubmittedMail;
use App\Models\ContactForm;
use App\Models\ContactSubject;
use App\Models\Page;
use App\Models\Faq;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use OpenApi\Attributes as OA;

class ContactController extends Controller
{
    use SeoSetTrait;

    #[OA\Get(path: '/api/contact', summary: 'Contact page data', description: 'Contact page content and subject options (Onderwerp dropdown) for headless frontend.', tags: ['Contact'], responses: [
        new OA\Response(response: 200, description: 'Contact page data with optional subjects', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', ref: '#/components/schemas/ContactPageData')])),
    ])]
    public function index(): JsonResponse
    {
        $page = Page::where('slug', 'contact')->where('is_active', true)->first();

        if (! $page) {
            $page = (object) [
                'title' => 'Hulp nodig bij de uitvoering van de Wet open overheid?',
                'short_body' => 'Laat hier je gegevens achter. Een van onze Woo-specialisten denkt graag met je mee.',
                'long_body' => '',
                'image' => null,
            ];
        } else {
            $page = [
                'id' => $page->id,
                'title' => $page->title,
                'short_body' => $page->short_body,
                'long_body' => $page->long_body,
                'image' => $page->image,
                'image_url' => get_image($page->image, null),
                'meta_title' => $page->meta_title,
                'meta_body' => $page->meta_body,
            ];
        }

        $data = is_array($page) ? $page : (array) $page;
        $data['subjects'] = ContactSubject::getCached()->map(fn ($s) => [
            'id' => $s->id,
            'title' => $s->title,
            'sort_order' => $s->sort_order,
        ])->values()->all();

        return response()->json([
            'template' => 'contact',
            'data' => $data, 
            'faqs' => Faq::getByIdentifier('contact') ?: null,
            'subjects' => ContactSubject::getCached()->map(fn ($s) => [
                'id' => $s->id,
                'title' => $s->title,
                'sort_order' => $s->sort_order,
            ])->values()->all(),
        ]);
    }

    #[OA\Get(path: '/api/contact/subjects', summary: 'Contact form subjects', description: 'List of active subject options (Onderwerp) for the contact form dropdown. Managed in admin under CRM → Subjects.', tags: ['Contact'], responses: [
        new OA\Response(response: 200, description: 'Subject options for dropdown', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/ContactSubjectListItem')),
        ])),
    ])]
    public function subjects(): JsonResponse
    {
        $subjects = ContactSubject::getCached()->map(fn ($s) => [
            'id' => $s->id,
            'title' => $s->title,
            'sort_order' => $s->sort_order,
        ])->values()->all();

        return response()->json(['data' => $subjects]);
    }

    #[OA\Post(path: '/api/contact/verstuur', summary: 'Submit contact form', description: 'Submit as form fields (application/x-www-form-urlencoded or multipart/form-data for file attachment). Accepts Dutch names: voornaam/achternaam, onderwerp, organisatie.', tags: ['Contact'], requestBody: new OA\RequestBody(required: true, content: new OA\MediaType(mediaType: 'application/x-www-form-urlencoded', schema: new OA\Schema(required: ['first_name', 'last_name', 'email', 'reden', 'bericht', 'avg-optin'], type: 'object', properties: [
        new OA\Property(property: 'voornaam', type: 'string', maxLength: 255, description: 'Voornaam (or first_name)'),
        new OA\Property(property: 'first_name', type: 'string', maxLength: 255, description: 'First name'),
        new OA\Property(property: 'achternaam', type: 'string', maxLength: 255, description: 'Achternaam (or last_name)'),
        new OA\Property(property: 'last_name', type: 'string', maxLength: 255, description: 'Last name'),
        new OA\Property(property: 'email', type: 'string', format: 'email', maxLength: 255, description: 'E-mailadres'),
        new OA\Property(property: 'phone', type: 'string', maxLength: 50, description: 'Phone (optional)'),
        new OA\Property(property: 'onderwerp', type: 'string', maxLength: 255, description: 'Onderwerp (or reden)'),
        new OA\Property(property: 'reden', type: 'string', maxLength: 255, description: 'Subject/reason'),
        new OA\Property(property: 'organisatie', type: 'string', maxLength: 255, description: 'Organisatie (optional, or company_name)'),
        new OA\Property(property: 'company_name', type: 'string', maxLength: 255, description: 'Company name (optional)'),
        new OA\Property(property: 'bericht', type: 'string', description: 'Bericht (message)'),
        new OA\Property(property: 'avg-optin', type: 'string', description: 'Privacy: 1 or true when agreed'),
        new OA\Property(property: 'contact_preference', type: 'string', enum: ['call', 'query'], description: 'Preferred contact (default: query)'),
        new OA\Property(property: 'country_code', type: 'string', maxLength: 10, description: 'Optional'),
        new OA\Property(property: 'bijlage', type: 'array', items: new OA\Items(type: 'string', format: 'binary'), description: 'One or more file attachments (required when reden=ondersteuning; use multipart/form-data with bijlage[] for multiple). Max 10MB each; pdf, jpg, png, doc, docx, xls, xlsx, ppt, pptx, txt.'),
        new OA\Property(property: 'nieuwsbrief', type: 'string', description: 'Newsletter opt-in (optional, not stored)'),
    ]))), responses: [
        new OA\Response(response: 201, description: 'Contact form submitted'),
        new OA\Response(response: 422, description: 'Validation error'),
        new OA\Response(response: 500, description: 'Server error'),
    ])]
    public function storeContact(Request $request): JsonResponse
    {
        // Normalize Dutch form field names to API names (React can send either)
        $request->merge([
            'first_name' => $request->input('first_name') ?? $request->input('voornaam'),
            'last_name' => $request->input('last_name') ?? $request->input('achternaam'),
            'reden' => $request->input('reden') ?? $request->input('onderwerp'),
            'company_name' => $request->input('company_name') ?? $request->input('organisatie'),
            'contact_preference' => $request->input('contact_preference') ?? 'query',
        ]);

        $fileRules = ['file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,txt,doc,docx,xls,xlsx,ppt,pptx'];
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country_code' => 'nullable|string|max:10',
            'reden' => 'required|string|max:255',
            'bericht' => 'required|string',
            'avg-optin' => 'required',
            'contact_preference' => 'required|in:call,query',
        ];
        $requireAttachment = $request->input('reden') === 'ondersteuning';
        if ($request->hasFile('bijlage')) {
            $files = $request->file('bijlage');
            if (is_array($files)) {
                $rules['bijlage'] = $requireAttachment ? ['required', 'array', 'min:1'] : ['nullable', 'array'];
                $rules['bijlage.*'] = $fileRules;
            } else {
                $rules['bijlage'] = $requireAttachment ? array_merge(['required'], $fileRules) : array_merge(['nullable'], $fileRules);
            }
        } elseif ($requireAttachment) {
            $rules['bijlage'] = 'required';
        }

        try {
            $validated = $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        try {
            $files = $request->file('bijlage');
            if ($files && ! is_array($files)) {
                $files = [$files];
            }
            $files = $files ?: [];

            $bijlage = [];
            foreach ($files as $file) {
                if (! $file || ! $file->isValid()) {
                    continue;
                }
                $name = $file->getClientOriginalName();
                $path = $file->storeAs('contact-forms', $name, 'public');
                if ($path) {
                    $bijlage[] = ['path' => $path, 'name' => $name];
                }
            }

            $contactForm = ContactForm::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? '',
                'company_name' => $validated['company_name'] ?? null,
                'country_code' => $validated['country_code'] ?? null,
                'reden' => $validated['reden'],
                'bericht' => $validated['bericht'],
                'bijlage' => $bijlage ?: null,
                'contact_preference' => $validated['contact_preference'],
                'avg_optin' => in_array($validated['avg-optin'], [true, '1', 1, 'true', 'on'], true),
                'status' => 'new',
            ]);

            try {
                Mail::to($contactForm->email)->send(new ContactFormSubmittedMail($contactForm, false));
            } catch (Exception $e) {
            }
            try {
                $adminEmail = config('mail.admin_email', 'admin@openpublication.eu');
                Mail::to($adminEmail)->send(new ContactFormSubmittedMail($contactForm, true));
            } catch (Exception $e) {
            }

            return response()->json([
                'success' => true,
                'message' => $validated['contact_preference'] === 'call'
                    ? 'We will call you back shortly!'
                    : 'We will respond to your query within one business day!',
                'data' => ['id' => $contactForm->id, 'full_name' => $contactForm->full_name],
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit request. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
