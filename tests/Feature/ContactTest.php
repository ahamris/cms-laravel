<?php

use App\Events\ContactFormSubmitted;
use App\Models\ContactForm;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    Event::fake([ContactFormSubmitted::class]);
});

test('contact form submit fails when required fields are missing', function () {
    $response = $this->postJson(route('contact.submit'), []);

    $response->assertStatus(422);
    $response->assertJson(['success' => false]);
    $response->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'phone', 'reden', 'bericht', 'avg-optin', 'contact_preference']);
});

test('contact form submit succeeds with valid data', function () {
    $response = $this->postJson(route('contact.submit'), [
        'first_name' => 'Jan',
        'last_name' => 'Jansen',
        'company_name' => '',
        'email' => 'jan@example.com',
        'phone' => '0612345678',
        'reden' => 'vraag',
        'bericht' => 'Ik heb een vraag over jullie product.',
        'avg-optin' => '1',
        'contact_preference' => 'query',
    ]);

    $response->assertStatus(201);
    $response->assertJson(['success' => true]);
    expect(ContactForm::count())->toBe(1);
    $contact = ContactForm::first();
    expect($contact->email)->toBe('jan@example.com');
    expect($contact->first_name)->toBe('Jan');
});
