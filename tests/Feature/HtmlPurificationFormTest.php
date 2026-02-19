<?php

use App\Helpers\Variable;
use App\Models\Blog;
use App\Models\CallAction;
use App\Models\Changelog;
use App\Models\Comment;
use App\Models\User;
use App\Models\VacancyModule\JobApplication;
use App\Models\VacancyModule\Vacancy;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

/**
 * Feature tests: form submissions that accept HTML must store purified content (no XSS).
 * Covers front Comment store, front Vacancy apply, and admin Page store (short_body/long_body).
 * Uses RefreshDatabase; requires migrations to run successfully (e.g. SQLite-compatible).
 */
test('comment store purifies body and does not store script tags', function () {
    $blog = Blog::create([
        'title' => 'Test Post',
        'slug' => 'test-post-xss',
        'short_body' => 'Short',
        'long_body' => 'Long body content here.',
        'is_active' => true,
    ]);

    $xssBody = '<p>Nice post!</p><script>alert("xss")</script><img src=x onerror="alert(1)">';

    $response = $this->post(route('comment.store'), [
        'body' => $xssBody,
        'entity_type' => Blog::class,
        'entity_id' => $blog->id,
        'guest_name' => 'Test User',
        'guest_email' => 'test@example.com',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
    $comment = Comment::latest()->first();
    expect($comment)->not->toBeNull('Expected a comment to be created');
    expect($comment->entity_id)->toBe($blog->id);
    expect($comment->entity_type)->toBeIn([Blog::class, 'blog']); // morph map may use 'blog'
    expect($comment->body)->not->toContain('<script>');
    expect($comment->body)->not->toContain('onerror');
});

test('vacancy apply submit purifies cover_letter and does not store script tags', function () {
    Notification::fake();

    $vacancy = Vacancy::create([
        'title' => 'Test Job',
        'slug' => 'test-job-xss',
        'description' => 'Description',
        'is_active' => true,
    ]);

    $xssCoverLetter = '<p>I am interested.</p><script>alert("xss")</script>';

    $response = $this->post(route('career.apply.submit', $vacancy), [
        'name' => 'Applicant',
        'email' => 'applicant@example.com',
        'cover_letter' => $xssCoverLetter,
    ]);

    $response->assertRedirect();
    $application = JobApplication::where('vacancy_id', $vacancy->id)->latest()->first();
    expect($application)->not->toBeNull();
    expect($application->cover_letter)->not->toContain('<script>');
});

test('admin page store purifies short_body and long_body', function () {
    Role::firstOrCreate(['name' => Variable::ROLE_ADMIN]);
    $user = User::create([
        'name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin-xss-test@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole(Variable::ROLE_ADMIN);

    $xssShort = '<script>bad</script>Short content here.';
    $xssLong = '<p>Intro</p><script>evil()</script><p>More text.</p>';

    $response = $this->actingAs($user)->post(route('admin.content.page.store'), [
        'title' => 'XSS Test Page',
        'slug' => 'xss-test-page',
        'short_body' => $xssShort,
        'long_body' => $xssLong,
        'is_active' => true,
    ]);

    $response->assertRedirect();
    $page = \App\Models\Page::where('slug', 'xss-test-page')->first();
    expect($page)->not->toBeNull();
    expect($page->short_body)->not->toContain('<script>');
    expect($page->long_body)->not->toContain('<script>');
});

test('admin changelog store purifies description and content', function () {
    Role::firstOrCreate(['name' => Variable::ROLE_ADMIN]);
    $user = User::create([
        'name' => 'Admin',
        'last_name' => 'Changelog',
        'email' => 'admin-changelog-xss@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole(Variable::ROLE_ADMIN);

    $xssDescription = 'Release notes <script>alert(1)</script> here.';
    $xssContent = '<p>Details</p><img src=x onerror="alert(1)">';

    $response = $this->actingAs($user)->post(route('admin.content.changelog.store'), [
        'title' => 'XSS Changelog Entry',
        'description' => $xssDescription,
        'content' => $xssContent,
        'date' => now()->format('Y-m-d'),
        'status' => 'new',
        'is_active' => true,
    ]);

    $response->assertRedirect();
    $changelog = Changelog::where('title', 'XSS Changelog Entry')->first();
    expect($changelog)->not->toBeNull();
    expect($changelog->description)->not->toContain('<script>');
    expect($changelog->content)->not->toContain('onerror');
});

test('admin call action store purifies content', function () {
    Role::firstOrCreate(['name' => Variable::ROLE_ADMIN]);
    $user = User::create([
        'name' => 'Admin',
        'last_name' => 'Call',
        'email' => 'admin-callaction-xss@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole(Variable::ROLE_ADMIN);

    $xssContent = '<p>CTA text</p><script>alert(1)</script>';

    $response = $this->actingAs($user)->post(route('admin.content.call-action.store'), [
        'title' => 'XSS Call Action',
        'content' => $xssContent,
        'background_color' => '#1e40af',
        'text_color' => '#ffffff',
        'section_identifier' => 'xss_call_action_section',
        'is_active' => true,
    ]);

    $response->assertRedirect();
    $callAction = CallAction::where('section_identifier', 'xss_call_action_section')->first();
    expect($callAction)->not->toBeNull();
    expect($callAction->content)->not->toContain('<script>');
});

test('admin vacancy store purifies description requirements and responsibilities', function () {
    Role::firstOrCreate(['name' => Variable::ROLE_ADMIN]);
    $user = User::create([
        'name' => 'Admin',
        'last_name' => 'Vacancy',
        'email' => 'admin-vacancy-xss@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole(Variable::ROLE_ADMIN);

    $xssDesc = '<p>Job</p><script>bad</script>';
    $xssReq = '<ul><li>Req</li></ul><img onerror="alert(1)">';

    $response = $this->actingAs($user)->post(route('admin.vacancies.store'), [
        'title' => 'XSS Vacancy',
        'slug' => 'xss-vacancy',
        'location' => 'Amsterdam',
        'short_code' => 'BE',
        'type' => 'full-time',
        'department' => 'Engineering',
        'description' => $xssDesc,
        'requirements' => $xssReq,
        'responsibilities' => '<p>Do work</p>',
        'salary_range' => 'Competitive',
        'closing_date' => now()->addMonth()->format('Y-m-d'),
        'is_active' => true,
    ]);

    $response->assertRedirect();
    $vacancy = Vacancy::where('slug', 'xss-vacancy')->first();
    expect($vacancy)->not->toBeNull();
    expect($vacancy->description)->not->toContain('<script>');
    expect($vacancy->requirements)->not->toContain('onerror');
});
