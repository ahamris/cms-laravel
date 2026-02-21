<?php

test('the application returns a response', function () {
    $response = $this->get('/');

    $response->assertRedirect('/api/documentation');
});
