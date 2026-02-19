<?php

use App\Helpers\Variable;

test('Variable CACHE_TTL is positive integer', function () {
    expect(Variable::CACHE_TTL)->toBeInt()->toBeGreaterThan(0);
});

test('Variable role constants are non-empty strings', function () {
    expect(Variable::ROLE_ADMIN)->toBe('admin');
    expect(Variable::ROLE_EDITOR)->toBe('editor');
    expect(Variable::ROLE_USER)->toBe('customer');
});

test('Variable fullRoles contains all role constants', function () {
    expect(Variable::$fullRoles)->toContain(Variable::ROLE_ADMIN);
    expect(Variable::$fullRoles)->toContain(Variable::ROLE_EDITOR);
    expect(Variable::$fullRoles)->toContain(Variable::ROLE_USER);
});

test('Variable expiresAt returns Carbon instance in the future', function () {
    $expires = Variable::expiresAt();
    expect($expires)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    expect($expires->isFuture())->toBeTrue();
});

test('Variable fullRolesSelector maps each role to a label', function () {
    expect(Variable::$fullRolesSelector[Variable::ROLE_ADMIN])->toBe('Admin');
    expect(Variable::$fullRolesSelector[Variable::ROLE_EDITOR])->toBe('Staff');
    expect(Variable::$fullRolesSelector[Variable::ROLE_USER])->toBe('Customer');
});

test('Variable fullPermissions has permission keys with role arrays', function () {
    expect(Variable::$fullPermissions)->toHaveKey('blog_access');
    expect(Variable::$fullPermissions['blog_access'])->toContain(Variable::ROLE_ADMIN);
});

test('Variable getAllowedRoles returns roles for permission', function () {
    $roles = Variable::getAllowedRoles('blog_access');
    expect($roles)->toContain(Variable::ROLE_ADMIN);
});
