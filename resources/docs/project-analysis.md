# Headless CMS — Project Analysis

This document describes what the project is, what it does, and how its main parts fit together.

---

## 1. What the project is

**headless-cms** is a Laravel 12 application that acts as:

1. **Headless CMS API** — A REST API under `/api/` for a React (or other) SPA: public content API (pages, blog, legal, static, docs, live-sessions, modules, features, solutions, sitemap, vacancies, settings), secured by allowed origins (no authentication required for content).
2. **Built-in admin panel** — A Laravel-based admin UI (Livewire, Blade) for managing users, roles, permissions, settings, menu, and theme. The frontend SPA is separate; only the admin is served by this app.
3. **API documentation** — OpenAPI/Swagger docs generated with L5-Swagger, exposed at `/api/documentation`.

So: one Laravel app = API for the SPA + admin panel + Swagger.

---

## 2. Tech stack (summary)

| Layer | Technology |
|-------|------------|
| Framework | Laravel 12, PHP ^8.4 |
| API content | Public; allowed origins only (no auth) |
| API docs | darkaonline/l5-swagger (OpenAPI 3) |
| Admin UI | Livewire 4, Blade, Tailwind-style components |
| Roles/permissions | Spatie Laravel Permission (used in seeders/controllers) |
| Auth (web) | Laravel Fortify (implied by Fortify actions in `app/Actions/Fortify`) |
| Testing | Pest 4 |
| Tooling | Laravel Pint, Laravel Pail, Boost, Sail (dev) |

---

## 3. What the project does

### 3.1 Headless CMS API (`/api/*`)

- **Content (public, no auth)** — All content endpoints are public. Access is restricted by the **allowed origins** check (middleware `frontend.origins`; config `config/cors.php`, env `CORS_ALLOWED_ORIGINS` or `FRONTEND_ALLOWED_ORIGINS`). If the list is empty, all origins are allowed (development only).
  - **GET /api/pages**, **GET /api/pages/{slug}** — Paginated list and single published page by slug.
  - **GET /api/blog**, **GET /api/blog/{slug}** — Blog preview and single post.
  - **GET /api/legal/{slug}**, **GET /api/static/{slug}** — Legal and static pages.
  - **GET /api/settings** — Site and theme settings (e.g. for homepage).
  - **GET /api/docs**, **GET /api/docs/search**, **GET /api/docs/{version}/{section}/{page}** — Documentation.
  - **GET /api/live-sessions**, **GET /api/live-sessions/{slug}** — Live sessions.
  - **GET /api/modules**, **GET /api/modules/{slug}** — Modules.
  - **GET /api/features**, **GET /api/features/{anchor}** — Features.
  - **GET /api/solutions**, **GET /api/solutions/{anchor}** — Solutions.
  - **GET /api/sitemap** — Sitemap as JSON.
  - **GET /api/vacancies**, **GET /api/vacancies/{slug}** — Vacancies.

- **Content models** — `Page`, `Blog`, `Legal`, `StaticPage`, etc. API responses use Laravel API Resources (e.g. `PageResource`, `BlogResource`).

- **Security** — No login or Bearer token for content. Security is via **allowed origins** only. Use `FRONTEND_ALLOWED_ORIGINS` in production. Analytics endpoints under `/api/analytics/` are rate-limited and public. See [security.md](security.md).

Result: the React SPA can read all content without authenticating; restrict who can call the API by setting allowed origins. Admin auth is web/session only (no API auth routes in this app).

### 3.2 Admin panel (web, `/admin/*`)

- **Entry** — `/` redirects to `/admin`. Admin routes are under `Route::prefix('admin')->middleware(['auth'])`.

- **Features**
  - **Dashboard** — Home and analytics (AdminController).
  - **Users** — CRUD (`users` resource), with roles (e.g. admin, editor, user) and role badges.
  - **Roles** — CRUD (`roles` resource).
  - **Permissions** — CRUD (`permissions` resource); permissions are mapped to roles via `Variable::$fullPermissions` and seeders.
  - **Settings** — General, menu (Livewire MenuManager), theme (Livewire ThemeManager).
  - **Profile** — Edit profile and update password (Fortify-style actions).
  - **2FA** — Two-factor recovery route and Livewire two-factor settings.

- **UI** — Livewire components, Blade layouts (`admin`, `auth`), reusable UI components (buttons, modals, inputs, etc.) and documentation views for them. Styling via `resources/css/admin.css` and Tailwind-style component classes.

- **Auth** — Web guard, session-based. Default accounts and roles come from `Variable::DEFAULT_ACCOUNTS` and `Variable::$fullRoles` (AdminSeeder, PermissionSeeder).

Result: admins manage users, roles, permissions, menu, and theme in this Laravel app. Content (e.g. pages) can be managed here or via future admin-only API endpoints.

### 3.3 Swagger / OpenAPI

- **Package** — `darkaonline/l5-swagger`. Base spec in `app/OpenApi/BaseOpenApi.php` (info, server; no auth required for content).
- **Controllers** — Frontend API controllers in `app/Http/Controllers/Api/Frontend/` and `BlogController` are annotated with OpenAPI attributes. Resources (e.g. `PageResource`, `BlogResource`) define response schemas.
- **Generation** — `php artisan l5-swagger:generate` writes JSON/YAML to `storage/api-docs/`.
- **UI** — Swagger UI is served at **/api/documentation** (package default).

Result: API consumers and developers can read and try the headless CMS API from the browser.

---

## 4. Project structure (high level)

```
├── app/
│   ├── OpenApi.php                 # Base OpenAPI info & security schemes
│   ├── Actions/Fortify/            # Profile/password/registration (admin auth)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/Frontend/       # Headless CMS content API (pages, blog, etc.)
│   │   │   └── Api/                # AnalyticsTrackingController
│   │   │   └── Admin/              # Admin panel (dashboard, users, roles, etc.)
│   │   ├── Requests/Api/           # e.g. LoginRequest
│   │   └── Resources/              # UserResource, PageResource
│   ├── Livewire/Admin/             # Menu, theme, table, search, sidebar, 2FA
│   ├── Models/                     # User (HasApiTokens), Page; Admin/* for menu/theme
│   ├── Helpers/                    # Variable (roles, permissions, defaults), Theme, Flash
│   └── View/Components/            # UI and layout components
├── routes/
│   ├── api.php                     # /api/* (content + analytics)
│   └── web.php                     # /admin/* (admin panel), redirect /
├── config/
│   ├── auth.php                    # web + api (sanctum) guards
│   ├── sanctum.php                 # stateful domains
│   └── cors.php                    # API + credentials
├── database/
│   ├── migrations/                 # users, pages, personal_access_tokens, roles/permissions, etc.
│   ├── seeders/                    # AdminSeeder, PermissionSeeder, AdminMenuSeeder
│   └── factories/                  # User, Page
└── resources/
    ├── docs/                       # This analysis
    ├── views/                      # admin/*, livewire/*, components/*, errors/*
    └── css/admin.css               # Admin panel styles
```

---

## 5. Data and configuration

- **Roles** — Defined in `Variable::$fullRoles` (e.g. admin, editor, user). Created by AdminSeeder.
- **Permissions** — Defined in `Variable::$fullPermissions` (site_setting, user_*, permission_*, role_*, media_*, etc.) and assigned to roles by PermissionSeeder.
- **Default admin** — From `Variable::DEFAULT_ACCOUNTS` (e.g. admin@example.com / password), created by AdminSeeder.
- **API** — All API routes under `/api/` (no version prefix). Content is public; restrict access via `FRONTEND_ALLOWED_ORIGINS`.

---

## 6. Results (what you get)

| Deliverable | Result |
|------------|--------|
| **SPA backend** | REST API with read-only content (pages, blog, legal, static, docs, live-sessions, modules, features, solutions, sitemap, vacancies, settings). Public; secured by allowed origins. |
| **Admin panel** | Full in-app UI for users, roles, permissions, settings, menu, theme, profile, 2FA. |
| **Swagger** | OpenAPI 3 spec and Swagger UI at `/api/documentation` for the headless CMS API. |
| **Content model** | `Page` with publish workflow; public endpoints for listing and fetching by slug. |

The React SPA uses the API for content (no auth required); admins use the Laravel admin for user and system management. No React code lives in this repo; it is a backend + admin only.
