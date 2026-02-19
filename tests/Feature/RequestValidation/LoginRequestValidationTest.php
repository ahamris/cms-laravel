<?php

test('login fails when email and password are missing', function () {
    $response = $this->post(route('admin.login.post'), []);

    $response->assertSessionHasErrors(['email', 'password']);
});

test('login fails when email format is invalid', function () {
    $response = $this->post(route('admin.login.post'), [
        'email' => 'not-an-email',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors(['email']);
});
