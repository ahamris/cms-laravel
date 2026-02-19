<?php

use App\Models\Blog;
use App\Models\Comment;

test('comment scopeApproved filters only approved comments', function () {
    $blog = Blog::create([
        'title' => 'Test',
        'slug' => 'test-scope-approved',
        'short_body' => 'Short',
        'long_body' => 'Long body here.',
        'is_active' => true,
    ]);

    Comment::create([
        'entity_type' => Blog::class,
        'entity_id' => $blog->id,
        'body' => 'Approved',
        'is_approved' => true,
    ]);
    Comment::create([
        'entity_type' => Blog::class,
        'entity_id' => $blog->id,
        'body' => 'Not approved',
        'is_approved' => false,
    ]);

    $approved = Comment::approved()->get();
    expect($approved)->toHaveCount(1);
    expect($approved->first()->body)->toBe('Approved');
});

test('comment scopeParents filters only top-level comments', function () {
    $blog = Blog::create([
        'title' => 'Test',
        'slug' => 'test-scope-parents',
        'short_body' => 'Short',
        'long_body' => 'Long.',
        'is_active' => true,
    ]);

    $parent = Comment::create([
        'entity_type' => Blog::class,
        'entity_id' => $blog->id,
        'body' => 'Parent',
        'parent_id' => null,
    ]);
    Comment::create([
        'entity_type' => Blog::class,
        'entity_id' => $blog->id,
        'body' => 'Reply',
        'parent_id' => $parent->id,
    ]);

    $parents = Comment::parents()->get();
    expect($parents)->toHaveCount(1);
    expect($parents->first()->body)->toBe('Parent');
});

test('comment entity morphTo returns the related model', function () {
    $blog = Blog::create([
        'title' => 'Test',
        'slug' => 'test-entity-morph',
        'short_body' => 'Short',
        'long_body' => 'Long.',
        'is_active' => true,
    ]);

    $comment = Comment::create([
        'entity_type' => Blog::class,
        'entity_id' => $blog->id,
        'body' => 'A comment',
    ]);

    expect($comment->entity)->toBeInstanceOf(Blog::class);
    expect($comment->entity->id)->toBe($blog->id);
});
