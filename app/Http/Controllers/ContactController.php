<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Http\Requests\SubscriptionRequest;
use App\Mail\ContactFormSubmittedMail;
use App\Mail\DemoRequestSubmitted;
use App\Models\ContactForm;
use App\Models\Page;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    use SeoSetTrait;

    /**
     * Display the contact page.
     */
    public function index(): View
    {
        // Get the contact page content from the database
        $page = Page::where('slug', 'contact')
            ->where('is_active', true)
            ->first();

        // Set SEO tags for contact page
        $this->setSeoTags([
            'google_title' => $page ? ($page->meta_title ?: $page->title) : 'Contact - '.get_setting('site_name'),
            'google_description' => $page ? ($page->meta_body ?: $page->short_body) : 'Neem contact met ons op voor vragen of een demo.',
            'google_image' => get_image($page->image ?? null, asset('images/contact-og-image.jpg')),
        ]);

        // If no page found, use default values
        if (! $page) {
            $page = (object) [
                'title' => 'Hulp nodig bij de uitvoering <br> van de Wet open overheid?',
                'short_body' => 'Laat hier je gegevens achter. <br> Een van onze Woo-specialisten denkt graag met je mee.',
                'long_body' => '',
                'image' => null,
            ];
        }

        return view('front.contact.index', compact('page'));
    }

    /**
     * Store a demo application.
     */
    public function storeDemo(SubscriptionRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            // Map form fields to model fields
            $data['first_name'] = $data['firstName'] ?? $data['first_name'] ?? '';
            $data['last_name'] = $data['lastName'] ?? $data['last_name'] ?? '';
            $data['topic'] = $data['topic'] ?? '';

            // Combine date and time if provided
            if (! empty($data['scheduled_date']) && ! empty($data['scheduled_time'])) {
                $data['preferred_demo_date'] = $data['scheduled_date'];
                $data['preferred_demo_time'] = $data['scheduled_time'];
                $data['demo_scheduled_at'] = $data['scheduled_date'].' '.$data['scheduled_time'];
                $data['status'] = 'demo_scheduled';
            }

            // Set source tracking
            $data['source'] = 'website';
            $data['preferred_contact_method'] = 'phone';

            // Create the demo application
            $subscription = Subscription::create($data);

            // Send email to customer
            try {
                Mail::to($subscription->email)
                    ->send(new DemoRequestSubmitted($subscription, false));
            } catch (Exception $e) {
            }

            // Send email to admin
            try {
                $adminEmail = config('mail.admin_email', 'admin@openpublication.eu');
                Mail::to($adminEmail)
                    ->send(new DemoRequestSubmitted($subscription, true));
            } catch (Exception $e) {
            }

            return response()->json([
                'success' => true,
                'message' => 'Demo request submitted successfully!',
                'data' => [
                    'id' => $subscription->id,
                    'full_name' => $subscription->full_name,
                    'scheduled_date' => $subscription->preferred_demo_date?->format('F j, Y'),
                    'scheduled_time' => $subscription->preferred_demo_time,
                ],
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit demo request. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a contact form submission.
     */
    public function storeContact(Request $request): JsonResponse
    {
        // Conditional validation for file upload
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

        // If reden is 'ondersteuning', make bijlage required
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
            // Handle file upload
            $bijlagePath = null;
            if ($request->hasFile('bijlage')) {
                $file = $request->file('bijlage');
                $originalName = $file->getClientOriginalName();
                $bijlagePath = $file->storeAs('contact-forms', $originalName, 'public');
            }

            // Create contact form entry
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

            // Send email to customer
            try {
                Mail::to($contactForm->email)
                    ->send(new ContactFormSubmittedMail($contactForm, false));
            } catch (Exception $e) {
            }

            // Send email to admin
            try {
                $adminEmail = config('mail.admin_email', 'admin@openpublication.eu');
                Mail::to($adminEmail)
                    ->send(new ContactFormSubmittedMail($contactForm, true));
            } catch (Exception $e) {
            }

            return response()->json([
                'success' => true,
                'message' => $validated['contact_preference'] === 'call'
                    ? 'We will call you back shortly!'
                    : 'We will respond to your query within one business day!',
                'data' => [
                    'id' => $contactForm->id,
                    'full_name' => $contactForm->full_name,
                ],
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
