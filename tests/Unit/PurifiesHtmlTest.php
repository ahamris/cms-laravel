<?php

use App\Http\Controllers\Admin\AdminBaseController;

/**
 * Tests for PurifiesHtml trait: ensures HTML purification (XSS removal) for form fields.
 * Covers flat keys, nested keys (e.g. items.*.content, faqs.*.answer), and safe HTML preservation.
 */
beforeEach(function () {
    $this->purifier = new class extends AdminBaseController {
        public function exposePurify(array $data, array $keys): array
        {
            return $this->purifyHtmlKeys($data, $keys);
        }
    };
});

test('purifies flat key and removes script tags', function () {
    $data = [
        'title' => 'Safe title',
        'long_body' => '<p>Hello</p><script>alert("xss")</script><p>World</p>',
    ];
    $result = $this->purifier->exposePurify($data, ['long_body']);

    expect($result['title'])->toBe('Safe title');
    expect($result['long_body'])->not->toContain('<script>');
    expect($result['long_body'])->not->toContain('alert(');
});

test('purifies flat key and removes event handler attributes', function () {
    $data = [
        'short_body' => '<p onclick="alert(1)">Click me</p><img src="x" onerror="alert(1)">',
    ];
    $result = $this->purifier->exposePurify($data, ['short_body']);

    expect($result['short_body'])->not->toContain('onclick');
    expect($result['short_body'])->not->toContain('onerror');
});

test('preserves safe HTML in purified keys', function () {
    $data = [
        'body' => '<p>Paragraph</p><strong>Bold</strong><ul><li>Item</li></ul>',
    ];
    $result = $this->purifier->exposePurify($data, ['body']);

    expect($result['body'])->toContain('<p>');
    expect($result['body'])->toContain('</p>');
    expect($result['body'])->toContain('<strong>');
    expect($result['body'])->toContain('<ul>');
});

test('purifies nested key items.*.content', function () {
    $data = [
        'items' => [
            ['content' => '<p>OK</p>', 'title' => 'A'],
            ['content' => '<script>evil()</script>', 'title' => 'B'],
        ],
    ];
    $result = $this->purifier->exposePurify($data, ['items.*.content']);

    expect($result['items'][0]['content'])->toContain('<p>');
    expect($result['items'][1]['content'])->not->toContain('<script>');
});

test('purifies nested key faqs.*.answer', function () {
    $data = [
        'faqs' => [
            ['question' => 'Q1', 'answer' => '<p>Fine</p>'],
            ['question' => 'Q2', 'answer' => 'Text <img src=x onerror="alert(1)"> here'],
        ],
    ];
    $result = $this->purifier->exposePurify($data, ['faqs.*.answer']);

    expect($result['faqs'][0]['answer'])->toContain('<p>');
    expect($result['faqs'][1]['answer'])->not->toContain('onerror');
});

test('leaves keys not in the purify list unchanged', function () {
    $data = [
        'long_body' => '<script>bad</script>',
        'other_field' => '<script>unchanged</script>',
    ];
    $result = $this->purifier->exposePurify($data, ['long_body']);

    expect($result['long_body'])->not->toContain('<script>');
    expect($result['other_field'])->toBe('<script>unchanged</script>');
});

test('handles missing key gracefully', function () {
    $data = ['title' => 'Only title'];
    $result = $this->purifier->exposePurify($data, ['long_body']);

    expect($result)->toBe($data);
});

test('handles non-string value for purified key gracefully', function () {
    $data = ['long_body' => null, 'short_body' => 123];
    $result = $this->purifier->exposePurify($data, ['long_body', 'short_body']);

    expect($result['long_body'])->toBeNull();
    expect($result['short_body'])->toBe(123);
});

test('handles empty nested array for nested key', function () {
    $data = ['faqs' => []];
    $result = $this->purifier->exposePurify($data, ['faqs.*.answer']);

    expect($result['faqs'])->toBe([]);
});

test('multiple flat keys are all purified', function () {
    $data = [
        'short_body' => '<script>a</script>',
        'long_body' => '<script>b</script>',
    ];
    $result = $this->purifier->exposePurify($data, ['short_body', 'long_body']);

    expect($result['short_body'])->not->toContain('<script>');
    expect($result['long_body'])->not->toContain('<script>');
});
