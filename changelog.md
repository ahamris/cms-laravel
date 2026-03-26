# Changelog

## Unreleased

Note: I couldn’t locate the literal marker `download@docs` anywhere in this workspace, so the entry below summarizes the changes that are present in the repo right now (especially the newly added Changelog feature + the docs resources that exist under `docs/` and `resources/docs/`).

### Changelog feature (new)

- Added `changelogs` database table with:
  - `title`, `description`, `content` (nullable), `video_url` (nullable)
  - `date`, `status` (`new|improved|fixed|api`)
  - `slug` (unique), `features` (JSON), `steps` (JSON)
  - `is_active` (default `true`), `sort_order` (default `0`)
- Implemented `App\Models\Changelog`
  - `sluggable` slug generation from `title` (updates on title change)
  - cache helpers (`getCached`, `getCachedFour`, `getCachedFourByStatus`, `getBySlug`, `getRecent`, `getByStatus`) and cache invalidation on create/update/delete
  - scopes: `active`, `ordered`, `byStatus`
  - computed attributes for UI: status badge color, status display name, and human-readable dates
  - route binding behavior: uses `id` for `admin/*` routes, `slug` otherwise
- Added `App\Http\Requests\ChangelogRequest` validation
  - validates all required fields (`title`, `description`, `date`, `status`)
  - validates `slug` uniqueness and optional `features`/`steps` arrays
  - normalizes `features` and `steps` when submitted as comma-separated strings
  - normalizes `is_active` into a boolean
- Added admin UI controller `App\Http\Controllers\Admin\ChangelogController`
  - CRUD pages for changelog entries
  - filters empty entries in `features` and `steps`
  - sanitizes `description` and `content` HTML keys via `purifyHtmlKeys(...)`
  - supports updating list ordering via `POST admin/changelogs/update-order`
- Added API controller `App\Http\Controllers\Api\Frontend\ChangelogController`
  - `GET /api/changelog`: paginated list (filterable by `status`)
  - `GET /api/changelog/search`: query search across `title`, `description`, `content` (returns templated JSON)
  - `GET /api/changelog/{slug}`: returns a single active entry or `404`
- Added API admin listing for API-only changelog entries:
  - `App\Http\Controllers\Admin\ApiChangelogController`
  - routes: `GET admin/api-changelog` and `GET admin/api-changelog/{changelog}`
- Registered routes in `routes/admin.php`
  - `admin/changelog` resource routes + `admin/changelogs/update-order`
  - `admin/api-changelog` routes
- Added OpenAPI schema `App\OpenApi\Schemas\ChangelogSchema`
  - `ChangelogEntry`
  - `ChangelogListResponse`
- Added seed + factory + tests
  - `database/seeders/ChangelogSeeder` (skips in production; creates initial entries with mixed statuses)
  - `database/factories/ChangelogFactory`
  - unit tests for slug generation, status scoping, status color mapping, caching (`tests/Unit/Models/ChangelogTest.php`)
  - request validation tests (`tests/Feature/RequestValidation/ChangelogRequestValidationTest.php`)
  - security test: admin store purifies `description` and `content` against XSS (`tests/Feature/HtmlPurificationFormTest.php`)

### Authorization update (admin)

- Added/registered changelog permissions in `App\Helpers\Variable::$fullPermissions`:
  - `changelog_access`, `changelog_show`, `changelog_create`, `changelog_edit`, `changelog_delete`

### Documentation resources (added/updated)

- Added/updated docs under `resources/docs/`:
  - `resources/docs/security.md`
  - `resources/docs/project-analysis.md`
  - `resources/docs/organization.json`
  - `resources/docs/inbound-marketing-orchestrator-plan.md`
  - `resources/docs/configurator.html`
  - `resources/docs/ai-blog-module.md`
  - `resources/docs/Privacyverklaring.html`
  - `resources/docs/Cookiebeleid.html`
  - `resources/docs/Algemene Voorwaarden - B2C.html`
  - `resources/docs/Algemene Voorwaarden - B2B.html`
- Added docs under `docs/`:
  - `docs/GIT_CHEAT_SHEET.md`
  - `docs/COOKIE_CONSENT_IMPLEMENTATION_PLAN.md`
  - `docs/FRONTEND-API.md`
  - `docs/ADMIN_PANEL_GUIDE.md`

