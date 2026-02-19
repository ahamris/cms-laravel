<?php

test('contact submit fails when required fields are missing', function () {
    $response = $this->postJson(route('contact.submit'), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'phone', 'reden', 'bericht', 'avg-optin', 'contact_preference']);
});
