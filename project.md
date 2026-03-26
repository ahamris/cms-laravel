# Project Overview

## Functions (system capabilities)

1. Public headless CMS API
   - Content endpoints under `/api/*` (pages, blog, legal/static, docs, course/live-sessions, modules/features/solutions, sitemap, vacancies, settings/homepage, and more).
   - Search endpoints for content sections (using throttling and query parameters).
   - Submissions and form endpoints (contact, newsletter, vacancies apply, blog comments, etc.).
2. Admin panel (web)
   - Laravel/Livewire + Blade under `/admin/*` for CRUD operations.
   - Role-based permissions (Spatie permissions wired through `Variable::$fullPermissions` and Gate checks).
   - Theme + layout customization UI.
3. API documentation
   - OpenAPI/Swagger endpoints served under `/api/documentation` (via `darkaonline/l5-swagger`).
4. Security model for the public API
   - Origin-based restriction (CORS + optional server-side origin checks).
   - Rate limiting/throttling for API routes and form submissions.
   - Content endpoints intentionally public (no login required).
5. Search + indexing support
   - Uses Laravel Scout (and the local Typesense integration packages in `packages/`) to power search-like features.
6. Content changelog
   - Admin CRUD for changelog entries (with ordering).
   - Public endpoints for API changelog + search across changelog fields.

## Features (what users can do)

1. Publish and browse content
   - Pages (including dynamic blocks/blocks routes where applicable).
   - Blog posts and interactive comments (like/dislike).
   - Legal and static pages.
   - Documentation sections and pages.
   - Course/live-session browsing and registration.
2. Discover content
   - List/detail views for modules, features, solutions, partners/tech-stack, sitemap, and vacancies.
   - Search endpoints for pages/blog/docs/changelog/etc. (with throttling).
3. Contact and conversion flows
   - Contact form + subject dropdown support.
   - Newsletter subscription endpoint.
   - Vacancy apply flow (view, apply, submit).
4. Admin management
   - Users, roles, permissions.
   - Site/theme configuration and admin menu structure.
   - Content CRUD for many content types.
   - Ordering controls for list-style content.
5. Developer experience
   - OpenAPI schemas and Swagger UI for endpoint discovery and exploration.
   - Test suite (Pest) and configurable endpoints.

## User Stories

### Visitor (public end-user)
- As a visitor, I want to read published pages and documentation without logging in, so that I can quickly find information.
- As a visitor, I want to search for content (pages/blog/docs/changelog) so that I can locate relevant results fast.
- As a visitor, I want to submit a contact request or newsletter subscription from the SPA so that the site can respond to my inquiry.

### Prospective candidate
- As a prospective candidate, I want to view a vacancy and submit an application so that I can be considered for roles.

### Admin / Editor (staff user)
- As an admin/editor, I want to create, edit, and publish content entries (pages/blog/docs/vacancies/changelog) through the admin panel so that the site stays up to date.
- As an admin/editor, I want to manage ordering (sort order) for list sections so that content appears in the right sequence.
- As an admin/editor, I want to update theme and fonts from the admin UI so that the branding matches our needs.

### API consumer (SPA developer / integrator)
- As an API consumer, I want to integrate the React SPA with stable REST endpoints so that the SPA can render content reliably.
- As an integrator, I want Swagger/OpenAPI documentation so that I can understand request/response shapes and iterate faster.

### Security / Operations (platform owner)
- As a platform owner, I want public APIs restricted to allowed origins and throttled requests so that the CMS is protected from abuse.

