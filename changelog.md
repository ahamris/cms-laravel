# Changelog

## 2026-03-26 — Admin UX, design system, and fixes

### Added

- Hero Section UI block: `ElementType::HeroSection`, `HeroSectionElementController`, admin form and show partials, `admin/element-hero-section` resource routes, `ElementSeeder` sample, and `hero_section` allowed in page row section categories.
- `resources/views/components/ui/slide-over.blade.php` (`x-ui.slide-over`) for non-destructive side panels (e.g. article social posting).
- Design docs: `docs/cursor-admin-design-system.md`, `docs/admin-ds-compliance-checklist.md`, `docs/OPCM_Design_Guide_Standard.md`, `docs/ui-ux-guidelines.md` (plus `docs/OPCM_Design_System.md` / `.docx` where tracked).
- OpenAPI scaffolding: `app/OpenApi/AnalyticsPaths.php`, `FrontendExtraPaths.php`, `V1Paths.php`, and `openapi.json`.
- Hero Video element: `POST admin/element-hero-video/{element}/clone` and clearer index/show UX (clone, delete confirmation, labels).

### Changed

- Admin sidebar: Content structure (Pages, UI Blocks with hero section entry), Articles children (Categories, Category groups, Types, Tags, Comments), naming aligned with UI terminology.
- Shared `livewire:admin.table`: toolbar layout (search, filters, live row count, bulk delete), DS-oriented typography and row actions, optional empty-state CTA; new props `entityCountLabel`, `emptyStateTitle`, `emptyCtaUrl`, `emptyCtaLabel`.
- Pages and Articles modules: consistent headers (`text-xl` titles), `x-ui.button`, table metadata on index, save / save-and-close behaviour on forms where applicable.
- Articles (`blog`): index uses slide-overs for social flows; create/edit use toasts instead of `alert()`; AI regenerate uses a confirmation modal instead of `confirm()`; copy uses “article” wording.
- Taxonomies: article categories, category groups (blog categories), article types, tags, comments — list headers and table integration; tags quick-add uses `x-ui.input` / `x-ui.button`.
- UI block element index/show: delete via `x-ui.modal`; clone without `confirm()`.
- Breadcrumbs: default `maxItems` reduced; overflow dropdown uses `z-50` instead of `z-[9999]`.

### Fixed

- `DashboardController`: cache key `dashboard.contentStatsQuery.v2` stores only plain arrays so PHP 8.4+ does not hit `array_column()` on incomplete objects after cache round-trips.
- `routes/admin.php`: compatibility POST routes for `content/page` and `content/blog` no longer steal names from `admin.page.store` / `admin.blog.store`.

---

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

