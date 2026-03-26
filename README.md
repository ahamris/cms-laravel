# Headless CMS (Laravel 12)

This repository contains a Laravel-based backend for a headless CMS setup:

- A public content REST API under `/api/*` (no login required for content; protected by CORS + throttling and optional origin restrictions).
- A built-in admin panel under `/admin/*` (Livewire + Blade) for managing content, users, roles/permissions, settings, and theme.
- OpenAPI/Swagger documentation served from `/api/documentation`.

## Main capabilities

### Public content API (examples)

The API includes endpoints for:

- Pages: `GET /api/pages`, `GET /api/pages/{slug}`, tree/blocks routes, and search.
- Blog: `GET /api/blog/*` plus comments/likes endpoints.
- Legal/static: `GET /api/legal/{slug}`, `GET /api/static/{slug}`.
- Docs: `GET /api/docs`, `GET /api/docs/search`, `GET /api/docs/{section}/{page}`.
- Course/live sessions: course categories, videos, recordings, registrations.
- Modules/features/solutions: listing + detail routes (anchors/slugs).
- Vacancies: list/detail plus apply + submit endpoints.
- Contact/newsletter/pricing/trial endpoints.
- Changelog: `GET /api/changelog`, `GET /api/changelog/search`, `GET /api/changelog/{slug}`.

### Admin panel

Admin features include:

- Authentication & profile management (web/session-based).
- Role-based access control (Spatie permissions + `Variable::$fullPermissions`).
- CRUD interfaces for core content modules.
- Theme customization via Livewire.
- API-only changelog admin views.
- Ordering controls for list-style content.

## Security notes (content API)

- CORS is handled via `config/cors.php` and environment variables such as `FRONTEND_ALLOWED_ORIGINS` / `CORS_ALLOWED_ORIGINS`.
- API routes are rate-limited via Laravel throttling middleware.
- Content endpoints are public by design; restrict by allowed origins (and optionally an API key, if configured in the app).

## Local setup

1. Copy env file: `.env.example` -> `.env`
2. Install PHP dependencies: `composer install`
3. Install JS dependencies: `npm install`
4. Run migrations: `php artisan migrate`
5. Seed development data (if applicable): `php artisan db:seed`
6. Run dev servers:
   - `php artisan serve`
   - `npm run dev`

## Testing

Run the Laravel test suite:

`php artisan test`

## Docs

- Security hardening notes: `resources/docs/security.md`
- Project overview: `project.md`
