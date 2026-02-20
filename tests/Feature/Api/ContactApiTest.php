<?php

use App\Models\ContactForm;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
});

test('api contact index returns 200 with data', function () {
    $response = $this->getJson(route('api.contact.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data']);
});

test('api contact demo submit succeeds with valid data', function () {
    $payload = [
        'first_name' => 'Demo',
        'last_name' => 'User',
        'email' => 'demo@example.com',
        'phone' => '0612345678',
    ];

    $response = $this->postJson(route('api.contact.demo.store'), $payload);

    $response->assertStatus(201);
    $response->assertJson(['success' => true]);
    $response->assertJsonPath('data.full_name', 'Demo User');
    expect(Subscription::count())->toBe(1);
});

test('api contact demo submit fails when required fields missing', function () {
    $response = $this->postJson(route('api.contact.demo.store'), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['first_name', 'last_name', 'email']);
});

test('api contact demo submit fails with invalid email', function () {
    $response = $this->postJson(route('api.contact.demo.store'), [
        'first_name' => 'A',
        'last_name' => 'B',
        'email' => 'not-an-email',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});

test('api contact submit succeeds with valid data', function () {
    $payload = [
        'first_name' => 'Jan',
        'last_name' => 'Jansen',
        'company_name' => '',
        'email' => 'jan@example.com',
        'phone' => '0612345678',
        'reden' => 'vraag',
        'bericht' => 'Ik heb een vraag over jullie product.',
        'avg-optin' => '1',
        'contact_preference' => 'query',
    ];

    $response = $this->postJson(route('api.contact.submit'), $payload);

    $response->assertStatus(201);
    $response->assertJson(['success' => true]);
    expect(ContactForm::count())->toBe(1);
    $contact = ContactForm::first();
    expect($contact->email)->toBe('jan@example.com');
});

test('api contact submit fails when required fields missing', function () {
    $response = $this->postJson(route('api.contact.submit'), []);

    $response->assertStatus(422);
    $response->assertJson(['success' => false]);
    $response->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'phone', 'reden', 'bericht', 'avg-optin', 'contact_preference']);
});

test('api contact submit fails with invalid contact_preference', function () {
    $response = $this->postJson(route('api.contact.submit'), [
        'first_name' => 'Jan',
        'last_name' => 'Jansen',
        'email' => 'jan@example.com',
        'phone' => '0612345678',
        'reden' => 'vraag',
        'bericht' => 'Message',
        'avg-optin' => '1',
        'contact_preference' => 'invalid',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['contact_preference']);
});
