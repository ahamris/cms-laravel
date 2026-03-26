<?php

use App\Ai\Agents\BlogContentWriterAgent;
use App\Ai\Agents\CrmSupportAgent;
use App\Helpers\Variable;
use App\Models\AIServiceSetting;
use App\Models\Contact;
use App\Models\ContactForm;
use App\Models\CrmTicket;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => Variable::ROLE_ADMIN]);
    $this->admin = User::create([
        'name' => 'Admin',
        'last_name' => 'AI',
        'email' => 'admin-ai-test-'.uniqid().'@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $this->admin->assignRole(Variable::ROLE_ADMIN);

    AIServiceSetting::query()->updateOrCreate(
        ['service' => 'groq'],
        [
            'api_key' => 'test-groq-key-for-fake-gateway',
            'model' => 'llama-3.3-70b-versatile',
            'is_active' => true,
            'priority' => 1,
        ]
    );
});

test('admin blog generate-with-ai uses BlogContentWriterAgent structured fake', function () {
    BlogContentWriterAgent::fake([[
        'title' => 'Structured Test Title',
        'short_body' => 'A concise excerpt that meets the short body requirement for the blog post schema.',
        'long_body' => '<h2>Hello</h2><p>Paragraph.</p>',
        'meta_title' => 'Meta Title',
        'meta_description' => 'Meta description for SEO purposes here.',
        'meta_keywords' => 'one, two, three',
    ]]);

    $response = $this->actingAs($this->admin)->postJson(route('admin.blog.generate-with-ai'), [
        'topic' => 'Testing AI SDK',
        'keywords' => 'laravel, ai',
        'tone' => 'professional',
        'length' => 'short',
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.title', 'Structured Test Title')
        ->assertJsonPath('data.long_body', '<h2>Hello</h2><p>Paragraph.</p>');
});

test('crm ticket ai-reply uses CrmSupportAgent structured fake', function () {
    CrmSupportAgent::fake([[
        'summary' => 'Customer asked about shipping.',
        'suggested_reply' => 'Thanks for reaching out. Here is the tracking link.',
        'suggested_status' => 'in_progress',
        'risk_flags' => ['deadline_today'],
    ]]);

    $contact = Contact::create([
        'organization_name' => 'Acme BV',
        'slug' => 'acme-bv-'.uniqid(),
        'email' => 'buyer@example.com',
        'is_active' => true,
    ]);

    $ticket = CrmTicket::create([
        'contact_id' => $contact->id,
        'subject' => 'Where is my order?',
        'description' => 'Still waiting since Monday.',
        'status' => 'open',
        'priority' => 'medium',
        'source' => 'email',
    ]);

    $response = $this->actingAs($this->admin)->postJson(
        route('admin.crm.tickets.ai-reply', $ticket)
    );

    $response->assertOk()
        ->assertJsonPath('draft', 'Thanks for reaching out. Here is the tracking link.')
        ->assertJsonPath('summary', 'Customer asked about shipping.')
        ->assertJsonPath('suggested_status', 'in_progress')
        ->assertJsonPath('risk_flags', ['deadline_today']);
});

test('crm message ai-reply uses CrmSupportAgent structured fake', function () {
    CrmSupportAgent::fake([[
        'summary' => 'Form submission about pricing.',
        'suggested_reply' => 'We will send a quote shortly.',
        'suggested_status' => 'open',
        'risk_flags' => [],
    ]]);

    $contact = Contact::create([
        'organization_name' => 'Widget Inc',
        'slug' => 'widget-inc-'.uniqid(),
        'email' => 'lead@widget.example',
        'is_active' => true,
    ]);

    $message = new ContactForm([
        'first_name' => 'Pat',
        'last_name' => 'Lee',
        'email' => 'lead@widget.example',
        'phone' => '0612345678',
        'reden' => 'Pricing',
        'bericht' => 'What are your enterprise rates?',
        'contact_preference' => 'email',
        'avg_optin' => true,
    ]);
    $message->converted_contact_id = $contact->id;
    $message->save();

    $response = $this->actingAs($this->admin)->postJson(
        route('admin.crm.messages.ai-reply', $message)
    );

    $response->assertOk()
        ->assertJsonPath('draft', 'We will send a quote shortly.')
        ->assertJsonPath('summary', 'Form submission about pricing.');
});
