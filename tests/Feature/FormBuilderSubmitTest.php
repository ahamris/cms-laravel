<?php

use App\Models\FormBuilder;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

test('form builder submit returns 404 when form not found', function () {
    $response = $this->postJson(route('form-builder.submit', ['identifier' => 'non-existent-form']), []);

    $response->assertStatus(404);
    $response->assertJson(['success' => false, 'message' => 'Form not found or inactive.']);
});

test('form builder submit stores submission when form exists and data is valid', function () {
    $form = FormBuilder::create([
        'title' => 'Test Contact Form',
        'identifier' => 'test-contact-form',
        'slug' => 'test-contact-form',
        'fields' => [
            ['name' => 'email', 'type' => 'email', 'required' => true, 'label' => 'Email'],
            ['name' => 'message', 'type' => 'textarea', 'required' => false, 'label' => 'Message'],
        ],
        'is_active' => true,
        'sort_order' => 0,
    ]);

    $response = $this->postJson(route('form-builder.submit', ['identifier' => 'test-contact-form']), [
        'email' => 'submitter@example.com',
        'message' => 'Hello world',
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    expect(FormSubmission::count())->toBe(1);
    $submission = FormSubmission::first();
    expect($submission->form_builder_id)->toBe($form->id);
    expect($submission->data['email'])->toBe('submitter@example.com');
    expect($submission->data['message'])->toBe('Hello world');
});

test('form builder submit returns 422 when required field is missing', function () {
    FormBuilder::create([
        'title' => 'Required Field Form',
        'identifier' => 'required-field-form',
        'slug' => 'required-field-form',
        'fields' => [
            ['name' => 'email', 'type' => 'email', 'required' => true, 'label' => 'Email'],
        ],
        'is_active' => true,
        'sort_order' => 0,
    ]);

    $response = $this->postJson(route('form-builder.submit', ['identifier' => 'required-field-form']), []);

    $response->assertStatus(422);
    $response->assertJson(['success' => false]);
});
