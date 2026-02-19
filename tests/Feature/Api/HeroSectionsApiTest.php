<?php

test('GET api hero-sections returns 200', function () {
    $response = $this->getJson(route('api.hero-sections.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['success', 'data', 'count']);
});

test('GET api blog-posts returns 200', function () {
    $response = $this->getJson(route('api.blog-posts'));

    $response->assertStatus(200);
});
