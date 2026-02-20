<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SeoSetTrait;
use App\Mail\ContactFormSubmittedMail;
use App\Models\ContactForm;
use App\Models\Page;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use OpenApi\Attributes as OA;

class ContactController extends Controller
{
    use SeoSetTrait;

    #[OA\Get(path: '/api/contact', summary: 'Contact page data', description: 'Contact page content for headless frontend.', tags: ['Contact'], responses: [
        new OA\Response(response: 200, description: 'Contact page data', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', ref: '#/components/schemas/ContactPageData')])),
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

        return response()->json(['data' => $page]);
    }

    #[OA\Post(path: '/api/contact/verstuur', summary: 'Submit contact form', tags: ['Contact'], responses: [
        new OA\Response(response: 201, description: 'Contact form submitted'),
        new OA\Response(response: 422, description: 'Validation error'),
        new OA\Response(response: 500, description: 'Server error'),
    ])]
    public function storeContact(Request $request): JsonResponse
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'country_code' => 'nullable|string|max:10',
            'reden' => 'required|string|max:255',
            'bericht' => 'required|string',
            'avg-optin' => 'required',
            'contact_preference' => 'required|in:call,query',
        ];
        if ($request->input('reden') === 'ondersteuning') {
            $rules['bijlage'] = 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,txt,doc,docx,xls,xlsx,ppt,pptx';
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
            $bijlagePath = null;
            if ($request->hasFile('bijlage')) {
                $bijlagePath = $request->file('bijlage')->storeAs('contact-forms', $request->file('bijlage')->getClientOriginalName(), 'public');
            }

            $contactForm = ContactForm::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'company_name' => $validated['company_name'],
                'country_code' => $validated['country_code'] ?? null,
                'reden' => $validated['reden'],
                'bericht' => $validated['bericht'],
                'bijlage' => $bijlagePath,
                'contact_preference' => $validated['contact_preference'],
                'avg_optin' => $validated['avg-optin'] === '1',
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
